<?php
session_start();

if (!isset($_SESSION["userID"])) {
    header("Location: login.html");
    exit();
}
?>

<!doctype html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Fitsystem</title>
    <link rel="stylesheet" href="output.css" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js" integrity="sha512-CQBWl4fJHWbryGE+Pc7UAxWMUMNMWzWxF4SQo9CgkJIN1kx6djDQZjh3Y8SZ1d+6I+1zze6Z7kHXO7q3UyZAWw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="index.js" ></script>
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
                <a class="hover:underline" href="/clients.php">
                    Clientes
                </a>
                <a class="hover:underline" href="/classes.php">
                    Aulas
                </a>
                <a class="hover:underline" href="/progress.php">
                    Progresso
                </a>
            </nav>
        </header>
        <main class="flex-1 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-6">
            <section class="bg-white rounded-lg shadow-md p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-bold">Clientes</h2>
                    <a class="text-blue-500 hover:underline" href="#">
                        Ver todos
                    </a>
                </div>
                <div class="space-y-2">
                <?php
                require "database.php";

                try {
                    $stmt = $conn->query("SELECT * FROM client");
                    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($tasks as $task) {
                        echo "<div class='flex items-center justify-between'>";
                        echo "<div class='flex items-center gap-2'>";
                        echo "<img src='/fitsystem/placeholder.svg' width='40' height='40' class='rounded-full' alt='Client Avatar' style='aspect-ratio:40/40;object-fit:cover' />";
                        echo "<div>";
                        echo "<h3 class='font-medium'>" .
                            $task["name"] .
                            "</h3>";
                        echo "<p class='text-gray-500 text-sm'>Membro Ativo</p>";
                        echo "</div>";
                        echo "</div>";
                        echo "<div class='flex items-center gap-1 text-green-500'>";
                        echo "<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='h-4 w-4'>";
                        echo "<polyline points='20 6 9 17 4 12'></polyline>";
                        echo "</svg>";
                        echo "<span>85% Progresso</span>";
                        echo "</div>";
                        echo "</div>";
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
                    <a class="text-blue-500 hover:underline" href="#">
                        Veja todas
                    </a>
                </div>
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="h-6 w-6 text-gray-500">
                                <path d="m6.5 6.5 11 11"></path>
                                <path d="m21 21-1-1"></path>
                                <path d="m3 3 1 1"></path>
                                <path d="m18 22 4-4"></path>
                                <path d="m2 6 4-4"></path>
                                <path d="m3 10 7-7"></path>
                                <path d="m14 21 7-7"></path>
                            </svg>
                            <div>
                                <h3 class="font-medium">Treino de força</h3>
                                <p class="text-gray-500 text-sm">Segunda, 18:00</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-1 text-green-500">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="h-4 w-4">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                            <span>15/20 Inscritos</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="h-6 w-6 text-gray-500">
                                <path d="M22 12h-4l-3 9L9 3l-3 9H2"></path>
                            </svg>
                            <div>
                                <h3 class="font-medium">Yoga</h3>
                                <p class="text-gray-500 text-sm">Quarta, 19:00</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-1 text-yellow-500">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="h-4 w-4">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                <circle cx="9" cy="7" r="4"></circle>
                                <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                            </svg>
                            <span>10/20 Inscritos</span>
                        </div>
                    </div>
                </div>
            </section>
            <section class="bg-white rounded-lg shadow-md p-6 space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-bold">Progresso do Cliente</h2>
                    <a class="text-blue-500 hover:underline" href="#">
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
                            <canvas id="user-chart-2"></canvas></div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>
</body>

</html>