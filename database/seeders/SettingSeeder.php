<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = [
            ['key' => 'center_name',     'value' => 'Centro de Atención Médica'],
            ['key' => 'center_subtitle', 'value' => 'Sistema de Gestión Médica'],
            ['key' => 'center_address',  'value' => 'Tucumán 925, Cosquín'],
            ['key' => 'center_phone',    'value' => '(3541) 705-281'],
            ['key' => 'center_email',    'value' => 'contacto@ejemplo.com'],
        ];

        foreach ($defaults as $data) {
            Setting::firstOrCreate(
                ['key' => $data['key']],
                ['group' => 'center', 'value' => $data['value']]
            );
        }
    }
}
