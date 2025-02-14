<?php

use App\Actions\CreateReminderAction;
use App\Events\NoteCreated;
use App\Listeners\GenerateActionablesListener;
use App\Models\Actionable;
use App\Models\Note;

it('creates a reminder for an actionable', function () {
    $actionable = Actionable::factory(['type' => 'schedule'])->create();

    (new CreateReminderAction)->execute($actionable);

    $this->assertDatabaseHas('reminders', [
        'actionable_id' => $actionable->id,
        'counts' => 0,
    ]);
});

it('creates actionables', function () {
    $test = 'Patient Name: John Doe';
    $test .= 'Patient ID: 12345';
    $test .= 'Date: October 10, 2023';
    $test .= 'Doctor Name: Dr. Emily Smith';
    $test .= 'Doctor ID: 67890';
    $test .= "Subjective:\n";
    $test .= 'John is a 45-year-old male presenting with complaints of chest pain and shortness of breath for the past week.';
    $test .= "He describes the pain as a pressure-like sensation in the center of his chest, radiating to his left arm.";
    $test .= "The pain occurs during physical activity and subsides with rest.";
    $test .= "He also reports occasional dizziness and fatigue.";
    $test .= "He has a history of hypertension and high cholesterol.";
    $test .= "He smokes 1 pack per day and has a sedentary lifestyle. No known drug allergies.";
    $test .= "Objective:\n";
    $test .= "Vitals: BP 150/95 mmHg, HR 90 bpm, RR 18, Temp 98.6°F, SpO2 96% on room air.\n";
    $test .= "Physical Exam:\n";
    $test .= "1. Cardiovascular: Regular rate and rhythm, no murmurs.\n";
    $test .= "2. Respiratory: Clear to auscultation bilaterally.\n";
    $test .= "3. Extremities: No edema.\n";
    $test .= "ECG: Shows ST-segment depression in leads V4-V6, suggestive of myocardial ischemia.\n";
    $test .= "Labs: Pending (ordered lipid profile, troponin, CBC).";
    $test .= "Assessment:\n";
    $test .= "Suspected Angina Pectoris: Likely due to coronary artery disease.\n";
    $test .= "Hypertension: Poorly controlled.\n";
    $test .= "Hyperlipidemia: Elevated LDL and total cholesterol.\n";
    $test .= "Tobacco Use Disorder: Active smoker.\n";
    $test .= "Plan:\n";
    $test .= "1. Administer nitroglycerin 0.4 mg sublingual for chest pain.\n";
    $test .= "2. Order an echocardiogram to assess cardiac function.\n";
    $test .= "3. Start aspirin 81 mg daily to reduce thrombotic risk.\n";
    $test .= "4. Order stat labs: lipid profile, troponin, CBC..\n";
    $test .= "Follow-Up Actions:\n";
    $test .= "1. Schedule a stress test within the next 7 days.\n";
    $test .= "2. Refer to cardiology for further evaluation.\n";
    $test .= "3. Start atorvastatin 20 mg daily for cholesterol management.\n";
    $test .= "4. Schedule follow-up in 2 weeks to reassess BP and symptoms.\n";


    $note = Note::factory()->create([
        'content' => $test,
    ]);

    NoteCreated::dispatch($note);

    expect($note->fresh())
        ->actionables->toHaveCount(9);
});
