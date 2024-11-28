<?php
declare(strict_types=1);
namespace App\Models;

use Framework\Model;
use PDO;

class Product extends Model
{
    protected $table = "product";





    protected function validate(array $data): void
    {
        if (empty($data['name'])) {
            $this->addError('name', 'Name is required');
        }


    }

    public function getTotal(): int
    {
        $sql = "SELECT COUNT(*) AS total FROM product";
        $conn = $this->db->getConnection();
        $statement = $conn->query($sql);
        $row = $statement->fetch(PDO::FETCH_ASSOC);
        return (int) $row["total"];
    }


}