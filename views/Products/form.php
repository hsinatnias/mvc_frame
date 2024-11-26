<label for="name">Name</label>
<input type="text" name="name" id="name">
<?php if (isset($errors["name"])): ?>
    <p><?= $errors["name"] ?></p>
<?php endif; ?>
<label for="description">Description</label>
<textarea name="description" id="description"></textarea>
<button>Save</button>