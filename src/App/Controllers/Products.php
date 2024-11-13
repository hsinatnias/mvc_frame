<?php
declare(strict_types=1);
namespace App\Controllers;

use App\Models\Product;
use Framework\Viewer;

class Products
{
    public function __construct(private Viewer $viewer, private Product $model)
    {

    }
    public function index()
    {
       



        $products = $this->model->getData();


        echo $this->viewer->render('shared/header', ["title" => "Products"]);
        echo $this->viewer->render("products/index",[
            "products" => $products
        ]);
        echo $this->viewer->render('shared/footer');
    }
    public function show(string $id){


        echo $this->viewer->render('shared/header', ["title" => "Products"]);
        echo $this->viewer->render("Products/show", ["id" => $id]);
        echo $this->viewer->render('shared/footer');
    }

    public function showPage(string $title, string $id, string $page){

        echo $title. " ". $id. " ". $page;

    }
}