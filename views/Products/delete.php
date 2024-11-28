<h1>Delete Product</h1>


<form action="/products/<?= $product["id"] ?>/destroy" method="post">
  <p>Delete this product?</p>
  <button>Delete</button>
</form>
<p><a href="/products/<?= $product["id"] ?>/show">Cancel</a></p>