<?php
require_once __DIR__ . '/../php/Clicker.php';
require_once __DIR__ . '/../php/Session.php';
header('Content-Type: application/json');
if (!Session::isLoggedIn()) {
	http_response_code(401);
	echo json_encode(['error' => 'Unauthorized']);
	exit;
}
$token = $_GET['csrf_token'] ?? '';
if (!Session::validateToken($token)) {
	http_response_code(403);
	echo json_encode(['error' => 'Invalid CSRF token']);
	exit;
}
$login = Session::getLogin();
$clicker = new Clicker($login);
$clicker->addClicks();
echo json_encode([
	'success' => true,
	'clicks' => $clicker->getClicks(),
	'bonus' => $clicker->getBonus()
]);
