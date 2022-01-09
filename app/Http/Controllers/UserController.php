<?php

namespace App\Http\Controllers;

use App\Models\User;
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
            $users = User::all();
            return response()->json([
                'users' => $users,
                'status' => 200
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'message' => 'You are not authorized to access this resource',
                'status' => 401
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
            $user = User::find($id);
            if ($user) {
                return response()->json([
                    'user' => $user,
                    'status' => 200
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'message' => 'User not found',
                    'status' => 404
                ], Response::HTTP_NOT_FOUND);
            }
        } else {
            return response()->json([
                'message' => 'You are not authorized to access this resource',
                'status' => 401
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
                    'status' => 200
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'message' => 'You are not authorized to update this user.',
                    'status' => 400
                ], Response::HTTP_BAD_REQUEST);
            }
        } else {
            return response()->json([
                'message' => 'User not found',
                'status' => 404
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
                    'status' => 200
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'message' => 'You are not authorized to delete this user.',
                    'status' => 400
                ], Response::HTTP_BAD_REQUEST);
            }
        } else {
            return response()->json([
                'message' => 'User not found',
                'status' => 404
            ], Response::HTTP_NOT_FOUND);
        }
    }

    // get all active user
    public function active()
    {
        $users = User::where('status', 'active')->get();

        return response([
            'users' => $users,
            'message' => 'Active user',
            'status' => 200
        ], Response::HTTP_OK);
    }

    // get all inactive user
    public function inactive()
    {
        $users = User::where('status', 'inactive')->get();

        return response([
            'users' => $users,
            'message' => 'Inactive user',
            'status' => 200
        ], Response::HTTP_OK);
    }

    // get all blocked user
    public function blocked()
    {
        $users = User::where('status', 'blocked')->get();

        return response([
            'users' => $users,
            'message' => 'Blocked user',
            'status' => 200
        ], Response::HTTP_OK);
    }

    // search user by name or phone
    public function search(Request $request)
    {
        $currentUser = auth()->user();
        if ($currentUser->role == 'admin') {
            $users = User::where('name', 'like', '%' . $request->name . '%')
                ->orWhere('phone', 'like', '%' . $request->phone . '%')
                ->get();

            return response([
                'users' => $users,
                'message' => 'Search user',
                'status' => 200
            ], Response::HTTP_OK);
        }
        else {
            return response()->json([
                'message' => 'You are not authorized to access this resource',
                'status' => 401
            ], Response::HTTP_UNAUTHORIZED);
        }
    }
}
