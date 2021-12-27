<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\ProjectType;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(){
        
        $roles = Role::all();
        $users = User::all()->except(1);
        return view('admin.user.index', compact('users', 'roles'));
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
        $this->validate($request,[
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone' => ['max:10'],
            'role_name' => ['required'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ], [
            'first_name.required' => 'First name required',
            'last_name.required' => 'Last name required',
            'role_name.required' => 'Select role',
            'email.required' => 'Email address required',
            'password.required' => 'Password required',
        ]);
        
        $userData = $request->all();
        $userData['role'] = 'engineer';
        $userData['password'] = Hash::make($userData['password']);
        $user = User::create($userData);
        $user->assignRole($userData['role_name']);

        return back()->with('success', 'User  created successfully!');
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
        $roles = Role::all();
        $user = User::find($id);
        return view('admin.user.update', compact('user', 'roles'));
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
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required|max:10',
        ]);
    
        $user = User::find($id);
        $user->update($request->all());
        //return $user;
        $user->assignRole($request['role_name']);
        return redirect()->route('admin.users.index')->with('success','User details updated successfully');
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
