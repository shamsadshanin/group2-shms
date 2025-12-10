<?php

namespace App\Services;

use App\Models\Tblsymptominput;
use App\Models\Tbldiseaseprediction;
use Illuminate\Support\Str;

class AISymptomService
{
    /**
     * Mock AI symptom checker that analyzes symptoms and returns predictions
     * 
     * @param string $symptomsDescription
     * @param string $patientId
     * @return array
     */
    public function analyzeSymptoms(string $symptomsDescription, string $patientId): array
    {
        // Save the symptom input
        $symptomInput = Tblsymptominput::create([
            'cInputID' => 'IN-' . Str::random(3),
            'cPatientID' => $patientId,
            'cDescription' => $symptomsDescription,
            'dDate' => now()->toDateString(),
            'dTimestamp' => now(),
        ]);

        // Mock AI analysis based on keywords
        $prediction = $this->generateMockPrediction($symptomsDescription, $symptomInput->cInputID);

        return [
            'success' => true,
            'input_id' => $symptomInput->cInputID,
            'prediction' => $prediction,
            'message' => 'Symptoms analyzed successfully'
        ];
    }

    /**
     * Generate mock disease prediction based on symptom keywords
     * 
     * @param string $symptomsDescription
     * @param string $inputId
     * @return array
     */
    private function generateMockPrediction(string $symptomsDescription, string $inputId): array
    {
        $symptoms = strtolower($symptomsDescription);
        $diseases = [];
        $confidence = 0;

        // Keyword-based disease mapping
        $diseaseMap = [
            'fever' => [
                'diseases' => ['Viral Influenza', 'Malaria', 'Dengue Fever', 'Typhoid'],
                'base_confidence' => 85
            ],
            'cough' => [
                'diseases' => ['Common Cold', 'Bronchitis', 'Pneumonia', 'Asthma'],
                'base_confidence' => 75
            ],
            'headache' => [
                'diseases' => ['Migraine', 'Tension Headache', 'Sinusitis', 'Hypertension'],
                'base_confidence' => 70
            ],
            'chest pain' => [
                'diseases' => ['Angina', 'Heart Attack', 'Acid Reflux', 'Muscle Strain'],
                'base_confidence' => 80
            ],
            'stomach pain' => [
                'diseases' => ['Gastritis', 'Appendicitis', 'Ulcer', 'Food Poisoning'],
                'base_confidence' => 75
            ],
            'nausea' => [
                'diseases' => ['Food Poisoning', 'Migraine', 'Pregnancy', 'Stomach Flu'],
                'base_confidence' => 65
            ],
            'fatigue' => [
                'diseases' => ['Anemia', 'Depression', 'Chronic Fatigue', 'Sleep Apnea'],
                'base_confidence' => 60
            ],
            'shortness of breath' => [
                'diseases' => ['Asthma', 'Heart Failure', 'Pneumonia', 'Anxiety'],
                'base_confidence' => 85
            ],
            'rash' => [
                'diseases' => ['Allergic Reaction', 'Eczema', 'Chickenpox', 'Heat Rash'],
                'base_confidence' => 70
            ],
            'joint pain' => [
                'diseases' => ['Arthritis', 'Gout', 'Lyme Disease', 'Flu'],
                'base_confidence' => 65
            ]
        ];

        // Find matching diseases based on symptoms
        foreach ($diseaseMap as $keyword => $data) {
            if (strpos($symptoms, $keyword) !== false) {
                $diseases = array_merge($diseases, $data['diseases']);
                $confidence = max($confidence, $data['base_confidence']);
            }
        }

        // If no specific diseases found, use general ones
        if (empty($diseases)) {
            $diseases = ['General Illness', 'Viral Infection', 'Bacterial Infection'];
            $confidence = 50;
        }

        // Remove duplicates and select top prediction
        $diseases = array_unique($diseases);
        $predictedDisease = $diseases[array_rand($diseases)];
        
        // Add some randomness to confidence
        $confidence += rand(-10, 10);
        $confidence = max(20, min(95, $confidence));

        // Save the prediction
        $prediction = Tbldiseaseprediction::create([
            'cPredictionID' => 'PRED-' . Str::random(3),
            'cDiseaseName' => $predictedDisease,
            'nConfidenceScore' => $confidence,
            'dTimestamp' => now(),
            'cInputID' => $inputId,
        ]);

        return [
            'disease_name' => $predictedDisease,
            'confidence_score' => $confidence,
            'prediction_id' => $prediction->cPredictionID,
            'recommendations' => $this->generateRecommendations($predictedDisease, $confidence)
        ];
    }

    /**
     * Generate recommendations based on predicted disease
     * 
     * @param string $diseaseName
     * @param float $confidence
     * @return array
     */
    private function generateRecommendations(string $diseaseName, float $confidence): array
    {
        $recommendations = [];

        // High confidence recommendations
        if ($confidence >= 80) {
            $recommendations[] = [
                'type' => 'Urgent',
                'message' => 'Consult a doctor immediately for proper diagnosis and treatment.'
            ];
        } elseif ($confidence >= 60) {
            $recommendations[] = [
                'type' => 'Recommended',
                'message' => 'Schedule an appointment with your healthcare provider.'
            ];
        } else {
            $recommendations[] = [
                'type' => 'Monitor',
                'message' => 'Monitor your symptoms and consult a doctor if they worsen.'
            ];
        }

        // Disease-specific recommendations
        $diseaseRecommendations = [
            'Viral Influenza' => [
                'Rest and stay hydrated',
                'Take over-the-counter fever reducers',
                'Avoid contact with others to prevent spread'
            ],
            'Migraine' => [
                'Rest in a dark, quiet room',
                'Apply cold or warm compresses',
                'Stay hydrated and maintain regular meal times'
            ],
            'Common Cold' => [
                'Get plenty of rest',
                'Drink warm fluids',
                'Use saline nasal sprays'
            ],
            'Asthma' => [
                'Use prescribed inhaler',
                'Avoid triggers like dust and smoke',
                'Monitor breathing regularly'
            ],
            'Gastritis' => [
                'Eat smaller, more frequent meals',
                'Avoid spicy and acidic foods',
                'Consider antacids as needed'
            ]
        ];

        if (isset($diseaseRecommendations[$diseaseName])) {
            $recommendations = array_merge($recommendations, 
                array_map(function($rec) {
                    return ['type' => 'Self-care', 'message' => $rec];
                }, $diseaseRecommendations[$diseaseName])
            );
        }

        return $recommendations;
    }

    /**
     * Get patient's symptom history
     * 
     * @param string $patientId
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPatientSymptomHistory(string $patientId)
    {
        return Tblsymptominput::where('cPatientID', $patientId)
            ->with(['prediction'])
            ->orderBy('dTimestamp', 'desc')
            ->limit(10)
            ->get();
    }

    /**
     * Get disease prediction statistics
     * 
     * @return array
     */
    public function getPredictionStatistics(): array
    {
        $totalPredictions = Tbldiseaseprediction::count();
        $avgConfidence = Tbldiseaseprediction::avg('nConfidenceScore') ?? 0;
        
        $topDiseases = Tbldiseaseprediction::select('cDiseaseName')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('cDiseaseName')
            ->orderBy('count', 'desc')
            ->limit(5)
            ->get()
            ->pluck('count', 'cDiseaseName');

        return [
            'total_predictions' => $totalPredictions,
            'average_confidence' => round($avgConfidence, 2),
            'top_diseases' => $topDiseases,
            'predictions_this_week' => Tbldiseaseprediction::where('dTimestamp', '>=', now()->subDays(7))->count()
        ];
    }
}