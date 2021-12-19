<?php

namespace App\Http\Controllers;

use App\Models\user;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Models\user  $user
     * @return \Illuminate\Http\Response
     */
    public function show(user $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\user  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(user $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\user  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if ($user && $user->status == 'active') {
            $user->update($request->all());
            return response()->json([
                'message' => 'User updated successfully',
                'user' => $user,
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'User not found',
            ], Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\user  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if ($user && $user->status == 'active') {
            $user->update([
                'status' => 'inactive',
            ]);
            return response()->json([
                'message' => 'User deleted successfully',
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'User not found',
            ], Response::HTTP_NOT_FOUND);
        }
    }

    // get all active user
    public function active()
    {
        $users = User::where('status', 'active')->get();

        return response([
            'users' => $users,
            'message' => 'Active user'
        ], Response::HTTP_OK);
    }

    // get all inactive user
    public function inactive()
    {
        $users = User::where('status', 'inactive')->get();

        return response([
            'users' => $users,
            'message' => 'Inactive user'
        ], Response::HTTP_OK);
    }
}
