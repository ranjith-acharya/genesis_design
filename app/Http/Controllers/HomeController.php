<?php

namespace App\Http\Controllers;

use App\ProjectType;
use App\Project;
use App\Statics\Statics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $return = null;
        switch (Auth::user()->role) {
            case (Statics::USER_TYPE_CUSTOMER || Statics::USER_TYPE_ENGINEER):
                $types = ProjectType::where('is_hidden', false)->get();
                $projectQuery = Project::latest()->paginate(3);
                //$return = view('home')->with('projectTypes', $types);
                $return = view('index')->with('projectQuery', $projectQuery)->with('projectTypes',$types);
                break;
            case (Statics::USER_TYPE_ADMIN):
                $types = ProjectType::where('is_hidden', false)->get();
                $return = view('admin.home')->with('projectTypes', $types);
                break;
        }

        return $return;
    }
}
