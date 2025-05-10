<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Doctor;
use App\Models\User;

class UpdateDoctorUsersSeeder extends Seeder
{
    public function run()
    {
        // Find the doctor user and doctor record from your previous seeder
        $doctorUser = User::where('email', 'doctor@example.com')->first();
        $doctor = Doctor::where([
            'procedure' => 'General Checkup',
            'diagnosis' => 'General Medicine'
        ])->first();

        if ($doctorUser && $doctor) {
            // Link the doctor to the user
            $doctor->user_id = $doctorUser->id;
            $doctor->save();

            $this->command->info('Doctor successfully linked to user!');
        } else {
            $this->command->error('Doctor or user not found!');
        }

        // Fix any other existing doctors with null user_id but matching emails
        $doctors = Doctor::whereNull('user_id')->get();
        $count = 0;

        foreach ($doctors as $doctor) {
            // Try to find a user with doctor role who isn't already linked to a doctor
            $user = User::where('role', 'doctor')
                ->whereNotIn('id', Doctor::whereNotNull('user_id')->pluck('user_id')->toArray())
                ->first();

            if ($user) {
                $doctor->user_id = $user->id;
                $doctor->save();
                $count++;
            }
        }

        if ($count > 0) {
            $this->command->info("{$count} additional doctors linked to users");
        }
    }
}
