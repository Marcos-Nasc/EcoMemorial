<?php 
require_once 'includes/db.php'; 

// Garante que a variável de conexão seja a mesma em todo o sistema
if (!isset($conn) && isset($pdo)) { $conn = $pdo; }

// Captura a página da URL. Se não existir, o padrão é 'home'
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EcoMemorial Reviver</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="assets/js/main.js" defer></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        eco: {
                            main: '#008560',
                            dark: '#014d3a',
                            light: '#f0faf5',
                            border: '#e2f5ec',
                            text: '#2d3748',
                            muted: '#718096'
                        }
                    },
                    fontFamily: {
                        serif: ['Georgia', 'serif'],
                    }
                }
            }
        }
    </script>
</head>
<body class="flex flex-col md:flex-row h-screen overflow-hidden bg-white text-eco-text">

    <?php include 'includes/sidebar.php'; ?>

    <main class="flex-1 overflow-y-auto bg-[#fcfdfc] p-4 md:p-24">
        <div class="max-w-[1400px] mx-auto">
            <?php 
                if ($page === 'portal_familia') {
                    include 'pages/portal_familia.php';
                } else {
                    include 'pages/home.php';
                }
            ?>
        </div>
    </main>

</body>
</html>