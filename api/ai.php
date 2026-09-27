<?php
require_once __DIR__ . '/../config/config.php';
$userId = requireLogin();

if (GEMINI_API_KEY === 'YOUR_GEMINI_API_KEY') {
    jsonResponse(false, 'Add your Gemini API key in config/config.php first.', [], 500);
}

$action = $_POST['action'] ?? '';
$materialId = (int)($_POST['material_id'] ?? 0);

$stmt = db()->prepare('SELECT id,title,content FROM materials WHERE id=? AND user_id=?');
$stmt->execute([$materialId, $userId]);
$material = $stmt->fetch();
if (!$material) jsonResponse(false, 'Study material not found.', [], 404);

$instructions = [
    'summary' => 'Create a clear, concise study summary. Use headings and bullet points. Keep important definitions, facts and examples.',
    'flashcards' => 'Create 10 useful study flashcards. Return ONLY valid JSON as an array of objects with keys "question" and "answer".',
    'quiz' => 'Create 10 multiple-choice questions. Return ONLY valid JSON as an array of objects with keys "question", "options" (array of 4 strings), and "answer" (the correct option text).',
    'plan' => 'Create a practical 7-day study plan based on this material. Include daily topics, tasks and a short revision activity.'
];

if (!isset($instructions[$action])) jsonResponse(false, 'Invalid AI action.', [], 400);

$prompt = "You are AI StudyBuddy, an educational assistant.\n\nTask: {$instructions[$action]}\n\nStudy material title: {$material['title']}\n\nStudy material:\n{$material['content']}";

$url = 'https://generativelanguage.googleapis.com/v1beta/models/' . GEMINI_MODEL . ':generateContent?key=' . urlencode(GEMINI_API_KEY);
$payload = [
    'contents' => [[
        'parts' => [['text' => $prompt]]
    ]],
    'generationConfig' => [
        'temperature' => 0.4,
        'maxOutputTokens' => 4096
    ]
];

$ch = curl_init($url);
curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
    CURLOPT_POSTFIELDS => json_encode($payload),
    CURLOPT_TIMEOUT => 60
]);
$response = curl_exec($ch);
$error = curl_error($ch);
$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($response === false) jsonResponse(false, 'Gemini request failed: ' . $error, [], 502);
$data = json_decode($response, true);
$text = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';

if ($status >= 400 || $text === '') {
    $message = $data['error']['message'] ?? 'Gemini did not return a response.';
    jsonResponse(false, $message, [], 502);
}

if (in_array($action, ['flashcards', 'quiz'], true)) {
    $clean = preg_replace('/^```(?:json)?\s*|\s*```$/i', '', trim($text));
    $decoded = json_decode($clean, true);
    if (json_last_error() === JSON_ERROR_NONE) {
        $text = json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}

$stmt = db()->prepare('INSERT INTO ai_outputs (user_id,material_id,type,content) VALUES (?,?,?,?)');
$stmt->execute([$userId, $materialId, $action, $text]);

jsonResponse(true, 'AI content generated successfully.', ['type' => $action, 'content' => $text]);
