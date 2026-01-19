<?php
require_once __DIR__ . '/MySqliUp.php';
require_once __DIR__ . '/Session.php';
class Clicker {
	private string $login;
	private string $dbName = 'data';
	private MySqliUp $sql;
	private static bool $tablesCreated = false;
	public function __construct(string $login) {
		$this->login = strtolower(trim($login));
		$this->initDatabase();
	}
	private function initDatabase(): void {
		if (!self::$tablesCreated) {
			(new MySqliUp(''))->createDataBase($this->dbName);
			self::$tablesCreated = true;
		}
		$this->sql = new MySqliUp($this->dbName);
		$this->createTables();
	}
	private function createTables(): void {
		$this->sql->createTable('Registered', 'Username VARCHAR(50) NOT NULL, Login VARCHAR(50) NOT NULL UNIQUE, Password VARCHAR(255) NOT NULL');
		$this->sql->createTable('Play', 'Login VARCHAR(50) NOT NULL UNIQUE, Clicks BIGINT DEFAULT 0, Bonus INT DEFAULT 1');
		$this->sql->createTable('Skin', 'Login VARCHAR(50) NOT NULL UNIQUE, Selected VARCHAR(100) DEFAULT "click.webp", Skin TEXT');
	}
	public function existsLogin(): bool {
		return $this->sql->exists('Registered', ['Login' => $this->login]);
	}
	public function existsUsername(string $username): bool {
		return $this->sql->exists('Registered', ['Username' => $username]);
	}
	public function getLogin(): string {
		return $this->login;
	}
	public function getUsername(): string {
		$result = $this->sql->selectOne('Registered', ['Login' => $this->login]);
		return $result['Username'] ?? '';
	}
	public function getPasswordHash(): string {
		$result = $this->sql->selectOne('Registered', ['Login' => $this->login]);
		return $result['Password'] ?? '';
	}
	public function verifyPassword(string $password): bool {
		$hash = $this->getPasswordHash();
		return $hash && password_verify($password, $hash);
	}
	public function getClicks(): int {
		$result = $this->sql->selectOne('Play', ['Login' => $this->login]);
		return (int)($result['Clicks'] ?? 0);
	}
	public function getBonus(): int {
		$result = $this->sql->selectOne('Play', ['Login' => $this->login]);
		return (int)($result['Bonus'] ?? 1);
	}
	public function setBonus(int $value): void {
		$this->sql->update('Play', ['Bonus' => $value], ['Login' => $this->login]);
	}
	public function addBonus(int $value): void {
		$this->setBonus($this->getBonus() + $value);
	}
	public function setClicks(int $value): void {
		$this->sql->update('Play', ['Clicks' => $value], ['Login' => $this->login]);
	}
	public function addClicks(): void {
		$this->setClicks($this->getClicks() + $this->getBonus());
	}
	public function reduceClicks(int $value): void {
		$newClicks = max(0, $this->getClicks() - $value);
		$this->setClicks($newClicks);
	}
	public function register(string $username, string $password): bool {
		if ($this->existsLogin() || $this->existsUsername($username)) {
			return false;
		}
		$hash = password_hash($password, PASSWORD_DEFAULT);
		$this->sql->insert('Registered', [
			'Username' => $username,
			'Login' => $this->login,
			'Password' => $hash
		]);
		$this->sql->insert('Play', [
			'Login' => $this->login,
			'Clicks' => 0,
			'Bonus' => 1
		]);
		$this->sql->insert('Skin', [
			'Login' => $this->login,
			'Selected' => 'click.webp',
			'Skin' => serialize(['click.webp'])
		]);
		return true;
	}
	public function getRating(int $limit = 10): array {
		$rows = $this->sql->query(
			"SELECT r.Username, p.Clicks, p.Bonus FROM Play p JOIN Registered r ON p.Login = r.Login ORDER BY CAST(p.Clicks AS UNSIGNED) DESC LIMIT ?",
			[$limit]
		);
		$result = [];
		$position = 1;
		foreach ($rows as $row) {
			$result[] = [
				'position' => $position,
				'username' => $row['Username'],
				'clicks' => (int)$row['Clicks'],
				'bonus' => (int)$row['Bonus']
			];
			$position++;
		}
		return $result;
	}
	public function getMyRating(): int {
		if (!$this->existsLogin()) {
			return 0;
		}
		$result = $this->sql->query(
			"SELECT COUNT(*) + 1 as rank FROM Play WHERE CAST(Clicks AS UNSIGNED) > (SELECT CAST(Clicks AS UNSIGNED) FROM Play WHERE Login = ?)",
			[$this->login]
		);
		return $result ? (int)$result[0]['rank'] : 0;
	}
	public function getSkinSelect(): string {
		$result = $this->sql->selectOne('Skin', ['Login' => $this->login]);
		return $result['Selected'] ?? 'click.webp';
	}
	public function setSkinSelect(string $skin): void {
		$this->sql->update('Skin', ['Selected' => $skin], ['Login' => $this->login]);
	}
	public function getSkins(): array {
		$result = $this->sql->selectOne('Skin', ['Login' => $this->login]);
		if (!$result || empty($result['Skin'])) {
			return ['click.webp'];
		}
		$skins = @unserialize($result['Skin']);
		return is_array($skins) ? $skins : ['click.webp'];
	}
	public function setSkins(array $skins): void {
		$this->sql->update('Skin', ['Skin' => serialize($skins)], ['Login' => $this->login]);
	}
	public function addSkin(string $skin): void {
		$skins = $this->getSkins();
		if (!in_array($skin, $skins)) {
			$skins[] = $skin;
			$this->setSkins($skins);
		}
	}
	public function removeSkin(string $skin): void {
		$skins = $this->getSkins();
		$skins = array_filter($skins, fn($s) => $s !== $skin);
		$this->setSkins(array_values($skins));
	}
}
