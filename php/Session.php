<?php
class Session {
	public static function start(): void {
		if (session_status() === PHP_SESSION_NONE) {
			session_start();
		}
	}
	public static function login(string $login, string $username): void {
		self::start();
		session_regenerate_id(true);
		$_SESSION['login'] = $login;
		$_SESSION['username'] = $username;
		$_SESSION['csrf_token'] = self::generateToken();
	}
	public static function logout(): void {
		self::start();
		$_SESSION = [];
		session_destroy();
	}
	public static function isLoggedIn(): bool {
		self::start();
		return isset($_SESSION['login']) && !empty($_SESSION['login']);
	}
	public static function getLogin(): string {
		self::start();
		return $_SESSION['login'] ?? '';
	}
	public static function getUsername(): string {
		self::start();
		return $_SESSION['username'] ?? '';
	}
	public static function generateToken(): string {
		return bin2hex(random_bytes(32));
	}
	public static function getToken(): string {
		self::start();
		if (!isset($_SESSION['csrf_token'])) {
			$_SESSION['csrf_token'] = self::generateToken();
		}
		return $_SESSION['csrf_token'];
	}
	public static function validateToken(string $token): bool {
		self::start();
		return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
	}
	public static function requireAuth(): void {
		if (!self::isLoggedIn()) {
			header('Location: join.php');
			exit;
		}
	}
	public static function requireGuest(): void {
		if (self::isLoggedIn()) {
			header('Location: play.php');
			exit;
		}
	}
	public static function csrfField(): string {
		return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(self::getToken()) . '">';
	}
	public static function validateCsrf(): bool {
		$token = $_POST['csrf_token'] ?? $_GET['csrf_token'] ?? '';
		return self::validateToken($token);
	}
}
