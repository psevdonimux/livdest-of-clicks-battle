<?php
require_once './php/Clicker.php';
require_once './php/Session.php';
Session::requireAuth();
$login = Session::getLogin();
$clicker = new Clicker($login);
$clicks = $clicker->getClicks();
$bonus = $clicker->getBonus();
$csrfToken = Session::getToken();
$rank = $clicker->getMyRating();
$rankSkins = [1 => 'gold.webp', 2 => 'silver.webp', 3 => 'bronze.webp'];
$skin = $clicker->getSkinSelect();
if (in_array($skin, $rankSkins) && (!isset($rankSkins[$rank]) || $rankSkins[$rank] !== $skin)) {
	$clicker->setSkinSelect('click.webp');
	$skin = 'click.webp';
}
?>
<!DOCTYPE html>
<html lang="ru" id="color">
<head>
	<meta charset="UTF-8">
	<title>LivDest of clicks battle | Игра</title>
	<meta name="viewport" content="width=device-width, user-scalable=no">
	<link rel="stylesheet" href="css/Play.css">
	<link rel="icon" href="image/icon.webp">
</head>
<body>
<script src="javascript/Design.js"></script>
<script>
const gameData = {
	clicks: <?= (int)$clicks ?>,
	bonus: <?= (int)$bonus ?>,
	csrfToken: '<?= htmlspecialchars($csrfToken) ?>'
};
</script>
<script src="javascript/Play.js"></script>
<p id="count">Клики: <?= number_format($clicks, 0, '', ' ') ?></p>
<p id="bonus">Бонус: <?= number_format($bonus, 0, '', ' ') ?></p>
<button id="up1" onclick="up(1);">up1</button>
<button id="up10" onclick="up(10);">up10</button>
<button id="up100" onclick="up(100);">up100</button>
<button id="upMax" onclick="up('max');">upMax</button>
<img id="loader" alt="">
<img id="click" onclick="clickOn();" alt="click">
<script>
const design = new Design();
const skinColors = {'gold.webp': '#FFD700', 'silver.webp': '#C0C0C0', 'bronze.webp': '#CD7F32'};
let skin = '<?= $skin ?>';
let skin2 = '<?= str_replace('.webp', '2.webp', $skin) ?>';
let color = skinColors[skin] || design.getColorMode('#FFFFFF', '#323338');
let backgroundColor = design.getColorMode('#FFFFFF', '#323338', true);
clickMode2();
</script>
</body>
</html>
