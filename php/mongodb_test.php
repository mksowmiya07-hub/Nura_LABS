<?php

require_once __DIR__ . '/../vendor/autoload.php';

try {

    $client = new MongoDB\Client("mongodb://127.0.0.1:27017");

    $database = $client->selectDatabase("nura_auth_system");

    echo "MongoDB connected successfully!";

} catch (Exception $e) {

    echo "MongoDB connection failed: " . $e->getMessage();

}

?>