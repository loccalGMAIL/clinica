<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\ClinicalRecord;
use App\Models\User;
use Illuminate\Database\Seeder;

class ClinicalRecordSeeder extends Seeder
{
    /**
     * Contenido clínico realista por especialidad.
     * specialty_id: 1=Clínica Médica, 2=Cardiología, 3=Dermatología, 4=Traumatología
     */
    private array $records = [
        1 => [ // Clínica Médica
            [
                'content'   => 'Paciente refiere cefalea tensional persistente hace 3 días, sin fiebre. TA 130/85 mmHg, FC 78 lpm, afebril. Sin signos meníngeos.',
                'diagnosis' => 'Cefalea tensional',
                'treatment' => 'Ibuprofeno 400 mg c/8 hs por 5 días. Reposo relativo. Control en 7 días si persiste.',
            ],
            [
                'content'   => 'Paciente masculino, 52 años, en control anual. Sin síntomas actuales. Laboratorio reciente dentro de parámetros normales. TA 125/80 mmHg.',
                'diagnosis' => 'Control anual sin patología aguda',
                'treatment' => 'Se solicita laboratorio de rutina anual. Electrocardiograma de control. Próximo control en 12 meses.',
            ],
            [
                'content'   => 'Paciente con diagnóstico previo de diabetes tipo 2. Glucemia en ayunas: 148 mg/dl. HbA1c 7.2%. Refiere cumplimiento de dieta en un 70%.',
                'diagnosis' => 'Diabetes tipo 2, control metabólico regular',
                'treatment' => 'Ajuste de metformina a 1000 mg c/12 hs. Plan nutricional actualizado. Control en 3 meses con laboratorio.',
            ],
            [
                'content'   => 'Paciente femenina, 38 años. Cuadro de 4 días de evolución: fiebre 38.5°C, odinofagia, mialgia y tos seca. Sin disnea.',
                'diagnosis' => 'Síndrome gripal',
                'treatment' => 'Paracetamol 500 mg c/6 hs. Reposo y buena hidratación. Consultar ante disnea o fiebre >39°C por más de 48 hs.',
            ],
            [
                'content'   => 'Paciente hipertenso en seguimiento. TA 145/90 mmHg en consulta, refiere no haber tomado medicación los últimos 2 días. Sin síntomas.',
                'diagnosis' => 'Hipertensión arterial esencial, mal controlada',
                'treatment' => 'Enalapril 10 mg/día. Se enfatiza adherencia terapéutica. Control de TA en domicilio. Próximo control en 30 días.',
            ],
            [
                'content'   => 'Consulta por dolor abdominal epigástrico postprandial de 1 semana de evolución. Sin vómitos ni fiebre. Abdomen blando, doloroso a la palpación en epigastrio.',
                'diagnosis' => 'Probable gastritis aguda',
                'treatment' => 'Omeprazol 20 mg en ayunas por 14 días. Dieta blanda. Evitar AINEs y alcohol. Control en 2 semanas.',
            ],
        ],
        2 => [ // Cardiología
            [
                'content'   => 'Paciente masculino, 60 años, derivado por palpitaciones irregulares. ECG con fibrilación auricular de baja respuesta ventricular. FC 68 lpm.',
                'diagnosis' => 'Fibrilación auricular crónica estable',
                'treatment' => 'Se mantiene tratamiento anticoagulante oral. Ajuste de betabloqueante. Holter de 24 hs. Control en 30 días.',
            ],
            [
                'content'   => 'Paciente con antecedente de HTA. Refiere episodios de dolor precordial de carácter opresivo con el esfuerzo, de 5 min de duración, que ceden con reposo.',
                'diagnosis' => 'Angina de pecho estable. A descartar cardiopatía isquémica',
                'treatment' => 'AAS 100 mg/día. Nitoglicerina sublingual a demanda. Solicito ergometría y coronariografía. Derivación a cardiología intervencionista.',
            ],
            [
                'content'   => 'Control en paciente con insuficiencia cardíaca compensada. Sin disnea en reposo. Auscultación: soplo sistólico 2/6 en foco aórtico. FC 72 lpm, TA 120/75 mmHg.',
                'diagnosis' => 'Insuficiencia cardíaca compensada CF-II NYHA',
                'treatment' => 'Enalapril 10 mg c/12 hs, furosemida 40 mg/día. Dieta hiposódica. Eco doppler cardíaco en 3 meses. Control mensual.',
            ],
            [
                'content'   => 'Paciente femenina, 55 años, control de HTA severa. TA 160/100 mmHg a pesar de tratamiento actual. Sin cefalea ni síntomas neurológicos.',
                'diagnosis' => 'Hipertensión arterial severa resistente',
                'treatment' => 'Amlodipina 10 mg/día añadida al esquema. Se solicita MAPA 24 hs y función renal. Derivar a nefrología para evaluación.',
            ],
            [
                'content'   => 'Paciente posoperatorio de angioplastia coronaria hace 6 meses. Sin síntomas actuales. ECG sin cambios isquémicos. TA 115/70 mmHg.',
                'diagnosis' => 'Cardiopatía isquémica posangioplastia, asintomático',
                'treatment' => 'Mantener doble antiagregación (AAS + clopidogrel) hasta cumplir 12 meses. Estatina de alta intensidad. Control en 3 meses.',
            ],
        ],
        3 => [ // Dermatología
            [
                'content'   => 'Paciente con lesión eccematosa en cara anterior de ambos antebrazos. Pruriginosa, con vesículas y costras. Refiere contacto con detergente nuevo hace 10 días.',
                'diagnosis' => 'Dermatitis de contacto alérgica',
                'treatment' => 'Corticoide tópico (betametasona 0.05%) c/12 hs por 10 días. Antihistamínico oral. Evitar contacto con el alérgeno. Control en 15 días.',
            ],
            [
                'content'   => 'Adolescente, 17 años, con comedones y pápulas inflamatorias en frente, mejillas y mentón. Sin lesiones noduloquísticas.',
                'diagnosis' => 'Acné moderado',
                'treatment' => 'Peróxido de benzoilo gel 5% por las noches. Adapaleno 0.1% en días alternos. Antibiótico tópico si empeora. Control en 6 semanas.',
            ],
            [
                'content'   => 'Paciente refiere placas eritematoescamosas en codos, rodillas y cuero cabelludo de 2 años de evolución. Con exacerbaciones en períodos de estrés.',
                'diagnosis' => 'Psoriasis en placas leve-moderada',
                'treatment' => 'Corticoide tópico en placas. Emolientes diarios. Derivación a dermatología para evaluación de tratamiento sistémico. Control en 4 semanas.',
            ],
            [
                'content'   => 'Nevus melanocítico compuesto en espalda. ABCDE: asimetría leve, borde irregular, color homogéneo marrón, diámetro 7 mm, evolución estable según paciente.',
                'diagnosis' => 'Nevus melanocítico compuesto con atipía leve — a seguimiento',
                'treatment' => 'Fotodocumentación. Control dermatoscópico en 4 meses. Indicaciones de fotoprotección y autoexamen mensual.',
            ],
            [
                'content'   => 'Paciente con múltiples verrugas vulgares en cara dorsal de ambas manos, de 3 meses de evolución. No dolorosas.',
                'diagnosis' => 'Verrugas vulgares (VPH)',
                'treatment' => 'Crioterapia con nitrógeno líquido en esta consulta sobre 4 lesiones. Nueva sesión en 3 semanas. Ácido salicílico tópico en domicilio.',
            ],
        ],
        4 => [ // Traumatología
            [
                'content'   => 'Paciente masculino, 28 años. Torcedura de tobillo derecho durante práctica deportiva hace 48 hs. Rx sin fractura. Edema grado II y equimosis periarticular.',
                'diagnosis' => 'Esguince grado II de tobillo derecho',
                'treatment' => 'RICE (reposo, hielo, compresión, elevación). AINE oral por 5 días. Vendaje funcional. Inicio de kinesioterapia en 5 días. Control en 2 semanas.',
            ],
            [
                'content'   => 'Paciente femenina, 45 años, con dolor lumbar crónico de 2 años de evolución. Sin irradiación a miembros inferiores. Resonancia previa: hernia L4-L5 contenida.',
                'diagnosis' => 'Lumbalgia crónica. Hernia de disco L4-L5 sin compromiso radicular',
                'treatment' => 'AINE en fase aguda (meloxicam 15 mg/día). Kinesioterapia intensiva 2x semana. Ejercicios de estabilización de core. Control en 30 días.',
            ],
            [
                'content'   => 'Control de fractura de radio distal derecho tratada con yeso antebraquial hace 6 semanas. Rx de control: buena evolución con callo óseo bien formado.',
                'diagnosis' => 'Fractura de radio distal derecho en buena consolidación',
                'treatment' => 'Retiro de yeso. Inicio de kinesioterapia para recuperación de rango articular. Evitar esfuerzos con la mano por 4 semanas más. Rx en 6 semanas.',
            ],
            [
                'content'   => 'Paciente con dolor en región del tendón de Aquiles derecho, de inicio gradual hace 3 semanas, relacionado con actividad física intensa.',
                'diagnosis' => 'Tendinitis del tendón de Aquiles derecho',
                'treatment' => 'Reposo deportivo 2 semanas. AINE tópico y oral. Kinesioterapia con ultrasonido. Plantillas con realce de talón. Control en 3 semanas.',
            ],
            [
                'content'   => 'Paciente, 67 años, con gonalgia bilateral de predominio derecho, crónica, con limitación de la marcha en distancias largas. Rx: pinzamiento femorotibial interno bilateral.',
                'diagnosis' => 'Gonartrosis bilateral grado III-IV',
                'treatment' => 'Paracetamol 1g c/8 hs. Infiltración intraarticular derecha con corticoide. Kinesioterapia. Evaluación para reemplazo articular total. Control en 4 semanas.',
            ],
        ],
    ];

    public function run(): void
    {
        $adminUser = User::whereHas('profile', fn ($q) => $q->where('name', 'Administrador'))->first();

        $attendedAppointments = Appointment::where('status', 'attended')
            ->with(['professional.specialty'])
            ->get();

        foreach ($attendedAppointments as $appointment) {
            $specialtyId = $appointment->professional->specialty_id ?? 1;
            $pool        = $this->records[$specialtyId] ?? $this->records[1];
            $record      = $pool[array_rand($pool)];

            ClinicalRecord::create([
                'patient_id'      => $appointment->patient_id,
                'professional_id' => $appointment->professional_id,
                'appointment_id'  => $appointment->id,
                'date'            => $appointment->appointment_date,
                'content'         => $record['content'],
                'diagnosis'       => $record['diagnosis'],
                'treatment'       => $record['treatment'],
                'created_by'      => $adminUser?->id,
            ]);
        }
    }
}
