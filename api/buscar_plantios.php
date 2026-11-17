<?php
// Inclua sua conexão com o banco (ex: $pdo)
require_once '../includes/db.php'; 

header('Content-Type: application/json');

$termo = isset($_GET['q']) ? $_GET['q'] : '';

try {
    // 1. Preparamos a query com o filtro WHERE no nome do homenageado
    $sql = "
        SELECT 
            p.id AS plantio_id, 
            p.lote_numero, 
            h.nome AS homenageado_nome, 
            p.especie_arvore, 
            p.data_plantio
        FROM plantios p
        INNER JOIN homenageados h ON p.homenageado_id = h.id
        WHERE h.nome LIKE :termo
        ORDER BY h.nome ASC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['termo' => '%' . $termo . '%']);
    $plantios = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // 2. Formatação para o JavaScript
    // Adicionamos o cálculo de tempo que o seu JS espera receber
    $dadosFormatados = array_map(function($item) {
        $dataPlantio = new DateTime($item['data_plantio']);
        $hoje = new DateTime();
        $intervalo = $dataPlantio->diff($hoje);
        
        // Exemplo simples: "2 anos e 3 meses" ou "150 dias"
        $item['tempo_crescimento'] = $intervalo->y > 0 
            ? $intervalo->y . " anos de vida" 
            : $intervalo->m . " meses de vida";
            
        return $item;
    }, $plantios);

    echo json_encode([
        'status' => 'sucesso',
        'dados' => $dadosFormatados
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'status' => 'erro',
        'mensagem' => 'Erro ao consultar banco de dados: ' . $e->getMessage()
    ]);
}