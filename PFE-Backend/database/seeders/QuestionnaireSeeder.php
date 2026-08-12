<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\Questionnaire;
use App\Models\QuestionType;
use Illuminate\Database\Seeder;

class QuestionnaireSeeder extends Seeder
{
    public function run(): void
    {
        $questionnaire = Questionnaire::updateOrCreate(
            ['title' => 'Questionnaire test de cybersécurité'],
            [
                'description' => "Un questionnaire test conçu pour vérifier la sécurité informatique de l'entreprise.",
                'status' => 'published',
            ]
        );

        $types = QuestionType::pluck('id', 'name');

        $questions = [
            [
                'question' => 'Votre entreprise utilise-t-elle des mots de passe complexes ?',
                'type' => 'unique_choice',
                'description' => null,
                'options' => ['Oui', 'Non', 'Partiellement'],
                'position' => 1,
                'required' => true,
            ],
            [
                'question' => "Les employés utilisent-ils l'authentification à deux facteurs ?",
                'type' => 'unique_choice',
                'description' => null,
                'options' => ['Oui', 'Non'],
                'position' => 2,
                'required' => true,
            ],
            [
                'question' => 'Quelles méthodes utilisez-vous pour protéger les comptes ?',
                'type' => 'multiple_choice',
                'description' => 'Plusieurs réponses possibles.',
                'options' => [
                    'Authentification à deux facteurs',
                    'Mot de passe uniquement',
                    'Certificat numérique',
                    'Gestionnaire de mots de passe',
                ],
                'position' => 3,
                'required' => true,
            ],
            [
                'question' => 'Décrivez votre politique de sauvegarde des données.',
                'type' => 'text',
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
                    'question' => $data['question'],
                ],
                [
                    'question_type_id' => $types[$data['type']],
                    'description' => $data['description'],
                    'options' => $data['options'],
                    'position' => $data['position'],
                    'required' => $data['required'],
                ]
            );
        }
    }
}
