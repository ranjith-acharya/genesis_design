<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        $cards = \Stripe\PaymentMethod::all([
            'customer' => Auth::user()->stripe_id,
            'type' => 'card',
        ]);

        $data = collect($cards->data);

        $filtered = $data->map(function ($item, $key) {
            return [
                "id" => $item->id,
                "card" => $item->card,
            ];
        });

        $default = $filtered->firstWhere('id', '=', Auth::user()->default_payment_method);

        $filtered = $filtered->reject(function ($item, $key) {
            return Auth::user()->default_payment_method === $item['id'];
        });

        return view('profile.profile', ["cards" => $filtered, "default" => $default]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    public function adminUpdate(Request $request, $id){
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required|max:10',
        ]);
    
        $user = User::find($id);
        $user->update($request->all());
        return back()->with('success','Details updated successfully!');
    }

    public function managerUpdate(Request $request, $id){
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required|max:10',
        ]);
    
        $user = User::find($id);
        $user->update($request->all());
        return back()->with('success','Details updated successfully!');
    }

    public function engineerUpdate(Request $request, $id){
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required|max:10',
        ]);
    
        $user = User::find($id);
        $user->update($request->all());
        return back()->with('success','Details updated successfully!');
    }

    public function customerUpdate(Request $request, $id){
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required|max:10',
        ]);
    
        $user = User::find($id);
        $user->update($request->all());
        return back()->with('success','Details updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
