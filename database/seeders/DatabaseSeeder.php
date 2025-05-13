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
        // Create multiple admin users
        $adminUsers = [
            [
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'staff' => 'Administrative Staff'
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane.smith@example.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'staff' => 'Senior Medical Administrator'
            ],
            [
                'name' => 'Robert Johnson',
                'email' => 'robert.johnson@example.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'staff' => 'Financial Administrator'
            ],
            [
                'name' => 'Maria Garcia',
                'email' => 'maria.garcia@example.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'staff' => 'Operations Manager'
            ],
        ];

        // Create the admin users and their admin records
        foreach ($adminUsers as $adminData) {
            $staffRole = $adminData['staff'];
            unset($adminData['staff']);

            $user = User::create($adminData);
            Admin::create([
                'staff' => $staffRole,
                'users_id' => $user->id,
            ]);
        }

        // Create doctor user
        $doctorUser = User::create([
            'name' => 'Doctor User',
            'email' => 'doctor@example.com',
            'password' => Hash::make('password'),
            'role' => 'doctor',
        ]);

        // Create doctor record with proper user_id
        $doctor = Doctor::create([
            'user_id' => $doctorUser->id,  // Link doctor to user directly
            'procedure' => 'General Checkup',
            'history' => 'Experienced physician',
            'diagnosis' => 'General Medicine',
            'admin_id' => Admin::first()->id,  // Assign first admin as supervisor
        ]);

        // Create more doctors
        $moreDoctors = [
            [
                'name' => 'Dr. Sarah Chen',
                'email' => 'sarah.chen@example.com',
                'procedure' => 'Cardiology',
                'history' => 'Specialized in heart surgery for 10 years',
                'diagnosis' => 'Heart Disease',
                'admin_id' => Admin::skip(1)->first()->id  // Second admin
            ],
            [
                'name' => 'Dr. James Wilson',
                'email' => 'james.wilson@example.com',
                'procedure' => 'Neurology',
                'history' => 'Renowned neurologist with extensive research background',
                'diagnosis' => 'Neurological Disorders',
                'admin_id' => Admin::skip(2)->first()->id  // Third admin
            ],
            [
                'name' => 'Dr. Emily Rodriguez',
                'email' => 'emily.rodriguez@example.com',
                'procedure' => 'Pediatrics',
                'history' => 'Child specialist with 8 years experience',
                'diagnosis' => 'Child Health',
                'admin_id' => Admin::skip(3)->first()->id  // Fourth admin
            ],
        ];

        foreach ($moreDoctors as $doctorData) {
            $newUser = User::create([
                'name' => $doctorData['name'],
                'email' => $doctorData['email'],
                'password' => Hash::make('password'),
                'role' => 'doctor',
            ]);

            Doctor::create([
                'user_id' => $newUser->id,
                'procedure' => $doctorData['procedure'],
                'history' => $doctorData['history'],
                'diagnosis' => $doctorData['diagnosis'],
                'admin_id' => $doctorData['admin_id'],
            ]);
        }

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

        // Create more patients assigned to different doctors
        $morePatients = [
            [
                'first_name' => 'Robert',
                'last_name' => 'Brown',
                'age' => 42,
                'dob' => '1983-07-12',
                'gender' => 'Male',
                'address' => '789 Pine St, Village',
                'spouse' => 'Susan Brown',
                'diagnosis' => 'Arthritis',
                'procedure' => 'Physical Therapy',
                'history' => 'Joint pain for the last 5 years',
                'prescription' => 'Ibuprofen 400mg',
                'doctor_id' => Doctor::skip(1)->first()->id // Dr. Sarah Chen
            ],
            [
                'first_name' => 'Patricia',
                'last_name' => 'Garcia',
                'age' => 32,
                'dob' => '1992-11-25',
                'gender' => 'Female',
                'address' => '101 Cedar St, Suburb',
                'spouse' => '',
                'diagnosis' => 'Migraine',
                'procedure' => 'Medication and Lifestyle Changes',
                'history' => 'Recurring headaches since teenage years',
                'prescription' => 'Sumatriptan 50mg',
                'doctor_id' => Doctor::skip(2)->first()->id // Dr. James Wilson
            ],
            [
                'first_name' => 'Michael',
                'last_name' => 'Wong',
                'age' => 7,
                'dob' => '2017-03-05',
                'gender' => 'Male',
                'address' => '222 Maple St, Town',
                'spouse' => '',
                'diagnosis' => 'Asthma',
                'procedure' => 'Inhaler Therapy',
                'history' => 'Seasonal allergies and breathing difficulties',
                'prescription' => 'Albuterol inhaler',
                'doctor_id' => Doctor::skip(3)->first()->id // Dr. Emily Rodriguez
            ]
        ];

        foreach ($morePatients as $patientData) {
            PatientChart::create($patientData);
        }

        // You can add billing records here if needed
    }
}
