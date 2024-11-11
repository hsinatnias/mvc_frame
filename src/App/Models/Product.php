<?php
namespace App\Models;

use PDO;
class Product
{
    public function getData(): array
    {
        $dsn = "mysql:host=localhost;dbname=product_db;charset=utf8;port=3306";
        $pdo = new PDO($dsn, 'root', '', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);

        $statement = $pdo->query("SELECT * FROM product");
        return $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}