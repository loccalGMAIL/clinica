<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\ClinicalRecord;
use App\Models\Patient;
use App\Models\ProfessionalAbsence;
use App\Models\ProfessionalLiquidation;
use App\Models\ProfessionalSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfessionalPortalController extends Controller
{
    /**
     * Dashboard del profesional
     */
    public function dashboard()
    {
        $professional = Auth::user()->professional;

        $today = now()->toDateString();

        $todayAppointments = Appointment::forProfessional($professional->id)
            ->forDate($today)
            ->with('patient', 'office')
            ->orderBy('appointment_date')
            ->get();

        $stats = [
            'today_total'    => $todayAppointments->count(),
            'today_attended' => $todayAppointments->where('status', 'attended')->count(),
            'today_pending'  => $todayAppointments->where('status', 'scheduled')->count(),
            'today_absent'   => $todayAppointments->where('status', 'absent')->count(),
            'patients_total' => Appointment::forProfessional($professional->id)
                                    ->whereNotIn('status', ['cancelled'])
                                    ->distinct('patient_id')
                                    ->count('patient_id'),
            'clinical_total' => ClinicalRecord::forProfessional($professional->id)->count(),
        ];

        // HCs de hoy indexadas por appointment_id
        $appointmentIds = $todayAppointments->pluck('id');
        $todayClinical = ClinicalRecord::forProfessional($professional->id)
            ->whereIn('appointment_id', $appointmentIds)
            ->get()
            ->keyBy('appointment_id');

        return view('professional.dashboard', compact('professional', 'todayAppointments', 'stats', 'todayClinical'));
    }

    /**
     * Mis turnos
     */
    public function appointments(Request $request)
    {
        $professional = Auth::user()->professional;

        $query = Appointment::forProfessional($professional->id)
            ->with('patient', 'office')
            ->orderBy('appointment_date', 'desc');

        if ($request->filled('date_from')) {
            $query->whereDate('appointment_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('appointment_date', '<=', $request->date_to);
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $appointments = $query->paginate(20)->withQueryString();

        if ($request->ajax()) {
            return response()->json([
                'appointments' => $appointments->items(),
                'pagination'   => [
                    'current_page' => $appointments->currentPage(),
                    'last_page'    => $appointments->lastPage(),
                    'total'        => $appointments->total(),
                ],
            ]);
        }

        return view('professional.appointments', compact('professional', 'appointments'));
    }

    /**
     * Mis pacientes
     */
    public function patients(Request $request)
    {
        $professional = Auth::user()->professional;

        $query = Patient::whereHas('appointments', function ($q) use ($professional) {
            $q->where('professional_id', $professional->id)
              ->whereNotIn('status', ['cancelled']);
        })->orderBy('last_name')->orderBy('first_name');

        if ($request->filled('search')) {
            $search = $request->search;
            $clean  = preg_replace('/[.\s]/', '', $search);
            $query->where(function ($q) use ($search, $clean) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('dni', 'like', "%{$search}%")
                  ->orWhereRaw('REPLACE(dni, ".", "") LIKE ?', ["%{$clean}%"]);
            });
        }

        $patients = $query->paginate(20)->withQueryString();

        // Agregar estadísticas por paciente
        $patientIds    = $patients->pluck('id');
        $appointCounts = Appointment::forProfessional($professional->id)
            ->whereIn('patient_id', $patientIds)
            ->whereNotIn('status', ['cancelled'])
            ->selectRaw('patient_id, count(*) as total, max(appointment_date) as last_appointment')
            ->groupBy('patient_id')
            ->get()
            ->keyBy('patient_id');

        if ($request->ajax()) {
            return response()->json([
                'patients'       => $patients->items(),
                'appointCounts'  => $appointCounts,
            ]);
        }

        return view('professional.patients', compact('professional', 'patients', 'appointCounts'));
    }

    /**
     * Detalle de paciente
     */
    public function patientDetail(Patient $patient)
    {
        $professional = Auth::user()->professional;

        // Verificar que el paciente haya tenido turno con este profesional
        $hasRelation = Appointment::forProfessional($professional->id)
            ->forPatient($patient->id)
            ->whereNotIn('status', ['cancelled'])
            ->exists();

        if (! $hasRelation) {
            abort(403, 'No tiene acceso a los datos de este paciente.');
        }

        $appointments = Appointment::forProfessional($professional->id)
            ->forPatient($patient->id)
            ->with('office')
            ->orderBy('appointment_date', 'desc')
            ->get();

        $clinicalRecords = ClinicalRecord::forProfessional($professional->id)
            ->forPatient($patient->id)
            ->ordered()
            ->get();

        return view('professional.patient-detail', compact('professional', 'patient', 'appointments', 'clinicalRecords'));
    }

    /**
     * Mis Historias Clínicas — listado de pacientes
     */
    public function clinical(Request $request)
    {
        $professional = Auth::user()->professional;

        // Redirigir al flujo "Nueva HC" desde el dashboard
        if ($request->get('new') === '1' && $request->filled('patient_id')) {
            $url = route('professional.clinical.patient', $request->patient_id) . '?new=1';
            if ($request->filled('appointment_id')) {
                $url .= '&appointment_id=' . (int) $request->appointment_id;
            }
            return redirect($url);
        }

        $patientIds = ClinicalRecord::forProfessional($professional->id)->distinct()->pluck('patient_id');

        $query = Patient::whereIn('id', $patientIds)->orderBy('last_name')->orderBy('first_name');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(fn ($q) =>
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name',  'like', "%{$search}%")
                  ->orWhere('dni',        'like', "%{$search}%")
            );
        }

        $patients = $query->paginate(20)->withQueryString();

        $hcStats = ClinicalRecord::forProfessional($professional->id)
            ->whereIn('patient_id', $patients->pluck('id'))
            ->selectRaw('patient_id, count(*) as total, max(date) as last_date')
            ->groupBy('patient_id')
            ->get()
            ->keyBy('patient_id');

        return view('professional.clinical', compact('professional', 'patients', 'hcStats'));
    }

    /**
     * HC completa de un paciente
     */
    public function clinicalPatient(Patient $patient)
    {
        $professional = Auth::user()->professional;

        $hasRelation = Appointment::forProfessional($professional->id)
            ->forPatient($patient->id)
            ->whereNotIn('status', ['cancelled'])
            ->exists();

        if (! $hasRelation) {
            abort(403, 'No tiene acceso a los datos de este paciente.');
        }

        $records = ClinicalRecord::forProfessional($professional->id)
            ->forPatient($patient->id)
            ->with('appointment')
            ->ordered()
            ->get();

        return view('professional.clinical-patient', compact('professional', 'patient', 'records'));
    }

    /**
     * Crear Historia Clínica desde el portal
     */
    public function clinicalStore(Request $request)
    {
        $professional = Auth::user()->professional;

        $validated = $request->validate([
            'patient_id'     => ['required', 'exists:patients,id'],
            'appointment_id' => ['nullable', 'exists:appointments,id'],
            'date'           => ['required', 'date'],
            'content'        => ['required', 'string', 'max:5000'],
            'diagnosis'      => ['nullable', 'string', 'max:1000'],
            'treatment'      => ['nullable', 'string', 'max:1000'],
        ]);

        $validated['professional_id'] = $professional->id;
        $validated['created_by']      = Auth::id();

        ClinicalRecord::create($validated);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Historia clínica creada exitosamente.']);
        }

        return back()->with('success', 'Historia clínica creada exitosamente.');
    }

    /**
     * Ver Historia Clínica propia
     */
    public function clinicalShow(ClinicalRecord $record)
    {
        $professional = Auth::user()->professional;

        if ($record->professional_id !== $professional->id) {
            abort(403, 'No tiene acceso a esta historia clínica.');
        }

        $record->load('patient', 'appointment');

        if (request()->ajax()) {
            return response()->json(['record' => $record]);
        }

        return view('professional.clinical-show', compact('record', 'professional'));
    }

    /**
     * Mi Horario
     */
    public function schedule()
    {
        $professional = Auth::user()->professional;

        $schedules = ProfessionalSchedule::where('professional_id', $professional->id)
            ->active()
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get();

        $setting = null;

        $days = [
            1 => 'Lunes', 2 => 'Martes', 3 => 'Miércoles',
            4 => 'Jueves', 5 => 'Viernes', 6 => 'Sábado', 7 => 'Domingo',
        ];

        $scheduleByDay = [];
        foreach ($days as $num => $name) {
            $scheduleByDay[$num] = [
                'name'  => $name,
                'slots' => $schedules->where('day_of_week', $num)->values(),
            ];
        }

        return view('professional.schedule', compact('professional', 'scheduleByDay', 'setting', 'days'));
    }

    /**
     * Mis Liquidaciones
     */
    public function liquidations(Request $request)
    {
        $professional = Auth::user()->professional;

        $query = ProfessionalLiquidation::where('professional_id', $professional->id)
            ->orderBy('liquidation_date', 'desc');

        if ($request->filled('date_from')) {
            $query->whereDate('liquidation_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('liquidation_date', '<=', $request->date_to);
        }

        $liquidations = $query->paginate(15)->withQueryString();

        $totals = [
            'total_collected'       => $query->sum('total_collected'),
            'professional_commission' => $query->sum('professional_commission'),
            'net_professional_amount' => $query->sum('net_professional_amount'),
        ];

        return view('professional.liquidations', compact('professional', 'liquidations', 'totals'));
    }

    /**
     * Mis Ausencias
     */
    public function absences(Request $request)
    {
        $professional = Auth::user()->professional;

        $query = ProfessionalAbsence::where('professional_id', $professional->id)
            ->orderBy('absence_date', 'desc');

        if ($request->filled('year')) {
            $query->whereYear('absence_date', $request->year);
        }

        if ($request->filled('month')) {
            $query->whereMonth('absence_date', $request->month);
        }

        $absences = $query->paginate(20)->withQueryString();

        return view('professional.absences', compact('professional', 'absences'));
    }
}
