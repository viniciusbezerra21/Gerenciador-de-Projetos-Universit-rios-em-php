<?php
$hostname = "127.0.0.1";
$user = "root";
$password = "root";
$database = "cadastro_universidade";

function getConnection() {
    global $hostname, $user, $password, $database;
    
    $conexao = new mysqli($hostname, $user, $password, $database);
    
    if ($conexao->connect_errno) {
        die("Failed to connect to MySQL: " . $conexao->connect_error);
    }
    
    $conexao->set_charset("utf8mb4");
    
    return $conexao;
}
?>
