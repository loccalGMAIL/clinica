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

        $types = MovementType::whereIn('code', [
            'patient_payment', 'professional_payment', 'expense', 'refund', 'other',
            'cash_opening', 'cash_closing',
            'office_supplies', 'medical_supplies', 'services', 'maintenance',
            'taxes', 'other_expense', 'professional_liquidation', 'expense_payment',
        ])->pluck('id', 'code');

        $movements = [];
        $balance   = 0;

        // ── Balance inicial (hace 6 meses) ──────────────────────────────────────
        $startDate = Carbon::today()->subDays(180);
        $balance += 50000;
        $movements[] = $this->row(
            $types['other'], 50000, 'Balance inicial de caja',
            $balance, $adminUser->id, $startDate->copy()->setTime(8, 0, 0)
        );

        // ── Aperturas y cierres de caja (últimos 30 días laborables) ────────────
        for ($i = 30; $i >= 1; $i--) {
            $date = Carbon::today()->subDays($i);
            if ($date->dayOfWeek >= 1 && $date->dayOfWeek <= 6) { // lun-sab
                $movements[] = $this->row(
                    $types['cash_opening'], 0, 'Apertura de caja',
                    $balance, $adminUser->id, $date->copy()->setTime(8, 0, 0)
                );
                $movements[] = $this->row(
                    $types['cash_closing'], 0, 'Cierre de caja',
                    $balance, $adminUser->id, $date->copy()->setTime(20, 0, 0)
                );
            }
        }

        // ── Pagos de pacientes (todos los confirmados) ───────────────────────────
        $payments = Payment::where('status', 'confirmed')
            ->where('payment_type', '!=', 'refund')
            ->where('total_amount', '>', 0)
            ->get();

        foreach ($payments as $payment) {
            $balance += $payment->total_amount;
            $movements[] = $this->row(
                $types['patient_payment'], $payment->total_amount,
                "Pago de paciente - {$payment->concept}",
                $balance, $adminUser->id, Carbon::parse($payment->payment_date),
                'App\\Models\\Payment', $payment->id
            );
        }

        // ── Gastos operativos mensuales (últimos 6 meses) ────────────────────────
        // Cada entrada: [código de tipo, descripción, monto, día del mes]
        $monthlyExpenses = [
            ['type' => 'other_expense',    'desc' => 'Pago de alquiler',         'amount' => -25000, 'day' => 1],
            ['type' => 'services',         'desc' => 'Servicios públicos',        'amount' => -8500,  'day' => 5],
            ['type' => 'medical_supplies', 'desc' => 'Compra de insumos médicos', 'amount' => -12000, 'day' => 8],
            ['type' => 'maintenance',      'desc' => 'Mantenimiento de equipos',  'amount' => -5500,  'day' => 10],
            ['type' => 'office_supplies',  'desc' => 'Material de oficina',       'amount' => -2800,  'day' => 12],
            ['type' => 'services',         'desc' => 'Limpieza del consultorio',  'amount' => -3200,  'day' => 15],
            ['type' => 'services',         'desc' => 'Internet y telefonía',      'amount' => -4500,  'day' => 18],
            ['type' => 'taxes',            'desc' => 'Seguros',                   'amount' => -7800,  'day' => 20],
        ];

        for ($monthsAgo = 5; $monthsAgo >= 0; $monthsAgo--) {
            $monthBase = Carbon::today()->subMonths($monthsAgo)->startOfMonth();
            foreach ($monthlyExpenses as $expense) {
                $date = $monthBase->copy()->addDays($expense['day'] - 1)->setTime(rand(10, 16), rand(0, 59));
                $balance += $expense['amount'];
                $movements[] = $this->row(
                    $types[$expense['type']], $expense['amount'], $expense['desc'],
                    $balance, $adminUser->id, $date
                );
            }
        }

        // ── Gastos adicionales esporádicos ───────────────────────────────────────
        $extraExpenses = [
            ['type' => 'other_expense', 'desc' => 'Combustible',  'amount' => -3000],
            ['type' => 'other_expense', 'desc' => 'Publicidad',   'amount' => -4200],
            ['type' => 'other_expense', 'desc' => 'Combustible',  'amount' => -3500],
            ['type' => 'other_expense', 'desc' => 'Publicidad',   'amount' => -5000],
            ['type' => 'other_expense', 'desc' => 'Combustible',  'amount' => -2800],
            ['type' => 'maintenance',   'desc' => 'Reparaciones', 'amount' => -8000],
        ];

        foreach ($extraExpenses as $expense) {
            $date = Carbon::today()->subDays(rand(1, 150))->setTime(rand(10, 16), rand(0, 59));
            $balance += $expense['amount'];
            $movements[] = $this->row(
                $types[$expense['type']], $expense['amount'], $expense['desc'],
                $balance, $adminUser->id, $date
            );
        }

        // ── Liquidaciones a profesionales (una por mes por profesional) ──────────
        $professionalPayments = [
            ['desc' => 'Liquidación Dr. Juan Pérez',      'amount' => -8500],
            ['desc' => 'Liquidación Dra. María González', 'amount' => -12000],
            ['desc' => 'Liquidación Dr. Carlos Martínez', 'amount' => -9200],
            ['desc' => 'Liquidación Dra. Ana Rodríguez',  'amount' => -10800],
        ];

        for ($monthsAgo = 5; $monthsAgo >= 1; $monthsAgo--) {
            $monthEnd = Carbon::today()->subMonths($monthsAgo)->endOfMonth();
            foreach ($professionalPayments as $pp) {
                $date = $monthEnd->copy()->addDays(rand(1, 5))->setTime(rand(14, 17), rand(0, 59));
                $balance += $pp['amount'];
                $movements[] = $this->row(
                    $types['professional_liquidation'], $pp['amount'], $pp['desc'],
                    $balance, $adminUser->id, $date
                );
            }
        }

        // ── Reembolsos ───────────────────────────────────────────────────────────
        $refunds = Payment::where('payment_type', 'refund')->where('total_amount', '<', 0)->get();
        foreach ($refunds as $refund) {
            $balance += $refund->total_amount;
            $movements[] = $this->row(
                $types['refund'], $refund->total_amount,
                "Reembolso a paciente - {$refund->concept}",
                $balance, $adminUser->id, Carbon::parse($refund->payment_date),
                'App\\Models\\Payment', $refund->id
            );
        }

        // ── Ordenar cronológicamente y recalcular balance ────────────────────────
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
