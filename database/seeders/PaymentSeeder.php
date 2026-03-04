<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Patient;
use App\Models\PatientPackage;
use App\Models\Payment;
use App\Models\PaymentAppointment;
use App\Models\PaymentDetail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
{
    private array $methods = ['cash', 'transfer', 'debit_card', 'credit_card'];

    public function run(): void
    {
        $patients  = Patient::all();
        $adminUser = User::whereHas('profile', fn ($q) => $q->where('name', 'Administrador'))->first();

        $this->createSinglePayments($patients, $adminUser);
        $this->createPackagePayments($patients, $adminUser);
        $this->createRefundPayments($patients, $adminUser);
        $this->associatePaymentsWithAppointments();
    }

    private function createSinglePayments($patients, $adminUser): void
    {
        for ($i = 0; $i < 20; $i++) {
            $patient     = $patients->random();
            $amount      = rand(8000, 25000);
            $paymentDate = Carbon::today()->subDays(rand(0, 30))->setTime(rand(8, 18), 0);

            $payment = Payment::create([
                'patient_id'         => $patient->id,
                'payment_type'       => 'single',
                'payment_date'       => $paymentDate,
                'total_amount'       => $amount,
                'is_advance_payment' => false,
                'concept'            => 'Consulta médica',
                'status'             => 'confirmed',
                'liquidation_status' => 'pending',
                'created_by'         => $adminUser->id,
            ]);

            PaymentDetail::create([
                'payment_id'     => $payment->id,
                'payment_method' => $this->methods[rand(0, count($this->methods) - 1)],
                'amount'         => $amount,
                'received_by'    => 'centro',
            ]);
        }
    }

    private function createPackagePayments($patients, $adminUser): void
    {
        for ($i = 0; $i < 8; $i++) {
            $patient      = $patients->random();
            $sessions     = [4, 6, 8, 10][rand(0, 3)];
            $sessionsUsed = rand(0, min($sessions, 4));
            $pricePerSession = rand(8000, 12000);
            $amount       = $sessions * $pricePerSession;
            $paymentDate  = Carbon::today()->subDays(rand(0, 60))->setTime(rand(8, 18), 0);

            $payment = Payment::create([
                'patient_id'         => $patient->id,
                'payment_type'       => 'package_purchase',
                'payment_date'       => $paymentDate,
                'total_amount'       => $amount,
                'is_advance_payment' => false,
                'concept'            => "Paquete de {$sessions} sesiones",
                'status'             => 'confirmed',
                'liquidation_status' => 'not_applicable',
                'created_by'         => $adminUser->id,
            ]);

            PaymentDetail::create([
                'payment_id'     => $payment->id,
                'payment_method' => $this->methods[rand(0, count($this->methods) - 1)],
                'amount'         => $amount,
                'received_by'    => 'centro',
            ]);

            PatientPackage::create([
                'patient_id'        => $patient->id,
                'package_id'        => null,
                'payment_id'        => $payment->id,
                'sessions_included' => $sessions,
                'sessions_used'     => $sessionsUsed,
                'price_paid'        => $amount,
                'purchase_date'     => $paymentDate->toDateString(),
                'expires_at'        => $paymentDate->copy()->addMonths(3)->toDateString(),
                'status'            => $sessionsUsed >= $sessions ? 'completed' : 'active',
            ]);
        }
    }

    private function createRefundPayments($patients, $adminUser): void
    {
        for ($i = 0; $i < 3; $i++) {
            $patient     = $patients->random();
            $amount      = rand(5000, 15000);
            $paymentDate = Carbon::today()->subDays(rand(0, 15))->setTime(rand(9, 17), 0);

            $payment = Payment::create([
                'patient_id'         => $patient->id,
                'payment_type'       => 'refund',
                'payment_date'       => $paymentDate,
                'total_amount'       => -$amount,
                'is_advance_payment' => false,
                'concept'            => 'Reembolso por cancelación',
                'status'             => 'confirmed',
                'liquidation_status' => 'not_applicable',
                'created_by'         => $adminUser->id,
            ]);

            PaymentDetail::create([
                'payment_id'     => $payment->id,
                'payment_method' => ['cash', 'transfer'][rand(0, 1)],
                'amount'         => -$amount,
                'received_by'    => 'centro',
            ]);
        }
    }

    private function associatePaymentsWithAppointments(): void
    {
        $attendedAppointments = Appointment::where('status', 'attended')
            ->whereDoesntHave('paymentAppointments')
            ->with('patient')
            ->get();

        foreach ($attendedAppointments as $appointment) {
            $availablePayment = Payment::where('patient_id', $appointment->patient_id)
                ->where('payment_type', 'single')
                ->where('status', 'confirmed')
                ->whereDoesntHave('paymentAppointments')
                ->first();

            if ($availablePayment) {
                PaymentAppointment::create([
                    'payment_id'           => $availablePayment->id,
                    'appointment_id'       => $appointment->id,
                    'professional_id'      => $appointment->professional_id,
                    'allocated_amount'     => $appointment->final_amount ?? $availablePayment->total_amount,
                    'is_liquidation_trigger' => false,
                ]);
            }
        }
    }
}
