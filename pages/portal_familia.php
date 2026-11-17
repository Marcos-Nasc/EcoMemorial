<?php
// Não precisa incluir db.php aqui, o index.php já incluiu!
$homenageado_id = isset($_GET['id']) ? (int)$_GET['id'] : 1; 

try {
    $query = "SELECT h.*, p.especie_arvore, p.data_plantio, p.latitude, p.longitude 
              FROM homenageados h 
              LEFT JOIN plantios p ON h.id = p.homenageado_id 
              WHERE h.id = ?";
    $stmt = $pdo->prepare($query); 
    $stmt->execute([$homenageado_id]);
    $dados = $stmt->fetch(PDO::FETCH_ASSOC);

    $query_msg = "SELECT * FROM tributos WHERE homenageado_id = ? AND status = 'aprovado' ORDER BY data_publicacao DESC";
    $stmt_msg = $pdo->prepare($query_msg);
    $stmt_msg->execute([$homenageado_id]);
    $mensagens = $stmt_msg->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) { echo "Erro: " . $e->getMessage(); }
?>

<div class="flex flex-col gap-10">
    
    <header class="flex justify-between items-start">
        <div>
            <h1 class="text-3xl font-serif italic text-eco-dark">Espaço de Memória</h1>
            <p class="text-eco-main/60 text-sm">Um lugar sagrado para honrar quem amamos.</p>
        </div>
    </header>

    <div class="relative h-[450px] rounded-[50px] overflow-hidden shadow-2xl">
        <img src="assets/images/joao.jpg" class="absolute inset-0 w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-t from-eco-dark/90 to-transparent"></div>
        <div class="absolute bottom-12 left-12 text-white">
            <span class="text-xs font-bold tracking-widest text-white/70">EM MEMÓRIA DE</span>
            <h2 class="text-5xl font-serif italic mt-2 mb-4"><?php echo $dados['nome']; ?></h2>
            <div class="flex gap-6 text-sm text-white/80">
                <span class="flex items-center gap-2">📅 <?php echo date('d/m/Y', strtotime($dados['data_plantio'])); ?></span>
                <span class="flex items-center gap-2">🌳 <?php echo $dados['especie_arvore']; ?></span>
                <span class="flex items-center gap-2">📍 <?php echo $dados['latitude'] . ", " . $dados['longitude']; ?></span>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-8 rounded-[35px] border border-eco-border shadow-sm">
            <h3 class="text-[11px] font-bold text-eco-main tracking-widest mb-4 uppercase">Legado Ambiental</h3>
            <p class="text-2xl font-serif italic text-eco-main mb-3">kg de CO2 filtrados</p>
            <p class="text-gray-500 leading-relaxed text-sm">
                Esta árvore continua o legado de <?php echo $dados['nome']; ?>, transformando memórias em ar puro e vida para o ecossistema.
            </p>
        </div>

        <div class="bg-eco-light p-8 rounded-[35px] border border-eco-border flex flex-col justify-center">
            <h3 class="text-[11px] font-bold text-eco-main tracking-widest mb-4 uppercase">Status de Crescimento</h3>
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center shadow-inner">
                    <span class="text-xl">💚</span>
                </div>
                <div>
                    <strong class="block text-xl text-eco-dark font-serif italic">Crescendo</strong>
                    <p class="text-sm text-eco-main/70 font-medium">Monitoramento ativo via satélite</p>
                </div>
            </div>
        </div>
    </div>

    <section class="max-w-4xl mx-auto w-full">
        <div class="flex justify-between items-center mb-8">
            <h3 class="text-3xl font-serif italic text-eco-dark">Mensagens de Carinho</h3>
            <span class="text-[10px] font-bold text-eco-main bg-eco-light px-3 py-1 rounded-full border border-eco-border">
                <?= count($mensagens) ?> MENSAGENS
            </span>
        </div>

        <div class="bg-[#fafffd] border border-eco-border rounded-[35px] p-8 mb-10 shadow-sm">
            <input type="text" placeholder="Seu nome" class="w-full bg-white border border-eco-border rounded-2xl py-4 px-6 mb-4 outline-none focus:ring-2 focus:ring-eco-main/10 transition-all">
            <textarea placeholder="Sua homenagem..." class="w-full bg-white border border-eco-border rounded-2xl py-4 px-6 mb-6 h-32 outline-none focus:ring-2 focus:ring-eco-main/10 transition-all resize-none"></textarea>
            <button class="w-full bg-eco-main hover:bg-eco-dark text-white font-bold py-5 rounded-2xl transition-all flex items-center justify-center gap-3 shadow-lg shadow-eco-main/20 uppercase text-xs tracking-widest">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                Enviar Homenagem
            </button>
        </div>

        <div class="space-y-6">
            <?php foreach($mensagens as $msg): ?>
            <div class="bg-white border border-eco-border rounded-[30px] p-8 relative overflow-hidden group hover:border-eco-main/30 transition-colors">
                <p class="text-xl font-serif italic text-gray-700 mb-6 relative z-10">"<?= htmlspecialchars($msg['mensagem']) ?>"</p>
                <div class="flex justify-between items-center relative z-10">
                    <span class="text-sm font-bold text-eco-main tracking-widest uppercase"><?= htmlspecialchars($msg['nome_autor']) ?></span>
                    <span class="text-xs text-gray-400 font-medium"><?= date('d/m/Y H:i', strtotime($msg['data_publicacao'])) ?></span>
                </div>
                <span class="absolute right-8 top-4 text-8xl text-eco-light font-serif opacity-50 select-none group-hover:text-eco-border transition-colors">"</span>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
</div>

<div class="fixed bottom-8 right-8 bg-white/90 backdrop-blur-md border border-eco-border px-6 py-3 rounded-full shadow-2xl flex items-center gap-3 z-50">
    <span class="flex h-2 w-2 relative">
        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-eco-main opacity-75"></span>
        <span class="relative inline-flex rounded-full h-2 w-2 bg-eco-main"></span>
    </span>
    <span class="text-[10px] font-bold tracking-widest text-eco-dark uppercase">Sincronização Satelital Ativa</span>
</div>

</body>
</html>