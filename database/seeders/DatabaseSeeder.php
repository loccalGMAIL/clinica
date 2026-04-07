<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            // 1. Configuración del centro
            SettingSeeder::class,

            // 2. Perfiles de acceso
            ProfileSeeder::class,

            // 3. Catálogos base (sin dependencias entre sí)
            SpecialtySeeder::class,
            OfficeSeeder::class,
            MovementTypeSeeder::class,

            // 4. Profesionales (requiere specialties)
            ProfessionalSeeder::class,

            // 5. Usuarios (requiere profiles y professionals)
            UserSeeder::class,

            // 6. Pacientes
            PatientsSeeder::class,

            // 7. Horarios y configuración de turnos (requiere professionals y offices)
            ProfessionalScheduleSeeder::class,
            AppointmentSettingSeeder::class,

            // 8. Turnos (requiere professionals, patients, offices, schedules)
            AppointmentSeeder::class,

            // 9. Pagos y caja (requieren appointments)
            PaymentSeeder::class,
            CashMovementSeeder::class,

            // 10. Historias clínicas (requieren appointments con status=attended)
            ClinicalRecordSeeder::class,

            // 11. Excepciones de horario
            ScheduleExceptionSeeder::class,
        ]);
    }
}
