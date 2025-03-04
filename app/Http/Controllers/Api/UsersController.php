<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{
    /**
     * List all users
     * @response array{data: User[]}
     */
    public function index()
    {
        $users = User::all();

        return response()->json($users);
    }

    /**
     * Create User from query params
     * @response {data: User} | {errors : object}
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email:rfc|unique:users|max:255',
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                /** @var object*/
                'errors' => $validator->errors()
            ], 400);
        }

        $user = new User;
        $user->email = $request->input('email');
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->save();

        return response()->json([
            'data' => $user
        ], 201);
    }

    /**
     * Update the specified user.
     * @response {data: User} | {errors : object}
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:users,id',
            'email' => ['required', 'email:rfc', 'max:255',
                        Rule::unique('users', 'email')->ignore($request->id)
                    ],
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                /** @var object*/
                'errors' => $validator->errors()
            ], 400);
        }

        $user = User::find($request->input('id'));
        $user->email = $request->input('email');
        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->save();

        return response()->json([
            'data' => $user
        ]);
    }

    /**
     * Delete the specified user.
     * @response {data: string} | {errors : object}
     */
    public function delete(Request $request, string $id)
    {
        $validator = Validator::make(['id' => $id], [
            'id' => 'required|exists:users,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                /** @var object*/
                'errors' => $validator->errors()
            ], 400);
        }

        $user = User::find($id);
        $user->delete();

        return response()->json([
            'data' => 'User was deleted with success'
        ]);
    }
}
