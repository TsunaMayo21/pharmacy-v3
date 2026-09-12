<?php
$host = getenv('DB_HOST') ?: 'mysql-18a7f917-pharma-hh-ims.b.aivencloud.com';
$port = getenv('DB_PORT') ?: 27565;
$user = getenv('DB_USER') ?: 'avnadmin';
$pass = getenv('DB_PASS') ?: 'AVNS_6tRQhnGnXNHLrNYnt2W';
$db   = getenv('DB_NAME') ?: 'defaultdb';

// Enable SSL for Aiven connection
$conn = mysqli_init();
$conn->ssl_set(NULL, NULL, NULL, NULL, NULL);
$conn->real_connect($host, $user, $pass, $db, $port, NULL, MYSQLI_CLIENT_SSL);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
