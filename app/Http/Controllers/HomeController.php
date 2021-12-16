<?php

namespace App\Http\Controllers;

use App\ProjectType;
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
                $return = view('home')->with('projectTypes', $types);
                break;
            case (Statics::USER_TYPE_ADMIN):
                $return = view('admin.home');
                break;
        }

        return $return;
    }
}
