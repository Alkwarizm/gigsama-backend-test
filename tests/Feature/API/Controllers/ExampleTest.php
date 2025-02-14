<?php


use EchoLabs\Prism\Enums\Provider;
use EchoLabs\Prism\Prism;

test('prism', function () {
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

    $response = Prism::text()
        ->using(Provider::Gemini, 'gemini-1.5-flash')
        ->withSystemPrompt("You're a medical expert who can analyze doctor notes and provide actionable steps and a plan for the patient's treatment such that actions and schedules can be extracted for cron jobs.". "As an example, given a note, you're able to do this:\n". $prompt . "\n". $responseText)
        ->withPrompt($test)
        ->generate();

    echo $response->text;

    dump($response->text);

    $this->assertTrue(true);
});
