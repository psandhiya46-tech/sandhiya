<?php
require_once __DIR__ . '/../config/config.php';
$userId = requireLogin();
$action = $_GET['action'] ?? 'list';

if ($action === 'list') {
    $stmt = db()->prepare('SELECT id,title,created_at,LEFT(content,180) AS preview FROM materials WHERE user_id=? ORDER BY id DESC');
    $stmt->execute([$userId]);
    jsonResponse(true, '', ['materials' => $stmt->fetchAll()]);
}

if ($action === 'get') {
    $id = (int)($_GET['id'] ?? 0);
    $stmt = db()->prepare('SELECT id,title,content,created_at FROM materials WHERE id=? AND user_id=?');
    $stmt->execute([$id, $userId]);
    $material = $stmt->fetch();
    if (!$material) jsonResponse(false, 'Material not found.', [], 404);
    jsonResponse(true, '', ['material' => $material]);
}

if ($action === 'save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    if ($title === '' || $content === '') jsonResponse(false, 'Title and study material are required.', [], 422);
    if (mb_strlen($content) > 100000) jsonResponse(false, 'Material is too large. Keep it below 100,000 characters.', [], 422);

    $stmt = db()->prepare('INSERT INTO materials (user_id,title,content) VALUES (?,?,?)');
    $stmt->execute([$userId, $title, $content]);
    jsonResponse(true, 'Material saved.', ['id' => (int)db()->lastInsertId()]);
}

if ($action === 'upload' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) jsonResponse(false, 'Please choose a text file.', [], 422);
    $file = $_FILES['file'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if ($ext !== 'txt') jsonResponse(false, 'For this simple version, upload only .txt files.', [], 422);
    $content = file_get_contents($file['tmp_name']);
    if ($content === false || trim($content) === '') jsonResponse(false, 'The file is empty.', [], 422);
    if (mb_strlen($content) > 100000) jsonResponse(false, 'File is too large. Keep it below 100,000 characters.', [], 422);

    $title = pathinfo($file['name'], PATHINFO_FILENAME);
    $stmt = db()->prepare('INSERT INTO materials (user_id,title,content) VALUES (?,?,?)');
    $stmt->execute([$userId, $title, $content]);
    jsonResponse(true, 'Material uploaded.', ['id' => (int)db()->lastInsertId()]);
}

if ($action === 'delete' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = (int)($_POST['id'] ?? 0);
    $stmt = db()->prepare('DELETE FROM materials WHERE id=? AND user_id=?');
    $stmt->execute([$id, $userId]);
    jsonResponse(true, 'Material deleted.');
}

jsonResponse(false, 'Invalid request.', [], 400);
