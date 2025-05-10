<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        $admins = Admin::with('user')->get();
        return response()->json($admins);
    }

    public function store(Request $request)
    {
        $request->validate([
            'staff' => 'required|string|max:255',
            'users_id' => 'required|exists:users,id'
        ]);

        $admin = Admin::create($request->all());

        return response()->json([
            'message' => 'Admin created successfully',
            'admin' => $admin
        ], 201);
    }

    public function show(Admin $admin)
    {
        return response()->json($admin->load('user'));
    }

    public function update(Request $request, Admin $admin)
    {
        $request->validate([
            'staff' => 'string|max:255',
            'users_id' => 'exists:users,id'
        ]);

        $admin->update($request->all());

        return response()->json([
            'message' => 'Admin updated successfully',
            'admin' => $admin
        ]);
    }

    public function destroy(Admin $admin)
    {
        $admin->delete();

        return response()->json([
            'message' => 'Admin deleted successfully'
        ]);
    }
}
