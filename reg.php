<?php
require_once './php/Clicker.php';
require_once './php/Session.php';
Session::requireGuest();
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if (!Session::validateCsrf()) {
		$error = 'Ошибка безопасности. Обновите страницу.';
	} else {
		$username = trim($_POST['username'] ?? '');
		$login = trim($_POST['login'] ?? '');
		$password = $_POST['password'] ?? '';
		$preg = '/^[a-zа-яё0-9.:,;\'"()+\-÷×=%*#?!^<>\/\\\\|[\]{}_$€@ ]+$/iu';
		$maxSymbol = 16;
		$minSymbol = 3;
		if ($username === '' || $login === '' || $password === '') {
			$error = 'Заполните все поля для регистрации';
		} elseif (!preg_match($preg, $username) || !preg_match($preg, $login) || !preg_match($preg, $password)) {
			$error = 'Разрешены символы: русский, английский, цифры, пробел и .:,;\'"()+-÷×=%*#?!^<>/\\|[]{}_$€@';
		} elseif (mb_strlen($username) < $minSymbol || mb_strlen($login) < $minSymbol || mb_strlen($password) < $minSymbol) {
			$error = 'Минимальная длина каждого поля: ' . $minSymbol . ' символов';
		} elseif (mb_strlen($username) > $maxSymbol || mb_strlen($login) > $maxSymbol || mb_strlen($password) > $maxSymbol) {
			$error = 'Максимальная длина каждого поля: ' . $maxSymbol . ' символов';
		} else {
			$clicker = new Clicker($login);
			if ($clicker->existsLogin()) {
				$error = 'Указанный логин уже занят';
			} elseif ($clicker->existsUsername($username)) {
				$error = 'Указанный псевдоним уже занят';
			} else {
				$clicker->register($username, $password);
				Session::login($login, $username);
				header('Location: play.php');
				exit;
			}
		}
	}
}
?>
<!DOCTYPE html>
<html lang="ru" id="color">
<head>
	<meta charset="UTF-8">
	<title>LivDest of clicks battle | Регистрация</title>
	<meta name="viewport" content="width=device-width, user-scalable=no">
	<link rel="stylesheet" href="css/Reg.css">
	<link rel="icon" href="image/icon.webp">
</head>
<body>
<script src="javascript/Design.js"></script>
<script src="javascript/Reg.js"></script>
<form method="POST" action="reg.php">
	<?= Session::csrfField() ?>
	<input id="username" name="username" type="text" placeholder="Введите псевдоним" maxlength="16" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
	<input id="login" name="login" type="text" placeholder="Введите логин" maxlength="16" value="<?= htmlspecialchars($_POST['login'] ?? '') ?>">
	<input id="password" name="password" type="password" placeholder="Введите пароль" maxlength="16">
	<input id="submit" type="submit" value="Регистрация">
</form>
<a href="join.php"><button type="button" id="join">Вход</button></a>
<?php if ($error): ?>
<p class="message error"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>
<script>clickModeReg();</script>
</body>
</html>
