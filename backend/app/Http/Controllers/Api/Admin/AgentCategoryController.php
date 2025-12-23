<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\AgentCategory;
use Illuminate\Http\Request;

class AgentCategoryController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'agent_id' => ['required', 'exists:users,id'],
            'category' => ['required', 'string'],
        ]);

        $agentCategory = AgentCategory::create($validated);

        return response()->json([
            'message' => 'Category assigned to agent',
            'data' => $agentCategory,
        ], 201);
    }

    public function destroy(AgentCategory $agentCategory)
    {
        $agentCategory->delete();

        return response()->json([
            'message' => 'Category removed from agent',
        ]);
    }
}
