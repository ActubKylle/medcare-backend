<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index()
    {
        $doctors = Doctor::with(['admin.user', 'user'])->get();
        return response()->json($doctors);
    }

    public function store(Request $request)
    {
        $request->validate([
            'procedure' => 'required|string|max:255',
            'history' => 'required|string|max:255',
            'diagnosis' => 'required|string|max:255',
            'admin_id' => 'required|exists:admin,id',
            'user.name' => 'sometimes|required|string|max:255',
            'user.email' => 'sometimes|required|string|email|max:255|unique:users,email',
            'user.password' => 'sometimes|required|string|min:8',
            'user.role' => 'sometimes|required|in:doctor'
        ]);

        $userId = null;
        if ($request->has('user')) {
            $userData = $request->input('user');

            $user = \App\Models\User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => \Illuminate\Support\Facades\Hash::make($userData['password']),
                'role' => 'doctor', // Ensure role is doctor
            ]);

            $userId = $user->id;
        }

        $doctor = Doctor::create([
            'procedure' => $request->procedure,
            'history' => $request->history,
            'diagnosis' => $request->diagnosis,
            'admin_id' => $request->admin_id,
            'user_id' => $userId
        ]);

        return response()->json([
            'message' => 'Doctor created successfully',
            'doctor' => $doctor->load('admin.user')
        ], 201);
    }
    public function show(Doctor $doctor)
    {
        return response()->json($doctor->load('admin.user'));
    }

    public function update(Request $request, Doctor $doctor)
    {
        $request->validate([
            'procedure' => 'string|max:255',
            'history' => 'string|max:255',
            'diagnosis' => 'string|max:255',
            'admin_id' => 'exists:admin,id'
        ]);

        $doctor->update($request->all());

        return response()->json([
            'message' => 'Doctor updated successfully',
            'doctor' => $doctor
        ]);
    }

    public function destroy(Doctor $doctor)
    {
        $doctor->delete();

        return response()->json([
            'message' => 'Doctor deleted successfully'
        ]);
    }
}
