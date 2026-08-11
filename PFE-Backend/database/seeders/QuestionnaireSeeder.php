<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\Questionnaire;
use App\Models\User;
use Illuminate\Database\Seeder;

class QuestionnaireSeeder extends Seeder
{
    public function run(): void
    {
        $analyst = User::whereHas('roles', fn($q) => $q->where('name', 'analyst'))->first();

        if (! $analyst) {
            return;
        }

        $questionnaire = Questionnaire::updateOrCreate(
            ['title' => 'Questionnaire test de cybersécurité'],
            [
                'analyst_id' => $analyst->id,
                'description' => 'Un questionnaire test conçu pour vérifier la sécurité informatique de l’entreprise.',
                'status' => 'published',
            ]
        );

        $questions = [
            [
                'question_text' => 'Votre entreprise utilise-t-elle des mots de passe complexes ? ',
                'question_type_id' => 1,
                'description' => null,
                'options' => null,
                'position' => 1,
                'required' => true,
            ],
            [
                'question_text' => 'Les employés utilisent-ils l’authentification à deux facteurs ?',
                'question_type_id' => 2,
                'description' => null,
                'options' => json_encode(['oui', 'non']),
                'position' => 2,
                'required' => true,
            ],
            [
                'question_text' => 'Quelle méthode utilisez-vous principalement pour protéger les comptes ?',
                'question_type_id' => 3,
                'description' => null,
                'options' => json_encode(['Authentification à deux facteurs', 'Mot de passe uniquement', 'Certificat numérique', 'Autre']),
                'position' => 3,
                'required' => true,
            ],
            [
                'question_text' => 'Décrivez votre politique de sauvegarde des données.',
                'question_type_id' => 4,
                'description' => null,
                'options' => null,
                'position' => 4,
                'required' => false,
            ],
        ];

        foreach ($questions as $data) {
            Question::updateOrCreate(
                [
                    'questionnaire_id' => $questionnaire->id,
                    'question' => $data['question_text'],
                ],
                [
                    'question_type_id' => $data['question_type_id'],
                    'description' => $data['description'],
                    'options' => $data['options'],
                    'position' => $data['position'],
                    'required' => $data['required'],
                ]
            );
        }
    }
}
