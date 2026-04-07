<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Professional;
use App\Models\ProfessionalLiquidation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ProfessionalLiquidationSeeder extends Seeder
{
    public function run(): void
    {
        $adminUser    = User::whereHas('profile', fn ($q) => $q->where('name', 'Administrador'))->first();
        $professionals = Professional::where('is_active', true)->get();
        $currentMonth = Carbon::today()->month;
        $currentYear  = Carbon::today()->year;

        // Generar liquidaciones para los últimos 5 meses + mes actual
        for ($monthsAgo = 5; $monthsAgo >= 0; $monthsAgo--) {
            $targetDate   = Carbon::today()->subMonths($monthsAgo);
            $year         = (int) $targetDate->year;
            $month        = (int) $targetDate->month;
            $isCurrentMonth = ($year === $currentYear && $month === $currentMonth);

            foreach ($professionals as $prof) {
                $attended = Appointment::where('professional_id', $prof->id)
                    ->where('status', 'attended')
                    ->whereYear('appointment_date', $year)
                    ->whereMonth('appointment_date', $month)
                    ->get();

                if ($attended->isEmpty()) {
                    continue;
                }

                $totalCollected      = (float) $attended->sum('final_amount');
                $commissionRate      = (float) $prof->commission_percentage / 100;
                $professionalAmount  = round($totalCollected * $commissionRate, 2);
                $clinicAmount        = round($totalCollected - $professionalAmount, 2);
                $absentCount         = rand(0, 3);
                $liquidationDate     = Carbon::create($year, $month)->endOfMonth();
                $paidAt              = $isCurrentMonth ? null : $liquidationDate->copy()->addDays(rand(2, 5));

                ProfessionalLiquidation::create([
                    'professional_id'           => $prof->id,
                    'liquidation_date'           => $liquidationDate->toDateString(),
                    'sheet_type'                 => 'liquidation',
                    'appointments_total'         => $attended->count() + $absentCount,
                    'appointments_attended'      => $attended->count(),
                    'appointments_absent'        => $absentCount,
                    'total_collected'            => $totalCollected,
                    'direct_payments_total'      => 0,
                    'professional_commission'    => $prof->commission_percentage,
                    'clinic_amount'              => $clinicAmount,
                    'clinic_amount_from_direct'  => 0,
                    'net_professional_amount'    => $professionalAmount,
                    'payment_status'             => $isCurrentMonth ? 'pending' : 'paid',
                    'payment_method'             => 'transfer',
                    'paid_at'                    => $paidAt,
                    'paid_by'                    => $isCurrentMonth ? null : $adminUser->id,
                    'notes'                      => null,
                ]);
            }
        }
    }
}
