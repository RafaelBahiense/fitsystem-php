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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"
    integrity="sha512-CQBWl4fJHWbryGE+Pc7UAxWMUMNMWzWxF4SQo9CgkJIN1kx6djDQZjh3Y8SZ1d+6I+1zze6Z7kHXO7q3UyZAWw=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="progress.js"></script>
  <script src="menu.js"></script>
</head>

<body>
  <div class="flex flex-col min-h-screen">
    <header class="bg-gray-900 text-white py-4 px-6 flex items-center justify-between">
      <div class="flex items-center gap-4">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6">
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
        <a class="hover:underline" href="/fitsystem/">
          Home
        </a>
        <a class="hover:underline" href="/fitsystem/clients.php">
          Clientes
        </a>
        <a class="hover:underline" href="/fitsystem/classes.php">
          Aulas
        </a>
        <a class="underline" href="#">
          Progresso
        </a>
        <div class="relative inline-block text-left">
          <div>
            <button type="button" id="menu-button" >
              <img src='/fitsystem/placeholder.svg' width='40' height='40' class='rounded-full' alt='Client Avatar' style='aspect-ratio:40/40;object-fit:cover' />
            </button>
          </div>
          <div id="menu-toggle" style="display: none">
            <div class="absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
              <div class="py-1" role="none">
                <span id="menu-item-logout" class="text-gray-700 block px-4 py-2 text-sm" id="menu-item-logout">Logout</span>
              </div>
            </div>
          </div>
        </div>
      </nav>
    </header>
    <main class="flex-1 p-6">
      <div class="bg-white rounded-lg shadow-md p-6 space-y-4">
        <div class="flex items-center justify-between">
          <h1 class="text-2xl font-bold">Progresso dos Clientes</h1>
          <div class="flex items-center gap-4">
            <button
              class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2"
              type="button" id="radix-:R1jlafnnja:" aria-haspopup="menu" aria-expanded="false" data-state="closed">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="h-4 w-4 mr-2">
                <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
              </svg>
              Filtros
            </button>
            <button
              class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 px-4 py-2"
              type="button" id="radix-:R2jlafnnja:" aria-haspopup="menu" aria-expanded="false" data-state="closed">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                class="h-4 w-4 mr-2">
                <line x1="10" x2="21" y1="6" y2="6"></line>
                <line x1="10" x2="21" y1="12" y2="12"></line>
                <line x1="10" x2="21" y1="18" y2="18"></line>
                <path d="M4 6h1v4"></path>
                <path d="M4 10h2"></path>
                <path d="M6 18H4c0-1 2-2 2-3s-1-1.5-2-1"></path>
              </svg>
              Ordenação
            </button>
          </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <?php
          require "database.php";

          try {
              $stmt = $conn->query("SELECT * FROM client");
              $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
              foreach ($tasks as $task) {
                  echo "<div class='rounded-lg border bg-card text-card-foreground shadow-sm' data-v0-t='card'>";
                  echo "<div class='flex flex-col space-y-1.5 p-6'>";
                  echo "<div class='flex items-center gap-2'>";
                  echo "<img src='/fitsystem/placeholder.svg' width='40' height='40' class='rounded-full' alt='Client Avatar' style='aspect-ratio:40/40;object-fit:cover' />";
                  echo "<div>";
                  echo "<h3 class='font-medium'>" . $task["name"] . "</h3>";
                  echo "<p class='text-gray-500 text-sm'>Ativo</p>";
                  echo "</div>";
                  echo "</div>";
                  echo "<div class='flex items-center gap-1 text-green-500'>";
                  echo "<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='h-4 w-4'>";
                  echo "<polyline points='20 6 9 17 4 12'></polyline>";
                  echo "</svg>";
                  echo "<span>Progresso 85%</span>";
                  echo "</div>";
                  echo "</div>";
                  echo "<div class='p-6'>";
                  echo "<div class='aspect-[4/3]'>";
                  echo "<div style='width:100%;height:100%'>";
                  echo "<canvas id='user-chart-" . $task['name'] . "'></canvas>";
                  echo "</div>";
                  echo "</div>";
                  echo "</div>";
                  echo "</div>";
              }
          } catch (PDOException $e) {
              echo json_encode(["error" => $e->getMessage()]);
          }
          ?>
        </div>
      </div>
    </main>
  </div>
</body>

</html>