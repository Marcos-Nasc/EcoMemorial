document.addEventListener('DOMContentLoaded', () => {
    const inputPesquisa = document.getElementById('inputPesquisa');
    const gridMemoriais = document.getElementById('gridMemoriais');
    let timer;

    inputPesquisa.addEventListener('input', (e) => {
        const termo = e.target.value;
        clearTimeout(timer);
        timer = setTimeout(() => {
            buscarMemoriais(termo);
        }, 300);
    });

    async function buscarMemoriais(termo) {
        try {
            // Mostra um estado de "carregando" (opcional)
            gridMemoriais.style.opacity = '0.5';

            const resposta = await fetch(`api/buscar_plantios.php?q=${encodeURIComponent(termo)}`);
            const json = await resposta.json();

            gridMemoriais.style.opacity = '1';

            if (json.status === 'sucesso') {
                renderizarCards(json.dados);
            } else {
                console.error(json.mensagem);
            }
        } catch (error) {
            console.error('Erro na requisição:', error);
        }
    }

    function renderizarCards(plantios) {
        gridMemoriais.innerHTML = ''; 

        if (plantios.length === 0) {
            gridMemoriais.innerHTML = `
                <div class="col-span-full text-center py-20">
                    <p class="text-gray-500 font-medium">Nenhum memorial encontrado com esse nome.</p>
                </div>`;
            return;
        }

        plantios.forEach(plantio => {
            const cardHTML = `
                <div class="bg-white p-6 rounded-[2rem] border border-eco-border flex flex-col justify-between hover:shadow-md transition-all">
                    <div>
                        <div class="flex justify-between items-start mb-6">
                            <div>
                                <span class="text-[9px] font-bold text-eco-main uppercase tracking-widest block mb-1">Localização</span>
                                <div class="text-eco-dark font-bold text-xl flex items-center gap-1.5">
                                    <svg class="w-5 h-5 text-eco-main" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    ${plantio.lote_numero}
                                </div>
                            </div>
                            <div class="w-10 h-10 bg-eco-light rounded-2xl flex items-center justify-center text-eco-main">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19h14M12 15v4m0-11a5 5 0 00-5 5v1h10v-1a5 5 0 00-5-5zM12 3L8 7h8l-4-4z"></path></svg>
                            </div>
                        </div>
                        
                        <h3 class="font-serif italic text-2xl text-eco-dark mb-1">${plantio.homenageado_nome}</h3>
                        <p class="text-[13px] text-gray-400 mb-6">${plantio.especie_arvore}</p>
                        
                        <div class="flex items-center gap-2 text-[12px] text-gray-400 mb-6 font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            ${plantio.tempo_crescimento}
                        </div>
                    </div>
                    
                    <a href="memorial_detalhe.php?id=${plantio.plantio_id}" class="w-full bg-eco-light text-eco-main text-sm font-semibold py-4 rounded-2xl flex justify-between items-center px-5 hover:bg-[#e2f5ec] transition-colors">
                        Acessar Diário
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                </div>
            `;
            gridMemoriais.insertAdjacentHTML('beforeend', cardHTML);
        });
    }
});