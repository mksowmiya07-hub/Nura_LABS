<?php

require_once __DIR__ . '/../vendor/autoload.php';

try {

    $redis = new Predis\Client([
        'scheme' => 'tcp',
        'host'   => '127.0.0.1',
        'port'   => 6379
    ]);

    $response = $redis->ping();

    echo "Redis connected successfully!";

} catch (Exception $e) {

    echo "Redis connection failed: " . $e->getMessage();

}

?>