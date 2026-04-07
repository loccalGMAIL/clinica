<?php

namespace Database\Seeders;

use App\Models\MovementType;
use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CashMovementSeeder extends Seeder
{
    public function run(): void
    {
        $adminUser = User::whereHas('profile', fn ($q) => $q->where('name', 'Administrador'))->first();

        // Obtener IDs de tipos de movimiento por código
        $types = MovementType::whereIn('code', [
            'patient_payment', 'professional_payment', 'expense', 'refund', 'other',
        ])->pluck('id', 'code');

        $movements   = [];
        $balance     = 0;
        $startDate   = Carbon::today()->subDays(30);

        // Balance inicial de caja
        $balance += 50000;
        $movements[] = $this->row(
            $types['other'],
            50000,
            'Balance inicial de caja',
            $balance,
            $adminUser->id,
            $startDate->copy()->setTime(8, 0, 0)
        );

        // Movimientos por pagos de pacientes confirmados
        $payments = Payment::where('status', 'confirmed')
            ->where('payment_type', '!=', 'refund')
            ->where('total_amount', '>', 0)
            ->get();

        foreach ($payments as $payment) {
            $typeId   = $types['patient_payment'];
            $balance += $payment->total_amount;
            $movements[] = $this->row(
                $typeId,
                $payment->total_amount,
                "Pago de paciente - {$payment->concept}",
                $balance,
                $adminUser->id,
                $payment->payment_date,
                'App\\Models\\Payment',
                $payment->id
            );
        }

        // Gastos operativos
        $expenses = [
            ['desc' => 'Pago de alquiler',              'amount' => -25000],
            ['desc' => 'Servicios públicos',             'amount' => -8500],
            ['desc' => 'Compra de insumos médicos',      'amount' => -12000],
            ['desc' => 'Mantenimiento de equipos',       'amount' => -5500],
            ['desc' => 'Material de oficina',            'amount' => -2800],
            ['desc' => 'Limpieza del consultorio',       'amount' => -3200],
            ['desc' => 'Internet y telefonía',           'amount' => -4500],
            ['desc' => 'Seguros',                        'amount' => -7800],
            ['desc' => 'Combustible',                    'amount' => -3000],
            ['desc' => 'Publicidad',                     'amount' => -4200],
        ];

        foreach ($expenses as $expense) {
            $date     = $startDate->copy()->addDays(rand(1, 25))->setTime(rand(10, 16), rand(0, 59));
            $balance += $expense['amount'];
            $movements[] = $this->row(
                $types['expense'],
                $expense['amount'],
                $expense['desc'],
                $balance,
                $adminUser->id,
                $date
            );
        }

        // Pagos a profesionales
        $professionalPayments = [
            ['desc' => 'Liquidación comisión Dr. Juan Pérez',     'amount' => -8500],
            ['desc' => 'Liquidación comisión Dra. María González', 'amount' => -12000],
            ['desc' => 'Liquidación comisión Dr. Carlos Martínez', 'amount' => -9200],
            ['desc' => 'Liquidación comisión Dra. Ana Rodríguez',  'amount' => -10800],
        ];

        foreach ($professionalPayments as $pp) {
            $date     = $startDate->copy()->addDays(rand(5, 28))->setTime(rand(14, 17), rand(0, 59));
            $balance += $pp['amount'];
            $movements[] = $this->row(
                $types['professional_payment'],
                $pp['amount'],
                $pp['desc'],
                $balance,
                $adminUser->id,
                $date
            );
        }

        // Reembolsos
        $refunds = Payment::where('payment_type', 'refund')->where('total_amount', '<', 0)->get();
        foreach ($refunds as $refund) {
            $balance += $refund->total_amount;
            $movements[] = $this->row(
                $types['refund'],
                $refund->total_amount,
                "Reembolso a paciente - {$refund->concept}",
                $balance,
                $adminUser->id,
                $refund->payment_date,
                'App\\Models\\Payment',
                $refund->id
            );
        }

        // Ordenar por fecha y recalcular balance en orden cronológico
        usort($movements, fn ($a, $b) => strcmp($a['created_at'], $b['created_at']));

        $runningBalance = 0;
        foreach ($movements as &$m) {
            $runningBalance += $m['amount'];
            $m['balance_after'] = $runningBalance;
        }
        unset($m);

        DB::table('cash_movements')->insert($movements);
    }

    private function row(
        int $typeId,
        float $amount,
        string $description,
        float $balanceAfter,
        int $userId,
        Carbon $date,
        ?string $referenceType = null,
        ?int $referenceId = null
    ): array {
        return [
            'movement_type_id' => $typeId,
            'amount'           => $amount,
            'description'      => $description,
            'reference_type'   => $referenceType,
            'reference_id'     => $referenceId,
            'balance_after'    => $balanceAfter,
            'user_id'          => $userId,
            'created_at'       => $date->toDateTimeString(),
            'updated_at'       => $date->toDateTimeString(),
        ];
    }
}
