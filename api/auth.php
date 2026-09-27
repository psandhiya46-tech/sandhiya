<?php
require_once __DIR__ . '/../config/config.php';

$action = $_GET['action'] ?? '';

if ($action === 'register' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 6) {
        jsonResponse(false, 'Enter a valid name, email and password of at least 6 characters.', [], 422);
    }

    $check = db()->prepare('SELECT id FROM users WHERE email = ?');
    $check->execute([$email]);
    if ($check->fetch()) jsonResponse(false, 'Email already registered.', [], 409);

    $stmt = db()->prepare('INSERT INTO users (name,email,password) VALUES (?,?,?)');
    $stmt->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT)]);
    jsonResponse(true, 'Registration successful. Please login.');
}

if ($action === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = db()->prepare('SELECT id,name,email,password,role FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password'])) {
        jsonResponse(false, 'Invalid email or password.', [], 401);
    }

    session_regenerate_id(true);
    $_SESSION['user_id'] = (int)$user['id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_role'] = $user['role'];

    unset($user['password']);
    jsonResponse(true, 'Login successful.', ['user' => $user]);
}

if ($action === 'logout') {
    $_SESSION = [];
    session_destroy();
    jsonResponse(true, 'Logged out successfully.');
}

if ($action === 'me') {
    $userId = requireLogin();
    $stmt = db()->prepare('SELECT id,name,email,role,created_at FROM users WHERE id = ?');
    $stmt->execute([$userId]);
    jsonResponse(true, '', ['user' => $stmt->fetch()]);
}

jsonResponse(false, 'Invalid request.', [], 400);
