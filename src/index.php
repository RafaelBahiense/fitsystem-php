<?php
session_start();

if (!isset($_SESSION["userID"])) {
    header("Location: login.html");
    exit();
}
?>

<?php
require_once "database/db_context.php";
$db = new DbContext();
$conn = $db->connect();
?>

<!doctype html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Fitsystem</title>
    <link rel="stylesheet" href="output.css"/>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"
            integrity="sha512-CQBWl4fJHWbryGE+Pc7UAxWMUMNMWzWxF4SQo9CgkJIN1kx6djDQZjh3Y8SZ1d+6I+1zze6Z7kHXO7q3UyZAWw=="
            crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="index.js"></script>
    <script src="menu.js"></script>
</head>

<body>
<div class="flex flex-col min-h-screen">
    <header class="bg-gray-900 text-white py-4 px-6 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                 class="h-6 w-6">
                <path d="m6.5 6.5 11 11"></path>
                <path d="m21 21-1-1"></path>
                <path d="m3 3 1 1"></path>
                <path d="m18 22 4-4"></path>
                <path d="m2 6 4-4"></path>
                <path d="m3 10 7-7"></path>
                <path d="m14 21 7-7"></path>
            </svg>
            <h1 class="text-xl font-bold">Fitsystem</h1>
        </div>
        <nav class="flex items-center gap-4">
            <a class="underline" href="#">
                Home
            </a>
            <a class="hover:underline" href="/fitsystem/clients.php">
                Clientes
            </a>
            <a class="hover:underline" href="/fitsystem/classes.php">
                Aulas
            </a>
            <a class="hover:underline" href="/fitsystem/progress.php">
                Progresso
            </a>
            <div class="relative inline-block text-left">
                <div>
                    <button type="button" id="menu-button">
                        <img src='/fitsystem/placeholder.svg' width='40' height='40' class='rounded-full'
                             alt='Client Avatar' style='aspect-ratio:40/40;object-fit:cover'/>
                    </button>
                </div>
                <div id="menu-toggle" style="display: none">
                    <div class="absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                         role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                        <div class="py-1" role="none">
                            <span id="menu-item-logout" class="text-gray-700 block px-4 py-2 text-sm"
                                  id="menu-item-logout">Logout</span>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    <main class="flex-1 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
        <section class="bg-white rounded-lg shadow-md p-6 space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold">Clientes</h2>
                <a class="text-blue-500 hover:underline" href="/fitsystem/clients.php">
                    Ver todos
                </a>
            </div>
            <div class="space-y-2">
                <?php
                require_once "components/client.row.php";

                try {
                    $results = $conn->query("SELECT * FROM client");
                    foreach ($results as $result) {
                        echo clientRowComponent($result);
                    }
                } catch (PDOException $e) {
                    echo json_encode(["error" => $e->getMessage()]);
                }
                ?>
            </div>
        </section>
        <section class="bg-white rounded-lg shadow-md p-6 space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold">Aulas</h2>
                <a class="text-blue-500 hover:underline" href="/fitsystem/classes.php">
                    Veja todas
                </a>
            </div>
            <div class="space-y-2">
                <?php
                require_once "components/class.row.php";

                try {
                    $results = $conn->query("SELECT * FROM class");
                    foreach ($results as $result) {
                        echo classRowComponent($result, "light");
                    }
                } catch (PDOException $e) {
                    echo json_encode(["error" => $e->getMessage()]);
                }
                ?>
            </div>
        </section>
        <section class="bg-white rounded-lg shadow-md p-6 space-y-4">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold">Progresso do Cliente</h2>
                <a class="text-blue-500 hover:underline" href="/fitsystem/progress.php">
                    Veja os relatórios
                </a>
            </div>
            <div class="space-y-4">
                <div>
                    <h3 class="font-medium mb-2">Teste</h3>
                    <div class="aspect-[4/3]">
                        <div style="width:100%;height:100%">
                            <canvas id="user-chart"></canvas>
                        </div>
                    </div>
                </div>
                <div>
                    <h3 class="font-medium mb-2">Teste 2</h3>
                    <div class="aspect-[4/3]">
                        <div style="width:100%;height:100%">
                            <canvas id="user-chart-2"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</div>
</body>

</html>
