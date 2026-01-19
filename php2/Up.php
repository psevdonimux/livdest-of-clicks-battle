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
$bonus = $_GET['up'] ?? 0;
$price = (int)($_GET['price'] ?? 0);
$currentClicks = $clicker->getClicks();
$currentBonus = $clicker->getBonus();
if ($bonus === 'max') {
	$multiplier = $currentBonus * 10;
	$maxBonus = (int)floor($currentClicks / $multiplier);
	$price = $maxBonus * $multiplier;
	$bonus = $maxBonus;
} else {
	$bonus = (int)$bonus;
}
if ($bonus <= 0 || $price <= 0 || $currentClicks < $price) {
	http_response_code(400);
	echo json_encode(['error' => 'Invalid operation']);
	exit;
}
$expectedPrice = $bonus * $currentBonus * 10;
if ($price !== $expectedPrice) {
	http_response_code(400);
	echo json_encode(['error' => 'Price mismatch']);
	exit;
}
$clicker->reduceClicks($price);
$clicker->addBonus($bonus);
echo json_encode([
	'success' => true,
	'clicks' => $clicker->getClicks(),
	'bonus' => $clicker->getBonus()
]);
