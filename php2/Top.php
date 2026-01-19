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
echo json_encode(['rank' => $rank, 'skin' => $currentSkin]);
