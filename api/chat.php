<?php
include dirname(__DIR__) . '/config.php';
require_once dirname(__DIR__) . '/includes/chatbot_knowledge.php';

header('Content-Type: application/json');

$message = trim($_POST['message'] ?? $_GET['message'] ?? '');
$userId = isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null;

$reply = chatbot_reply($message, $conn, $userId);

echo json_encode(['reply' => $reply]);
