<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AgentCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserManagementController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required|string|min:8',
            'role'       => 'required|in:admin,agent',
            'categories' => 'nullable|array',
            'categories.*' => 'string|in:shipping,payment,account,product,return_refund',
        ]);

        return DB::transaction(function () use ($validated) {

            $user = User::create([
                'name'     => $validated['name'],
                'email'    => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role'     => $validated['role'],
            ]);

            if ($validated['role'] === 'agent' && !empty($validated['categories'])) {
                foreach ($validated['categories'] as $category) {
                    AgentCategory::create([
                        'agent_id' => $user->id,
                        'category' => $category,
                    ]);
                }
            }

            return response()->json([
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => $user->role,
            ], 201);
        });
    }

    public function updateAgentCategories(Request $request, User $user)
    {
        if ($user->role !== 'agent') {
            return response()->json([
                'message' => 'User is not an agent'
            ], 422);
        }

        $validated = $request->validate([
            'categories' => 'required|array',
            'categories.*' => 'string|in:shipping,payment,account,product,return_refund',
        ]);

        return DB::transaction(function () use ($user, $validated) {

            AgentCategory::where('agent_id', $user->id)->delete();

            foreach ($validated['categories'] as $category) {
                AgentCategory::create([
                    'agent_id' => $user->id,
                    'category' => $category,
                ]);
            }

            return response()->json([
                'message' => 'Agent categories updated successfully',
            ]);
        });
    }
}
