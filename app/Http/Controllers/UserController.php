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
        $currentUser = auth()->user();
        if ($currentUser->role == 'admin') {
            $users = user::all();
            return response()->json([
                'users' => $users,
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'You are not authorized to access this resource',
            ], Response::HTTP_UNAUTHORIZED);
        }
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
    public function show($id)
    {
        //
        $currentUser = auth()->user();
        if ($currentUser->role == 'admin') {
            $user = user::find($id);
            if ($user) {
                return response()->json([
                    'user' => $user,
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'message' => 'User not found',
                ], Response::HTTP_NOT_FOUND);
            }
        } else {
            return response()->json([
                'message' => 'You are not authorized to access this resource',
            ], Response::HTTP_UNAUTHORIZED);
        }
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
            // check $user is current user
            if ($user->id == auth()->user()->id) {
                $user->update($request->all());
                return response()->json([
                    'message' => 'User updated successfully',
                    'user' => $user,
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'message' => 'You are not authorized to update this user.',
                ], Response::HTTP_BAD_REQUEST);
            }
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
        $currentUser = auth()->user();
        $user = User::find($id);

        if ($user && $user->status == 'active') {
            // check $user is current user or admin
            if ($user->id == $currentUser->id || $currentUser->role == 'admin') {
                $user->update([
                    'status' => 'blocked',
                ]);
                // delete all tokens of user
                $user->tokens()->delete();

                return response()->json([
                    'message' => 'User deleted successfully',
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'message' => 'You are not authorized to delete this user.',
                ], Response::HTTP_BAD_REQUEST);
            }
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

    // get all blocked user
    public function blocked()
    {
        $users = User::where('status', 'blocked')->get();

        return response([
            'users' => $users,
            'message' => 'Blocked user'
        ], Response::HTTP_OK);
    }

    // search user by name or phone
    public function search(Request $request)
    {
        $users = User::where('name', 'like', '%' . $request->name . '%')
            ->orWhere('phone', 'like', '%' . $request->phone . '%')
            ->get();

        return response([
            'users' => $users,
            'message' => 'Search user'
        ], Response::HTTP_OK);
    }
}
