<?php
declare(strict_types=1);
namespace App\Models;

use PDO;
use App\Database;
class Product
{
    public function __construct(private Database $db){

    }
    public function getData(): array
    {
        $pdo = $this->db->getConnection();
        
        $statement = $pdo->query("SELECT * FROM product");
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}