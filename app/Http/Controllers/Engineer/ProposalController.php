<?php

namespace App\Http\Controllers\Engineer;

use App\Http\Controllers\Controller;
use App\Mail\Notification;
use App\Proposal;
use App\ProposalFile;
use App\Statics\Statics;
use App\SystemDesign;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ProposalController extends Controller
{
    public function from(Request $request, $design)
    {
        $engineer = SystemDesign::findOrFail($design)->project->engineer;
        //return $engineer;
        $design = $engineer->assignedDesigns()->with(['project.customer', 'type', 'changeRequests' => function ($query) use ($request) {
            if ($request->changeRequest)
                $query->findOrFail($request->changeRequest);
        }])->withCount('proposals')->where('system_designs.id', $design)->firstOrFail();

        if ($design->proposals_count)
            if (!$request->has('changeRequest'))
                return abort(403, 'changeRequest needed after first proposal');

        return view('engineer.proposal.new', ["design" => $design]);
    }

    private function capture($payment_id)
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $intent = \Stripe\PaymentIntent::retrieve($payment_id);
        $response = $intent->capture();
        Log::info("captured $payment_id", $response->toArray());
        return $response;
    }

    public function insert(Request $request)
    {
        $this->validate($request, [
            "note" => 'required',
            "design" => "required",
            "change_request" => "nullable"
        ]);

        $cr = null;
        $engineer = SystemDesign::findOrFail($request->design)->project->engineer;
        //return $engineer;
        $design = $engineer->assignedDesigns()->with(['project.customer', 'type', 'proposals', 'changeRequests' => function ($query) use ($request) {
            $query->find($request->change_request);
        }])->where('system_designs.id', $request->design)->firstOrFail();


//         This is the first proposal
        if (sizeof($design->proposals) === 0) {
            if (!$design->paymant_date) {
                $capture = $this->capture($design->stripe_payment_code);

                if ($capture->status != 'succeeded')
                    return abort(500, 'Could not capture payment');

                $design->payment_date = now();
                $design->update();
            }
        } else {
            $cr = $design->changeRequests[0];

            if ($cr->stripe_payment_code !== 'skipped') {
                $capture = $this->capture($cr->stripe_payment_code);

                if ($capture->status != 'succeeded')
                    return abort(500, 'Could not capture payment');
            }

            $cr->payment_date = now();
            $cr->status = Statics::CHANGE_REQUEST_STATUS_CLOSED;
            $cr->update();
        }

        $proposal = new Proposal();
        $proposal->note = $request->note;
        $proposal->engineer_id = Auth::id();
        $proposal->system_design_id = $design->id;
        if ($cr)
            $proposal->change_request_id = $cr->id;
        $proposal->save();

        if ($cr)
            $body = "You have a proposal for project: <b>" . $design->project->name . "</b> for a change request in design: " . $design->type->name;
        else
            $body = "You have a proposal for project: <b>" . $design->project->name . "</b> for design: " . $design->type->name;

        Mail::to($design->project->customer->email)
            ->send(new Notification($design->project->customer->email, "New Proposal for: " . $design->project->name, $body, route('proposal.view', $design->id) . "?proposal=" . $proposal->id, "View Proposal"));

        return $proposal;
    }

    public function attachFile(Request $request)
    {
        $this->validate($request, [
            'type' => 'required|string',
            'path' => 'required|string|max:255',
            'proposal_id' => 'required|numeric',
            'content_type' => 'required|string'
        ]);
        return ProposalFile::create($request->all());
    }

    public function view(Request $request, $design)
    {
        $user = SystemDesign::findOrFail($design)->project->engineer;
        //return $user;
        $design = $user->designs()->with(['proposals' => function ($query) use ($request) {
            $query->with('changeRequest.files')->findOrFail($request->proposal);
        }, 'type.latestPrice'])->where('system_designs.id', $design)->firstOrFail();

        //return $design;
        return view('proposal.view', ["design" => $design]);
    }

    public function getFile(Request $request)
    {
        //return $request;
        $this->validate($request, [
            'design' => 'required|numeric',
            'proposal' => 'required|numeric',
            'file' => 'required|numeric'
        ]);

        $engineer = SystemDesign::findOrFail($request->design)->project->engineer;
        //return $engineer;
        $design = $engineer->designs()->with(['proposals' => function ($query) use ($request) {
            $query->findOrFail($request->proposal);
        }])->where('system_designs.id', $request->design)->firstOrFail();
        $file = $design->proposals[0]->files->firstWhere('id', $request->file);
        if ($file) {
            $url = Http::get(env('SUN_STORAGE') . "/file/url?ttl_seconds=900&api-key=" . env('SUN_STORAGE_KEY') . "&file_path=" . $file->path)->body();
            return redirect()->away(json_decode($url));
        } else {
            abort(404);
            return false;
        }
    }
}
