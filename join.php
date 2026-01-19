<?php
require_once './php/Clicker.php';
require_once './php/Session.php';
Session::requireGuest();
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if (!Session::validateCsrf()) {
		$error = 'Ошибка безопасности. Обновите страницу.';
	} else {
		$login = trim($_POST['login'] ?? '');
		$password = $_POST['password'] ?? '';
		if ($login === '' || $password === '') {
			$error = 'Заполните все поля';
		} else {
			$clicker = new Clicker($login);
			if ($clicker->verifyPassword($password)) {
				Session::login($login, $clicker->getUsername());
				header('Location: play.php');
				exit;
			} else {
				$error = 'Неверный логин или пароль';
			}
		}
	}
}
?>
<!DOCTYPE html>
<html lang="ru" id="color">
<head>
	<meta charset="UTF-8">
	<title>LivDest of clicks battle | Вход</title>
	<meta name="viewport" content="width=device-width, user-scalable=no">
	<link rel="stylesheet" href="css/Join.css">
	<link rel="icon" href="image/icon.webp">
</head>
<body>
<script src="javascript/Design.js"></script>
<script src="javascript/Join.js"></script>
<form id="form" method="POST" action="join.php">
	<?= Session::csrfField() ?>
	<input id="login" name="login" type="text" placeholder="Введите логин" value="<?= htmlspecialchars($_POST['login'] ?? '') ?>">
	<input id="password" name="password" type="password" placeholder="Введите пароль">
	<input id="submit" type="submit" value="Вход">
</form>
<a href="reg.php"><button type="button" id="reg">Регистрация</button></a>
<?php if ($error): ?>
<p class="message error"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>
<script>clickModeJoin();</script>
</body>
</html>
