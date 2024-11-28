<?php
declare(strict_types=1);
namespace Framework;

use PDO;
use App\Database;
abstract class Model
{
    protected $table;
    protected array $errors = [];
    private function getTable(): string
    {
        if ($this->table !== null) {
            return $this->table;
        }
        $parts = explode("\\", $this::class);
        return strtolower(array_pop($parts));
    }

    public function __construct(protected Database $db)
    {

    }

    public function getInsertID(): string
    {
        $pdo = $this->db->getConnection();
        return $pdo->lastInsertId();
    }
    public function findAll(): array
    {
        $pdo = $this->db->getConnection();

        $sql = "SELECT * FROM {$this->getTable()}";

        $statement = $pdo->query($sql);
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(string $id): array|bool
    {
        $conn = $this->db->getConnection();
        $sql = "SELECT * FROM {$this->getTable()} WHERE id=:id";
        $statement = $conn->prepare($sql);
        $statement->bindValue(":id", $id, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetch(PDO::FETCH_ASSOC);

    }

    public function insert(array $data): bool
    {
        $this->validate($data);
        if (!empty($this->errors)) {
            return false;
        }

        $columns = implode(", ", array_keys($data));
        $placeholders = implode(", ", array_fill(0, count($data), "?"));


        $sql = "INSERT INTO {$this->getTable()} ($columns) VALUES($placeholders)";

        $conn = $this->db->getConnection();

        $statement = $conn->prepare($sql);
        $index = 1;
        foreach ($data as $value) {
            $type = match (gettype($value)) {
                "boolean" => PDO::PARAM_BOOL,
                "integer" => PDO::PARAM_INT,
                "NULL" => PDO::PARAM_NULL,
                default => PDO::PARAM_STR
            };
            $statement->bindValue($index++, $value, $type);
        }


        return $statement->execute();
    }

    public function update(string $id, array $data): bool
    {
        $this->validate($data);
        if (!empty($this->errors)) {
            return false;
        }
        $sql = "UPDATE {$this->getTable()} ";
        unset($data["id"]);
        $assignments = array_keys($data);
        array_walk($assignments, function (&$value) {
            $value = "$value = ?";
        });

        $sql .= " SET " . implode(", ", $assignments);
        $sql .= " WHERE id = ?";

        $conn = $this->db->getConnection();

        $statement = $conn->prepare($sql);
        $index = 1;
        foreach ($data as $value) {
            $type = match (gettype($value)) {
                "boolean" => PDO::PARAM_BOOL,
                "integer" => PDO::PARAM_INT,
                "NULL" => PDO::PARAM_NULL,
                default => PDO::PARAM_STR
            };
            $statement->bindValue($index++, $value, $type);
        }

        $statement->bindValue($index, $id, PDO::PARAM_INT);

        return $statement->execute();
    }

    public function delete(string $id): bool
    {
        $sql = "DELETE FROM {$this->getTable()}
                WHERE id=:id";
        $con = $this->db->getConnection();
        $statement = $con->prepare($sql);
        $statement->bindValue(":id", $id, PDO::PARAM_INT);
        return $statement->execute();
    }

    protected function validate(array $data): void
    {

    }

    protected function addError(string $field, string $message): void
    {
        $this->errors[$field] = $message;
    }
    public function getErrors(): array
    {
        return $this->errors;
    }
}