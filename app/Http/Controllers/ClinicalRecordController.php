<?php

namespace App\Http\Controllers;

use App\Models\ClinicalRecord;
use App\Models\Professional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class ClinicalRecordController extends Controller
{
    public function index(Request $request)
    {

        $query = ClinicalRecord::with(['patient', 'professional', 'creator'])
            ->ordered();

        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->whereHas('patient', function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('dni', 'like', "%{$search}%");
            });
        }

        if ($request->filled('professional_id')) {
            $query->where('professional_id', $request->get('professional_id'));
        }

        if ($request->filled('date_from')) {
            $query->where('date', '>=', $request->get('date_from'));
        }

        if ($request->filled('date_to')) {
            $query->where('date', '<=', $request->get('date_to'));
        }

        $records = $query->paginate(20)->withQueryString();

        $professionals = Professional::active()->orderBy('last_name')->orderBy('first_name')->get();

        if ($request->ajax()) {
            return response()->json([
                'records' => $records->items(),
                'pagination' => [
                    'current_page' => $records->currentPage(),
                    'last_page'    => $records->lastPage(),
                    'per_page'     => $records->perPage(),
                    'total'        => $records->total(),
                ],
            ]);
        }

        return view('clinical.index', compact('records', 'professionals'));
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
