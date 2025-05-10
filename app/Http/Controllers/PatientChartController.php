<?php

namespace App\Http\Controllers;

use App\Models\PatientChart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class PatientChartController extends Controller
{
    public function index()
    {
        try {
            $patients = PatientChart::with('doctor')->get();
            return response()->json($patients);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }



    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'age' => 'required|integer',
            'dob' => 'required|date',
            'gender' => 'required|in:Male,Female',
            'address' => 'required|string|max:255',
            'spouse' => 'nullable|string|max:255',
            'diagnosis' => 'required|string|max:255',
            'procedure' => 'required|string|max:255',
            'history' => 'required|string|max:255',
            'prescription' => 'required|string|max:255',
            'doctor_id' => 'required|exists:doctor,id'
        ]);

        $patient = PatientChart::create($request->all());

        return response()->json([
            'message' => 'Patient created successfully',
            'patient' => $patient->load('doctor')
        ], 201);
    }

   public function show(PatientChart $patient)
{
    Log::info('Patient data requested', [
        'id' => $patient->id,
        'has_data' => $patient->exists,
        'doctor_id' => $patient->doctor_id
    ]);

    return response()->json($patient->load('doctor'));
}

    public function update(Request $request, PatientChart $patient)
{
    $request->validate([
        'first_name' => 'string|max:255',
        'last_name' => 'string|max:255',
        'age' => 'integer',
        'dob' => 'date',
        'gender' => 'in:Male,Female',
        'address' => 'string|max:255',
        'spouse' => 'nullable|string|max:255',
        'diagnosis' => 'string|max:255',
        'procedure' => 'string|max:255',
        'history' => 'string|max:255',
        'prescription' => 'string|max:255',
        'doctor_id' => 'exists:doctor,id'
    ]);

    $patient->update($request->all());

    return response()->json([
        'message' => 'Patient updated successfully',
        'patient' => $patient->load('doctor')
    ]);
}
    public function destroy(PatientChart $patientChart)
    {
        $patientChart->delete();

        return response()->json([
            'message' => 'Patient deleted successfully'
        ]);
    }

public function getPatientsByDoctor()
{
    try {
        // Get the authenticated user
        $user = Auth::user();

        // Check if the user is an admin - admins can see all patients
        if ($user->role === 'admin') {
            $patients = PatientChart::with('doctor')->get();
            return response()->json($patients);
        }

        // If user is a doctor, try to find their doctor profile
        if ($user->role === 'doctor') {
            // Find the doctor record for this user
            $doctor = \App\Models\Doctor::where('user_id', $user->id)->first();

            if (!$doctor) {
                // TEMPORARY FIX: If no doctor record found, return all patients for now
                // You should remove this in production after fixing data
                $patients = PatientChart::with('doctor')->get();
                return response()->json($patients);

                // Original error response:
                // return response()->json([
                //     'error' => 'Doctor profile not found'
                // ], 404);
            }

            // Get all patients assigned to this doctor
            $patients = PatientChart::where('doctor_id', $doctor->id)
                                   ->with('doctor')
                                   ->get();

            return response()->json($patients);
        }

        // If neither admin nor doctor
        return response()->json([
            'error' => 'Unauthorized. Only doctors or admins can access patients.'
        ], 403);

    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
}


}
