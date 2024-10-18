<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $orderBy = $request->get('order_by', 'name');
        $orderType = $request->get('order_type', 'asc');
        $search = $request->get('filter', '');

        $q = User::query();
        $q->orderBy($orderBy, $orderType);
        if (!empty($search)) {
            $q->where('name', 'like', '%' . $search . '%');
            $q->orWhere('email', 'like', '%' . $search . '%');
        }

        $users = $q->paginate($request->get('per_page', 10))->withQueryString();

        return response()->json($users);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|unique:users,email|min:3|max:100',
            'password' => 'required|min:5|max:40',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->string('password')),
        ]);

        return response()->json([
            'message' => 'New user created!',
            'data' => $user,
        ]);
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'required|max:255',
            'email' => 'required|unique:users,email,' . $id . '|min:3|max:100',
        ];

        if ($request->get('password') != '') {
            $rules['password'] = 'required|min:5|max:40';
        }

        $request->validate($rules);

        $user = User::find($id);

        $user->fill($request->only(['name', 'email']));
        if ($request->get('password') != '') {
            $user->password = Hash::make($request->string('password'));
        }
        $user->save();

        return response()->json([
            'message' => 'User data updated!',
            'data' => $user,
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if ($user->id == Auth::user()->id) {
            return response()->json([
                'message' => 'Cannot delete current user!'
            ], 409);
        }

        if ($user) {
            $user->delete();
            return response()->noContent();
        }
    }
}
