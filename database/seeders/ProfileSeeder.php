<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\ProfileModule;
use Illuminate\Database\Seeder;

class ProfileSeeder extends Seeder
{
    public function run(): void
    {
        $allModules = array_keys(Profile::MODULES);

        $generalModules = [
            'professionals',
            'patients',
            'appointments',
            'agenda',
            'cash',
            'payments',
            'clinical',
            'reports',
        ];

        // Módulos del Administrador (nivel 2): todo EXCEPTO system
        $adminModules = array_filter($allModules, fn($m) => $m !== 'system');

        // Perfil Administrador — sin módulo system
        $admin = Profile::firstOrCreate(
            ['name' => 'Administrador'],
            ['description' => 'Acceso completo a todos los módulos del sistema']
        );
        $admin->modules()->delete();
        foreach ($adminModules as $module) {
            ProfileModule::create(['profile_id' => $admin->id, 'module' => $module]);
        }

        // Perfil Acceso General — sin configuración ni sistema
        $general = Profile::firstOrCreate(
            ['name' => 'Acceso General'],
            ['description' => 'Acceso a módulos operativos sin configuración ni sistema']
        );
        $general->modules()->delete();
        foreach ($generalModules as $module) {
            ProfileModule::create(['profile_id' => $general->id, 'module' => $module]);
        }

        // Perfil Profesional — solo portal profesional
        $profesional = Profile::firstOrCreate(
            ['name' => 'Profesional'],
            ['description' => 'Acceso al portal de profesionales del centro']
        );
        $profesional->modules()->delete();
        ProfileModule::create(['profile_id' => $profesional->id, 'module' => 'professional']);

        // Perfil DEV — todos los módulos (incluye system)
        $dev = Profile::firstOrCreate(
            ['name' => 'DEV'],
            ['description' => 'Acceso total al sistema']
        );
        $dev->modules()->delete();
        foreach ($allModules as $module) {
            ProfileModule::create(['profile_id' => $dev->id, 'module' => $module]);
        }
    }
}
