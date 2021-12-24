<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\ProjectType;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function customerIndex()
    {
        //return $role;
        $types = ProjectType::where('is_hidden', false)->get();
        return view('admin.customer.home')->with('projectTypes', $types);
        //$users = User::where('role', '=',  $role)->get();
        //return $users;
    }

    public function engineerIndex()
    {
        //return $role;
        $types = ProjectType::where('is_hidden', false)->get();
        return view('admin.engineer.home')->with('projectTypes', $types);
        //$users = User::where('role', '=',  $role)->get();
        //return $users;
    }

    public function adminIndex(){
        return view('admin.home');
    }

    public function getList(Request $request, $role)
    {
        //return "sdfsdf";
        //return $role;
        $users = User::where('role', $role);
        //return view('admin.users');
        return $users->latest()->paginate(5);
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
