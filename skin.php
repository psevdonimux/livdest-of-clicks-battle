<?php
require_once './php/Session.php';
Session::requireAuth();
$csrfToken = Session::getToken();
?>
<!DOCTYPE html>
<html lang="ru" id="color">
<head>
	<meta charset="UTF-8">
	<title>LivDest of clicks battle | Скины</title>
	<meta name="viewport" content="width=device-width, user-scalable=no">
	<link rel="stylesheet" href="css/Skin.css">
	<link rel="icon" href="image/icon.webp">
</head>
<body>
<script>const csrfToken = '<?= htmlspecialchars($csrfToken) ?>';</script>
<script src="javascript/Design.js"></script>
<script src="javascript/Skin.js"></script>
<p id="page">0/0</p>
<button id="left">&lt;</button>
<button id="right">&gt;</button>
<img id="skin1" onclick="selectSkin(1);" alt="skin">
<img id="skin2" onclick="selectSkin(2);" alt="skin">
<img id="skin3" onclick="selectSkin(3);" alt="skin">
<img id="skin4" onclick="selectSkin(4);" alt="skin">
<img id="skin5" onclick="selectSkin(5);" alt="skin">
<img id="skin6" onclick="selectSkin(6);" alt="skin">
<img id="skin7" onclick="selectSkin(7);" alt="skin">
<img id="skin8" onclick="selectSkin(8);" alt="skin">
<img id="skin9" onclick="selectSkin(9);" alt="skin">
<script>clickMode();</script>
</body>
</html>
