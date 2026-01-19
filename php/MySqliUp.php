<?php
class MySqliUp {
	private ?PDO $pdo = null;
	private string $dbName;
	public function __construct(string $name) {
		$this->dbName = $name;
		if ($name !== '') {
			$this->connect($name);
		}
	}
	private function connect(string $name): void {
		$env = parse_ini_file(__DIR__ . '/../.env');
		$this->pdo = new PDO(
			"mysql:host={$env['DB_HOST']};dbname={$name};charset=utf8mb4",
			$env['DB_USER'],
			$env['DB_PASS'],
			[
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
				PDO::ATTR_EMULATE_PREPARES => false
			]
		);
	}
	public function createDataBase(string $database): void {
		$env = parse_ini_file(__DIR__ . '/../.env');
		$pdo = new PDO(
			"mysql:host={$env['DB_HOST']};charset=utf8mb4",
			$env['DB_USER'],
			$env['DB_PASS']
		);
		$pdo->exec("CREATE DATABASE IF NOT EXISTS `" . preg_replace('/[^a-zA-Z0-9_]/', '', $database) . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
	}
	public function createTable(string $table, string $columns): void {
		$table = preg_replace('/[^a-zA-Z0-9_]/', '', $table);
		$this->pdo->exec("CREATE TABLE IF NOT EXISTS `{$table}` ({$columns}) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
	}
	public function insert(string $table, array $data): void {
		$table = preg_replace('/[^a-zA-Z0-9_]/', '', $table);
		$columns = implode(', ', array_keys($data));
		$placeholders = implode(', ', array_fill(0, count($data), '?'));
		$stmt = $this->pdo->prepare("INSERT INTO `{$table}` ({$columns}) VALUES ({$placeholders})");
		$stmt->execute(array_values($data));
	}
	public function update(string $table, array $data, array $where): void {
		$table = preg_replace('/[^a-zA-Z0-9_]/', '', $table);
		$setParts = [];
		$values = [];
		foreach ($data as $key => $value) {
			$setParts[] = "{$key} = ?";
			$values[] = $value;
		}
		$whereParts = [];
		foreach ($where as $key => $value) {
			$whereParts[] = "{$key} = ?";
			$values[] = $value;
		}
		$sql = "UPDATE `{$table}` SET " . implode(', ', $setParts) . " WHERE " . implode(' AND ', $whereParts);
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute($values);
	}
	public function selectOne(string $table, array $where): ?array {
		$table = preg_replace('/[^a-zA-Z0-9_]/', '', $table);
		$whereParts = [];
		$values = [];
		foreach ($where as $key => $value) {
			$whereParts[] = "{$key} = ?";
			$values[] = $value;
		}
		$sql = "SELECT * FROM `{$table}` WHERE " . implode(' AND ', $whereParts) . " LIMIT 1";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute($values);
		$result = $stmt->fetch();
		return $result ?: null;
	}
	public function selectAll(string $table, ?array $where = null, ?string $orderBy = null): array {
		$table = preg_replace('/[^a-zA-Z0-9_]/', '', $table);
		$sql = "SELECT * FROM `{$table}`";
		$values = [];
		if ($where) {
			$whereParts = [];
			foreach ($where as $key => $value) {
				$whereParts[] = "{$key} = ?";
				$values[] = $value;
			}
			$sql .= " WHERE " . implode(' AND ', $whereParts);
		}
		if ($orderBy) {
			$sql .= " ORDER BY {$orderBy}";
		}
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute($values);
		return $stmt->fetchAll();
	}
	public function selectColumn(string $table, string $column, ?array $where = null): array {
		$table = preg_replace('/[^a-zA-Z0-9_]/', '', $table);
		$column = preg_replace('/[^a-zA-Z0-9_]/', '', $column);
		$sql = "SELECT {$column} FROM `{$table}`";
		$values = [];
		if ($where) {
			$whereParts = [];
			foreach ($where as $key => $value) {
				$whereParts[] = "{$key} = ?";
				$values[] = $value;
			}
			$sql .= " WHERE " . implode(' AND ', $whereParts);
		}
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute($values);
		return $stmt->fetchAll(PDO::FETCH_COLUMN);
	}
	public function exists(string $table, array $where): bool {
		return $this->selectOne($table, $where) !== null;
	}
	public function query(string $sql, array $params = []): array {
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute($params);
		return $stmt->fetchAll();
	}
}
