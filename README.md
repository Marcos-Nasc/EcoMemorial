# 🌳 EcoMemorial

**Sistema de gestão do Bosque de Memórias.** No EcoMemorial cada homenagem a um ente querido fica ligada a uma árvore plantada. O sistema registra quem é o homenageado, a espécie plantada e a localização exata do plantio (quadra/lote e número), e gera relatórios para a equipe de campo e para a área de ASG.

> 🏆 Reconhecido com o prêmio de Empresa Acolhedora **ACEMBRA/SINCEP** na categoria **Inovação** (2026).

---

## ✨ Funcionalidades

| Módulo | O que faz |
|---|---|
| **Memoriais** | Cadastro e listagem das homenagens, com homenageado, lote e número do plantio |
| **Espécies** | Catálogo das espécies de árvores usadas nos plantios |
| **Relatórios ASG** | Indicadores ambientais, sociais e de governança gerados a partir dos plantios |
| **Configurações** | Backup, relatório de plantios, limpeza de duplicatas e histórico de atividades |

### Configurações e segurança
- **Backup do banco:** gera um dump `.sql` com a estrutura e todos os dados do sistema.
- **Relatório de plantios (.xlsx):** exporta a planilha *Organização dos Plantios* com Nome do Homenageado, Quadra, Número, Status e Observação. As linhas seguem a ordem do campo (A1, B1, C1… A2, B2…) e as colunas Status e Observação saem em branco para a conferência da equipe. Para exportar uma quadra só: `exportar_plantios.php?quadra=70`.
- **Remoção de duplicatas:** apaga cadastros de memoriais idênticos e mantém apenas o primeiro registro.
- **Histórico de atividades:** registra as últimas ações de cada colaborador (exportações, limpezas, alterações).

---

## 🛠️ Tecnologias

- **PHP 8+** com PDO
- **MySQL / MariaDB**
- HTML, CSS e JavaScript puro, sem frameworks
- Extensão **`zip`** do PHP para gerar o `.xlsx`. O sistema não usa Composer nem PhpSpreadsheet.

---

## 🚀 Instalação

1. **Clone o repositório**
   ```bash
   git clone https://github.com/Marcos-Nasc/EcoMemorial.git
   ```

2. **Crie o banco de dados** no MySQL/MariaDB e importe o dump `.sql` do sistema.

3. **Configure a conexão** em `includes/config.php`. Esse arquivo não vai para o repositório, então crie o seu a partir do exemplo:
   ```php
   <?php
   define('BASE_URL', 'http://localhost/ecomemorial/');

   $pdo = new PDO(
       'mysql:host=localhost;dbname=ecomemorial;charset=utf8mb4',
       'usuario',
       'senha',
       [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
   );
   ```

4. **Suba no servidor** (XAMPP, Laragon, Apache ou Nginx com PHP) e acesse a tela de login.

---

## 📊 Relatório de plantios: ajuste ao banco

O arquivo `exportar_plantios.php` busca o nome do homenageado na tabela de memoriais e a quadra e o número na tabela `lote`, por meio de um `INNER JOIN`. Se os nomes das tabelas ou colunas forem diferentes, ajuste o bloco **"AJUSTE AQUI"** no topo do arquivo:

```php
const EXP_TABELA_MEMORIAL = 'memoriais';
const EXP_TABELA_LOTE     = 'lote';
const EXP_FK_LOTE         = 'm.lote_id';
const EXP_PK_LOTE         = 'l.id';
const EXP_COL_NOME        = 'm.nome_homenageado';
const EXP_COL_QUADRA      = 'l.quadra';
const EXP_COL_NUMERO      = 'l.numero';
```

---

## 🔒 Segurança

- Todas as páginas internas exigem sessão de colaborador (`$_SESSION['colaborador_id']`).
- As consultas usam *prepared statements* (PDO).
- As saídas em HTML passam por `htmlspecialchars`.
- **Nunca suba o `includes/config.php`**, porque ele contém as credenciais do banco. Ele já deve estar listado no `.gitignore`.

---

## 👨‍💻 Autor

**Marcos Nascimento** · Desenvolvedor Full Stack, Inovação · Concessionária Reviver
GitHub: [@Marcos-Nasc](https://github.com/Marcos-Nasc)
