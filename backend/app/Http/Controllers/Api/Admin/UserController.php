<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        $users = User::query()
            ->select('id', 'name', 'email', 'role')
            ->with('agentCategories:id,name')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($users);
    }

    public function updateAgentCategories(int $id): JsonResponse
    {
        $user = User::findOrFail($id);

        if ($user->role !== 'agent') {
            return response()->json([
                'message' => 'Only agent users can have categories'
            ], 422);
        }

        request()->validate([
            'category_ids' => 'array',
            'category_ids.*' => 'exists:categories,id'
        ]);

        $user->agentCategories()->sync(request('category_ids'));

        return response()->json([
            'message' => 'Agent categories updated successfully'
        ]);
    }
}
