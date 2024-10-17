<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function index(Request $request) {
        return inertia('user/UserListPage');
    }

    public function data(Request $request)
    {
        $orderBy = $request->get('order_by', 'name');
        $orderType = $request->get('order_type', 'asc');

        $q = User::query();
        $q->orderBy($orderBy, $orderType);

        $users = $q->paginate($request->get('per_page', 10))->withQueryString();

        return response()->json($users);

    }
}
