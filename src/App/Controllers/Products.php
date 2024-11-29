<?php
declare(strict_types=1);
namespace App\Controllers;

use Framework\Controller;
use Framework\Viewer;
use App\Models\Product;
use Framework\Exceptions\PageNotFoundException;

class Products extends Controller
{
    
    public function __construct(private Product $model)
    {

    }
    public function index()
    {
        $products = $this->model->findAll();

        echo $this->viewer->render('shared/header', ["title" => "Products"]);
        echo $this->viewer->render("products/index", [
            "products" => $products,
            "total" => $this->model->getTotal()
        ]);
        echo $this->viewer->render('shared/footer');
    }
    public function show(string $id)
    {

        $product = $this->getProduct($id);

        echo $this->viewer->render('shared/header', ["title" => "Products"]);
        echo $this->viewer->render("Products/show", ["product" => $product]);
        echo $this->viewer->render('shared/footer');
    }

    public function edit(string $id)
    {

        $product = $this->getProduct($id);

        echo $this->viewer->render('shared/header', ["title" => "Edit Product"]);
        echo $this->viewer->render("Products/edit", ["product" => $product]);
        echo $this->viewer->render('shared/footer');
    }

    public function showPage(string $title, string $id, string $page)
    {

        echo $title . " " . $id . " " . $page;

    }
    public function new()
    {
        echo $this->viewer->render('shared/header', [
            "title" => "New Product"
        ]);
        echo $this->viewer->render("Products/new");
        echo $this->viewer->render('shared/footer');

    }

    public function create()
    {
        $data = [
            "name" => $this->request->post["name"],
            "description" => empty($this->request->post["description"]) ? null : $this->request->post["description"]
        ];
        if ($this->model->insert($data)) {
            header("Location: /products/{$this->model->getInsertID()}/show");
            exit;
        } else {
            echo $this->viewer->render('shared/header', [
                "title" => "New Product"
            ]);
            echo $this->viewer->render("Products/new", [
                'errors' => $this->model->getErrors(),
                'product' => $data
            ]);
            echo $this->viewer->render('shared/footer');
        }

    }

    public function update(string $id)
    {
        $product = $this->getProduct($id);


        $product["name"] = $this->request->post["name"];
        $product["description"] = empty($this->request->post["description"]) ? null : $this->request->post["description"];

        if ($this->model->update($id, $product)) {
            header("Location: /products/{$id}/show");
            exit;
        } else {
            echo $this->viewer->render('shared/header', [
                "title" => "Edit Product",

            ]);
            echo $this->viewer->render("Products/edit", [
                'errors' => $this->model->getErrors(),
                "product" => $product
            ]);
            echo $this->viewer->render('shared/footer');
        }

    }
    public function delete(string $id)
    {
        $product = $this->getProduct($id);

        if ($this->request->server['REQUEST_METHOD'] === "POST") {
            $this->model->delete($id);
            header("Location: /products/index");
            exit;
        }

        echo $this->viewer->render("shared/header", [
            "title" => "Delete Product"
        ]);
        echo $this->viewer->render("Products/delete", [
            "product" => $product
        ]);
    }
    public function destroy(string $id)
    {
        $product = $this->getProduct($id);

        $this->model->delete($id);
        header("Location: /products/index");
        exit;

    }
    private function getProduct(string $id): array
    {
        $product = $this->model->find($id);
        if ($product === false) {
            throw new PageNotFoundException("Product not found");
        }
        return $product;
    }
}