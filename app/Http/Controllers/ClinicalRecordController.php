<?php

namespace App\Http\Controllers;

use App\Models\ClinicalRecord;
use App\Models\Patient;
use App\Models\Professional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ClinicalRecordController extends Controller
{
    public function index(Request $request)
    {
        $professionals = Professional::active()->orderBy('last_name')->orderBy('first_name')->get();

        // Obtener IDs de pacientes que tienen registros coincidentes con los filtros
        $patientIdQuery = ClinicalRecord::distinct()->select('patient_id');

        if ($request->filled('professional_id')) {
            $patientIdQuery->where('professional_id', $request->get('professional_id'));
        }
        if ($request->filled('date_from')) {
            $patientIdQuery->where('date', '>=', $request->get('date_from'));
        }
        if ($request->filled('date_to')) {
            $patientIdQuery->where('date', '<=', $request->get('date_to'));
        }

        $patientQuery = Patient::whereIn('id', $patientIdQuery)
            ->orderBy('last_name')
            ->orderBy('first_name');

        if ($request->filled('search')) {
            $search = $request->get('search');
            $patientQuery->where(fn ($q) =>
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name',  'like', "%{$search}%")
                  ->orWhere('dni',        'like', "%{$search}%")
            );
        }

        $patients = $patientQuery->paginate(10)->withQueryString();

        // Cargar registros de los pacientes en esta página
        $recordsByPatient = ClinicalRecord::with(['professional', 'appointment', 'creator'])
            ->whereIn('patient_id', $patients->pluck('id'))
            ->when($request->filled('professional_id'), fn ($q) => $q->where('professional_id', $request->get('professional_id')))
            ->when($request->filled('date_from'), fn ($q) => $q->where('date', '>=', $request->get('date_from')))
            ->when($request->filled('date_to'),   fn ($q) => $q->where('date', '<=', $request->get('date_to')))
            ->ordered()
            ->get()
            ->groupBy('patient_id');

        $patientGroups = collect($patients->items())->map(fn ($p) => [
            'patient' => $p,
            'records' => $recordsByPatient->get($p->id, collect())->values(),
        ]);

        if ($request->ajax()) {
            return response()->json([
                'patientGroups' => $patientGroups,
                'pagination'    => [
                    'current_page' => $patients->currentPage(),
                    'last_page'    => $patients->lastPage(),
                    'per_page'     => $patients->perPage(),
                    'total'        => $patients->total(),
                ],
            ]);
        }

        return view('clinical.index', compact('patientGroups', 'patients', 'professionals'));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'patient_id'      => 'required|exists:patients,id',
                'professional_id' => 'required|exists:professionals,id',
                'appointment_id'  => 'nullable|exists:appointments,id',
                'date'            => 'required|date',
                'content'         => 'nullable|string',
                'diagnosis'       => 'nullable|string',
                'treatment'       => 'nullable|string',
            ]);

            $validated['created_by'] = Auth::id();

            $record = ClinicalRecord::create($validated);
            $record->load(['patient', 'professional', 'creator']);

            return response()->json([
                'success' => true,
                'message' => 'Registro clínico creado exitosamente.',
                'record'  => $record,
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación.',
                'errors'  => $e->errors(),
            ], 422);
        }
    }

    public function show(ClinicalRecord $record)
    {
        $record->load(['patient', 'professional', 'creator', 'appointment']);

        return response()->json([
            'success' => true,
            'record'  => $record,
        ]);
    }

    public function destroy(ClinicalRecord $record)
    {
        Gate::authorize('delete', $record);

        $record->delete();

        return response()->json([
            'success' => true,
            'message' => 'Registro eliminado.',
        ]);
    }
}
