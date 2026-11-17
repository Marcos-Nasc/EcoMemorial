<?php

$sql = "
    SELECT 
        p.id AS plantio_id, 
        p.lote_numero, 
        h.nome AS homenageado_nome, 
        p.especie_arvore, 
        p.data_plantio
    FROM plantios p
    INNER JOIN homenageados h ON p.homenageado_id = h.id
    ORDER BY p.data_plantio DESC
    LIMIT 20
";

try {
    $stmt = $pdo->query($sql);
    $plantiosDB = $stmt->fetchAll();
} catch (PDOException $e) {
    $plantiosDB = [];
}
?>

<main class="flex-1 overflow-y-auto p-6 relative">
    
    <header class="mb-8">
        <h1 class="text-4xl font-serif italic text-eco-dark mb-2 leading-tight">Acompanhamento do Bosque</h1>
        <p class="text-gray-400 text-sm">Acompanhe o desenvolvimento e os cuidados com o memorial da sua família.</p>
    </header>

    <div class="bg-white p-6 rounded-3xl border border-eco-border mb-8 flex flex-col md:flex-row items-start md:items-center justify-between shadow-sm gap-4 md:gap-0">
        <div class="w-full">
            <h2 class="text-xl md:text-2xl font-serif italic text-eco-dark mb-1">Localizar Memorial</h2>
            <p class="text-sm text-gray-400">Encontre a localização exata no bosque e o status de crescimento.</p>
        </div>
        <div class="relative w-full md:w-[22rem] shrink-0">
            <input type="text" id="inputPesquisa" placeholder="Nome do homenageado..." class="w-full bg-eco-light text-eco-main rounded-2xl py-3 px-5 pl-11 outline-none placeholder:text-eco-main/50 font-medium text-sm transition-all focus:ring-2 focus:ring-eco-main/20">
            <svg class="w-4 h-4 text-eco-main absolute left-4 top-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </div>
    </div>

    <div id="gridMemoriais" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 mb-10">
        
        <?php if (empty($plantiosDB)): ?>
            <div class="col-span-full text-center py-10 text-gray-500">Nenhum memorial encontrado.</div>
        <?php else: ?>
            <?php foreach ($plantiosDB as $plantio): ?>
                <div class="bg-white p-6 rounded-3xl border border-eco-border flex flex-col justify-between shadow-sm hover:shadow-md transition-shadow">
                    <div>
                        <div class="flex justify-between items-start mb-5">
                            <div>
                                <span class="text-[9px] font-bold text-eco-main uppercase tracking-widest block mb-1">Localização</span>
                                <div class="text-eco-dark font-bold text-xl flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-eco-main" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    <?= htmlspecialchars($plantio['lote_numero']) ?>
                                </div>
                            </div>
                            <div class="w-10 h-10 bg-eco-light rounded-xl flex items-center justify-center text-eco-main">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19h14M12 15v4m0-11a5 5 0 00-5 5v1h10v-1a5 5 0 00-5-5zM12 3L8 7h8l-4-4z"></path></svg>
                            </div>
                        </div>
                        
                        <h3 class="font-serif italic text-2xl text-eco-dark mb-1"><?= htmlspecialchars($plantio['homenageado_nome']) ?></h3>
                        <p class="text-xs text-gray-400 mb-5"><?= htmlspecialchars($plantio['especie_arvore']) ?></p>
                        
                        <div class="flex items-center gap-2 text-xs text-gray-400 mb-5 font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <?= calcularTempoCrescimento($plantio['data_plantio']) ?>
                        </div>
                    </div>
                    
                    <a href="index.php?page=portal_familia&id=<?= $plantio['plantio_id'] ?>" class="w-full bg-eco-light text-eco-main text-[13px] font-semibold py-3 rounded-xl flex justify-between items-center px-5 hover:bg-[#e2f5ec] transition-colors">
                        Acessar Memorial
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <div class="bg-eco-dark text-white p-8 rounded-3xl overflow-hidden flex flex-col md:flex-row items-start md:items-center justify-between shadow-lg mb-16 md:mb-0">
        <div class="relative z-10 w-full md:w-3/4 lg:w-2/3">
            <div class="inline-flex items-center gap-2 bg-white/10 text-[10px] font-bold px-3 py-1.5 rounded-full mb-3 text-green-300 tracking-wider">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                TRANSPARÊNCIA E CUIDADO
            </div>
            <h2 class="text-2xl font-serif italic mb-2">Diário de Crescimento</h2>
            <p class="text-green-100/70 text-sm leading-relaxed">
                Registramos as coordenadas exatas de cada plantio e realizamos manutenções periódicas. Acesse o memorial para ver a linha do tempo fotográfica e as últimas ações de zeladoria da nossa equipe.
            </p>
        </div>
    </div>

</main>

<div class="fixed bottom-4 right-4 md:bottom-8 md:right-8 z-50 bg-white/85 backdrop-blur-md border border-eco-border/50 px-5 py-2.5 rounded-full text-[9px] md:text-[10px] font-bold tracking-widest flex items-center gap-2 md:gap-3 text-eco-dark shadow-xl">
    <span class="relative flex h-2.5 w-2.5">
        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-eco-main opacity-75"></span>
        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-eco-main"></span>
    </span>
    <span class="hidden sm:inline">MONITORAMENTO ATIVO</span>
    <span class="sm:hidden">MONITORAMENTO</span>
</div>