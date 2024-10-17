<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;

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


    public function create(Request $request)
    {
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
