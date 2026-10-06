<?php

$host = "192.168.10.37";
$dbname = "manutencao";
$user = "postgres";
$password = "nick2509";

try {
    $pdo = new PDO(
        "pgsql:host=$host;dbname=$dbname",
        $user,
        $password
    );

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    header('Content-Type: application/json');
    http_response_code(500);

    echo json_encode([
        "erro" => "Erro na conexão com o banco"
    ]);

    exit;
}
