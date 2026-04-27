<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $request->user()->accounts;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string',
            'balance' => 'required|numeric',
            'icon' => 'nullable|string',
            'color' => 'nullable|string',
        ]);

        $account = $request->user()->accounts()->create($validated);
        return response()->json($account, 201);
    }

    public function show(Request $request, string $id)
    {
        return $request->user()->accounts()->findOrFail($id);
    }

    public function update(Request $request, string $id)
    {
        $account = $request->user()->accounts()->findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'type' => 'sometimes|string',
            'balance' => 'sometimes|numeric',
            'icon' => 'nullable|string',
            'color' => 'nullable|string',
        ]);

        $account->update($validated);
        return response()->json($account);
    }

    public function destroy(Request $request, string $id)
    {
        $account = $request->user()->accounts()->findOrFail($id);
        $account->delete();
        return response()->json(null, 204);
    }
}
