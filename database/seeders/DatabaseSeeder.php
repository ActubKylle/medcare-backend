<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Admin;
use App\Models\Doctor;
use App\Models\PatientChart;
use App\Models\Billing;
use Illuminate\Support\Facades\Hash;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        $adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create admin record
        $admin = Admin::create([
            'staff' => 'Administrative Staff',
            'users_id' => $adminUser->id,
        ]);

        // Create doctor user
        $doctorUser = User::create([
            'name' => 'Doctor User',
            'email' => 'doctor@example.com',
            'password' => Hash::make('password'),
            'role' => 'doctor',
        ]);

        // Create doctor record
        $doctor = Doctor::create([
            'procedure' => 'General Checkup',
            'history' => 'Experienced physician',
            'diagnosis' => 'General Medicine',
            'admin_id' => $admin->id,
        ]);

        // Create some sample patients
        $patient1 = PatientChart::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'age' => 35,
            'dob' => '1990-05-15',
            'gender' => 'Male',
            'address' => '123 Main St, City',
            'spouse' => 'Jane Doe',
            'diagnosis' => 'Common Cold',
            'procedure' => 'Medication',
            'history' => 'No significant medical history',
            'prescription' => 'Paracetamol 500mg',
            'doctor_id' => $doctor->id,
        ]);

        $patient2 = PatientChart::create([
            'first_name' => 'Mary',
            'last_name' => 'Smith',
            'age' => 28,
            'dob' => '1997-09-20',
            'gender' => 'Female',
            'address' => '456 Oak St, Town',
            'spouse' => '',
            'diagnosis' => 'Hypertension',
            'procedure' => 'Medication and Diet Plan',
            'history' => 'Family history of high blood pressure',
            'prescription' => 'Amlodipine 5mg',
            'doctor_id' => $doctor->id,
        ]);

        // Create billing records - FIXED to match migration schema
    }
}
