<?php
$config = require basePath('config/db.php');
$db = new Database($config);

$id = $_GET['id'] ?? '';

$params = [
    'id' => $id
];

// :id is a named placeholder.
// prevents SQL injection, PDO sends the SQL and the data separately to the database engine.
$listing = $db->query('SELECT * FROM listings WHERE id = :id', $params)->fetch();   

loadView('listings/show', [
    'listing' => $listing
]);