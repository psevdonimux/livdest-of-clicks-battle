<?php
require_once __DIR__ . '/../php/Clicker.php';
require_once __DIR__ . '/../php/Session.php';
header('Content-Type: application/json');
if (!Session::isLoggedIn()) {
	http_response_code(401);
	echo json_encode(['error' => 'Unauthorized']);
	exit;
}
$login = Session::getLogin();
$clicker = new Clicker($login);
$rank = $clicker->getMyRating();
$rankSkins = [1 => 'gold.webp', 2 => 'silver.webp', 3 => 'bronze.webp'];
$currentSkin = $clicker->getSkinSelect();
if (in_array($currentSkin, $rankSkins) && (!isset($rankSkins[$rank]) || $rankSkins[$rank] !== $currentSkin)) {
	$clicker->setSkinSelect('click.webp');
	$currentSkin = 'click.webp';
}
$type = $_GET['type'] ?? '';
switch ($type) {
	case 'select':
		echo json_encode(['selected' => $currentSkin, 'rank' => $rank]);
		break;
	case 'list':
		echo json_encode(['skins' => $clicker->getSkins(), 'rank' => $rank]);
		break;
	case 'set':
		$token = $_GET['csrf_token'] ?? '';
		if (!Session::validateToken($token)) {
			http_response_code(403);
			echo json_encode(['error' => 'Invalid CSRF token']);
			exit;
		}
		$skin = $_GET['skin'] ?? '';
		if (in_array($skin, $rankSkins) && !isset($rankSkins[$rank])) {
			http_response_code(403);
			echo json_encode(['error' => 'Rank skin not available']);
			exit;
		}
		if (in_array($skin, $rankSkins) && $rankSkins[$rank] !== $skin) {
			http_response_code(403);
			echo json_encode(['error' => 'Wrong rank skin']);
			exit;
		}
		$allowedSkins = ['click.webp', 'gold.webp', 'silver.webp', 'bronze.webp'];
		if (in_array($skin, $allowedSkins) || in_array($skin, $clicker->getSkins())) {
			$clicker->setSkinSelect($skin);
			echo json_encode(['success' => true, 'selected' => $skin]);
		} else {
			http_response_code(400);
			echo json_encode(['error' => 'Invalid skin']);
		}
		break;
	default:
		http_response_code(400);
		echo json_encode(['error' => 'Invalid type']);
}
