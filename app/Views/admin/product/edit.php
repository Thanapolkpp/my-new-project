<?php
// Since both Add and Edit share the same UI with dynamic PHP properties, we can simply include the same form.
// The variables $product has already been fetched by ProductController::edit($id).
require_once __DIR__ . '/add_preview.php';
