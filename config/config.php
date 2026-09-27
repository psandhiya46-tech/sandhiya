<?php
session_start();

// const DB_HOST = 'localhost';
// const DB_NAME = 'ai_buddy';
// const DB_USER = 'root';
// const DB_PASS = 'root';
const DB_HOST = 'mysql8';
const DB_NAME = 'ai_buddy';
const DB_USER = 'root';
const DB_PASS = 'root';
// Add your Google Gemini API key here.

// const GEMINI_MODEL = 'gemini-2.5-flash';
const GEMINI_MODEL = 'gemini-3.8-flash';
const GEMINI_API_KEY = '';


function db(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        $pdo = new PDO(
            'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
            DB_USER,
            DB_PASS,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]
        );
    }
    return $pdo;
}

function jsonResponse(bool $success, string $message = '', array $data = [], int $status = 200): never {
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['success' => $success, 'message' => $message, 'data' => $data]);
    exit;
}

function requireLogin(): int {
    if (empty($_SESSION['user_id'])) jsonResponse(false, 'Please login first.', [], 401);
    return (int) $_SESSION['user_id'];
}
