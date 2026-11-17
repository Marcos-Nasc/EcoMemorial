<?php
// includes/db.php
$host = '127.0.0.1';
$db   = 'ecomemorial_v2';
$user = 'root';
$pass = ''; 
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    die("Erro de conexão com o banco de dados: " . $e->getMessage());
}

function calcularTempoCrescimento($dataPlantio) {
    if (!$dataPlantio) return "Data não informada";
    $dataInicio = new DateTime($dataPlantio);
    $dataAtual = new DateTime();
    $diferenca = $dataAtual->diff($dataInicio);

    if ($diferenca->y > 0) return "Crescendo há cerca de {$diferenca->y} ano(s)";
    if ($diferenca->m > 0) return "Crescendo há cerca de {$diferenca->m} mês(es)";
    return "Plantada recentemente";
}
// FIM DO ARQUIVO. Não coloque mais nada aqui.
?>