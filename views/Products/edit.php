<h1>Edit Product</h1>


<form action="/products/<?= $product["id"] ?>/update" method="post">
  <?php require "form.php" ?>
</form>
<p><a href="/products/<?= $product["id"] ?>/show">Cancel</a></p>