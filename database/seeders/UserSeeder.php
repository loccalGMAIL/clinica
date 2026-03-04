<?php

namespace Database\Seeders;

use App\Models\Professional;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminProfile      = Profile::where('name', 'Administrador')->first();
        $generalProfile    = Profile::where('name', 'Acceso General')->first();
        $professionalProfile = Profile::where('name', 'Profesional')->first();

        // Usuario administrador
        User::create([
            'name'       => 'Administrador',
            'email'      => 'admin@clinicademo.com',
            'password'   => Hash::make('password123'),
            'profile_id' => $adminProfile?->id,
            'is_active'  => true,
        ]);

        // Usuario recepcionista
        User::create([
            'name'       => 'Recepcionista',
            'email'      => 'recepcion@clinicademo.com',
            'password'   => Hash::make('password123'),
            'profile_id' => $generalProfile?->id,
            'is_active'  => true,
        ]);

        // Usuario portal profesional — Dr. Juan Pérez García
        $professional = Professional::where('dni', '20.123.456')->first();
        User::create([
            'name'            => 'Juan Pérez',
            'email'           => 'perez@clinicademo.com',
            'password'        => Hash::make('password123'),
            'profile_id'      => $professionalProfile?->id,
            'professional_id' => $professional?->id,
            'is_active'       => true,
        ]);
    }
}
