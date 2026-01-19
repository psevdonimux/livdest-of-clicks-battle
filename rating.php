<?php
require_once './php/Clicker.php';
$clicker = new Clicker('');
$ratings = $clicker->getRating();
?>
<!DOCTYPE html>
<html lang="ru" id="color">
<head>
	<meta charset="UTF-8">
	<title>LivDest of clicks battle | Рейтинг</title>
	<meta name="viewport" content="width=device-width, user-scalable=no">
	<link rel="stylesheet" href="css/Rating.css">
	<link rel="icon" href="image/icon.webp">
</head>
<body>
<script src="javascript/Design.js"></script>
<script src="javascript/Rating.js"></script>
<div id="rating">
<?php foreach ($ratings as $row): ?>
<p id="top<?= $row['position'] ?>">
	<?= $row['position'] ?>. <?= htmlspecialchars($row['username']) ?> | <?= number_format($row['clicks'], 0, '', ' ') ?> | <?= number_format($row['bonus'], 0, '', ' ') ?>
</p>
<?php endforeach; ?>
</div>
<script>clickModeRating();</script>
</body>
</html>
