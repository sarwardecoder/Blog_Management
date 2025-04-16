<?php

namespace App\Http\Controllers;

use App\Helper\JWTToken;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function userLogin(Request $request)
    {
        $count=User::where('email',$request->input('email'))->where('password',
        $request->input('password'))->select('id')->first();
        if($count!==null){
            // $token=JWTToken::createToken($request->input('email'),$count->id);
           
            // return response()->json([
            //     'status'=>'success',
            //     'message'=>'User Login successful',
            //     'token'=>$token
            // ],200)->cookie('token',$token,60*24*30);
            $email=$request->input('email');
            $user_id=$count->id;
            $request->session()->put('email',$email);
            $request->session()->put('user_id',$user_id);
$data=['message'=>'User Login Successful','status'=>'true','error'=>''];
return redirect('/dashboard')->with($data);
   }else{
            // return response([
            //     'status'=>'failed',
            //     'message'=>'unauthorized',
            // ],200);
$data=['message'=>'login failed','status'=>false,'error'=>''];
return redirect('/login')->with($data);


        }
    }
    public function confirmLogin()
    {
        return Inertia::render('Users/Login');    
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //register a user
        return Inertia::render('User/Create');
    }
      
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8'
        ]);

        $validated['password'] = bcrypt($validated['password']);
        $user = User::create($validated);

        return Redirect::route('users.index')->with('message', 'User created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Inertia::render('User/Show', [
            'user' => User::findOrFail($id)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return Inertia::render('User/Edit', [
            'user' => User::findOrFail($id)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'username' => 'sometimes|string|max:255|unique:users,username,'.$id,
            'email' => 'sometimes|email|unique:users,email,'.$id,
            'password' => 'sometimes|string|min:8',
            'profile_pic' => 'sometimes|string'
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        }

        $user->update($validated);

        return Redirect::route('users.index')->with('message', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return Redirect::route('users.index')->with('message', 'User deleted successfully.');
    }
}
