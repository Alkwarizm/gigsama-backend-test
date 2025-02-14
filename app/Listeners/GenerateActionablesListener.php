<?php

namespace App\Listeners;

use App\Events\NoteCreated;
use App\Models\Note;
use EchoLabs\Prism\Enums\Provider;
use EchoLabs\Prism\Prism;
use EchoLabs\Prism\Text\Response;

class GenerateActionablesListener
{
    public function handle(NoteCreated $event): void
    {
        $response = $this->generate($event->note);

        $actionables = $this->extractActionables($response->text);

        collect($actionables)->each(function ($actionable) use ($event) {
            $event->note->actionable()->create([
                'description' => $actionable['action'],
                'type' => $actionable['schedule'] === null ? 'step': 'schedule',
                'status' => 'pending',
                'schedule' => $actionable['schedule'],
                'cron_expression' => $actionable['cron_expression'],
            ]);
        });
    }

    protected function generate(Note $note): Response
    {
        return Prism::text()
            ->using(Provider::Gemini, 'gemini-1.5-flash')
            ->withSystemPrompt($this->template())
            ->withPrompt($note->content)
            ->generate();
    }

    protected function template(): string
    {
        $prompt = 'Patient Name: John Doe';
        $prompt .= 'Patient ID: 12345';
        $prompt .= 'Date: October 10, 2023';
        $prompt .= 'Doctor Name: Dr. Emily Smith';
        $prompt .= 'Doctor ID: 67890';
        $prompt .= "Clinical Summary\n";
        $prompt .= 'John Doe, a 45-year-old male, presented with complaints of persistent chest pain and shortness of breath over the past week. He has a history of hypertension and high cholesterol. Physical examination revealed elevated blood pressure (150/95 mmHg) and a heart rate of 90 bpm. An ECG showed signs of possible myocardial ischemia.'. "\n";
        $prompt .= "Diagnosis\n";
        $prompt .= "1. Suspected angina pectoris (chest pain due to reduced blood flow to the heart).\n";
        $prompt .= "2. Hypertension (high blood pressure).\n";
        $prompt .= "3. Hyperlipidemia (high cholesterol).\n";
        $prompt .= "Treatment Plan\n";
        $prompt .= "1. Immediate Actions (Checklist):\n";
        $prompt .= "a. Administer nitroglycerin (0.4 mg sublingual) to relieve chest pain.\n";
        $prompt .= "b. Schedule an echocardiogram to assess heart function.\n";
        $prompt .= "c. Order blood tests (lipid profile, troponin levels, and CBC).\n";
        $prompt .= "d. Prescribe aspirin (81 mg daily) to reduce the risk of blood clots.\n";
        $prompt .= "2. Follow-Up Actions (Plan):\n";
        $prompt .= "a. Schedule a stress test within the next 7 days to evaluate heart function under exertion.\n";
        $prompt .= "b. Prescribe atorvastatin (20 mg daily) to manage cholesterol levels.\n";
        $prompt .= "c. Schedule a follow-up appointment in 2 weeks to monitor blood pressure and adjust medications if necessary.\n";

        $responseText = "Actionable Steps:\n";
        $responseText .= "1. Administer nitroglycerin (0.4 mg sublingual)\n";
        $responseText .= "2. Schedule an echocardiogram\n";
        $responseText .= "3. Order blood tests (lipid profile, troponin levels, and CBC)\n";
        $responseText .= "4. Prescribe aspirin (81 mg daily)\n";
        $responseText .= "Plan:\n";
        $responseText .= "1. Schedule a stress test - cron: 0 9 * * * | Every day at 9:00 AM, starting tomorrow, for the next 7 days\n";
        $responseText .= "2. Prescribe atorvastatin 20 mg - cron: 0 8 * * * | Every day at 8:00 AM, starting today)\n";
        $responseText .= "3. Schedule a follow-up appointment - cron: 0 11 24 10 * | Once, 2 weeks from today at 11:00 AM\n";

        return "You're a medical expert who can analyze doctor notes and provide actionable steps and a plan for the patient's treatment such that actions and schedules can be extracted for cron jobs.". "As an example, given a note, you're able to do this:\n". $prompt . "\n". $responseText;
    }

    protected function extractActionables(string $text): array
    {
        $actionables = [];
        $lines = explode("\n", $text);
        $isPlan = false;

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) {
                continue;
            }

            // Check if we've entered the "Plan:" section
            if (str_contains($line, 'Plan:')) {
                $isPlan = true;
                continue;
            }

            // Extract actions that start with a number followed by a period
            if (preg_match('/^\d+\.\s*(.*)$/', $line, $matches)) {
                $action = $matches[1];
                $schedule = null;
                $cronExpression = null;

                // If we're in the "Plan:" section, try to extract cron expression and schedule
                if ($isPlan) {
                    // Handle cron expressions surrounded by backticks or with additional text
                    if (preg_match('/`([^`]+)`\s*\(([^)]+)\)/', $line, $cronMatches)) {
                        $cronExpression = $cronMatches[1];
                        $schedule = $cronMatches[2];
                        // Remove the cron and schedule details from the action
                        $action = preg_replace('/\s*`[^`]+`\s*\([^)]+\)/', '', $action);
                    } elseif (preg_match('/- cron:\s*([^ ]+)\s*\(([^)]+)\)/', $line, $cronMatches)) {
                        $cronExpression = $cronMatches[1];
                        $schedule = $cronMatches[2];
                        // Remove the cron and schedule details from the action
                        $action = preg_replace('/\s*- cron:\s*[^ ]+\s*\([^)]+\)/', '', $action);
                    }
                }

                // Clean up the action by removing extra spaces and trailing punctuation
                $action = trim($action);
                $action = rtrim($action, '.'); // Remove trailing period if present

                // Add the actionable to the list
                $actionables[] = [
                    'action' => $action,
                    'schedule' => $schedule,
                    'cron_expression' => $cronExpression,
                ];
            }
        }

        return $actionables;
    }
}
