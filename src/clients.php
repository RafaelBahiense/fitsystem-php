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
  <script src="clients.js"></script>
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
        <a class="underline" href="#">
          Clientes
        </a>
        <a class="hover:underline" href="/fitsystem/classes.php">
          Aulas
        </a>
        <a class="hover:underline" href="/fitsystem/progress.php">
          Progresso
        </a>
      </nav>
    </header>
    <main class="flex-1">
      <section class="p-6">
        <div class="flex items-center justify-between">
          <h2 class="text-lg font-bold">Clientes</h2>
          <button id="add-client"
            class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-gray-900 text-white hover:bg-primary/90 h-10 px-4 py-2 mb-2">
            Adicionar Cliente
          </button>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full table-auto">
            <thead>
              <tr class="bg-gray-100 text-gray-600">
                <th class="px-4 py-2 text-left">Nome</th>
                <th class="px-4 py-2 text-left">Email</th>
                <th class="px-4 py-2 text-left">Telefone</th>
                <th class="px-4 py-2 text-left">Status</th>
                <th class="px-4 py-2 text-left">Ações</th>
              </tr>
            </thead>
            <tbody>
              <?php
                require "database.php";

                try {
                    $stmt = $conn->query("SELECT * FROM client");
                    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($tasks as $task) {
                        echo "<tr class='border-b'>";
                        echo "<td class='px-4 py-2'>";
                        echo "<div class='flex items-center gap-2'>";
                        echo "<img src='/fitsystem/placeholder.svg' width='40' height='40' class='rounded-full' alt='Client Avatar' style='aspect-ratio:40/40;object-fit:cover' />";
                        echo "<span>" . $task["name"] . "</span>";
                        echo "</div>";
                        echo "</td>";
                        echo "<td class='px-4 py-2'>" . $task["email"] . "</td>";
                        echo "<td class='px-4 py-2'>" . $task["phone"] . "</td>";
                        echo "<td class='px-4 py-2'>Ativo</td>";
                        echo "<td class='px-4 py-2'>";
                        echo "<div class='flex items-center gap-2'>";
                        echo "<button class='inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 w-10'>";
                        echo "<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='h-4 w-4'>";
                        echo "<path d='M4 13.5V4a2 2 0 0 1 2-2h8.5L20 7.5V20a2 2 0 0 1-2 2h-5.5'></path>";
                        echo "<polyline points='14 2 14 8 20 8'></polyline>";
                        echo "<path d='M10.42 12.61a2.1 2.1 0 1 1 2.97 2.97L7.95 21 4 22l.99-3.95 5.43-5.44Z'></path>";
                        echo "</svg>";
                        echo "</button>";
                        echo "<button class='inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 w-10'>";
                        echo "<svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='h-4 w-4'>";
                        echo "<path d='M3 6h18'></path>";
                        echo "<path d='M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6'></path>";
                        echo "<path d='M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2'></path>";
                        echo "</svg>";
                        echo "</button>";
                        echo "</div>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } catch (PDOException $e) {
                    echo json_encode(["error" => $e->getMessage()]);
                }
                ?>
            </tbody>
          </table>
        </div>
      </section>
    </main>
  </div>
  <!-- Modal Content -->
  <div id="client-modal" class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full">
      <div class="flex justify-end">
        <button id="close-client-modal" class="text-gray-500 hover:text-gray-800">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
            stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
      <form id="clientForm" class="space-y-6">
        <h2 class="text-lg font-bold text-center">Informações do Cliente</h2>
        <div>
          <label class="block text-sm font-medium text-gray-700">Nome</label>
          <input type="text" name="name" id="name" required
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">Email</label>
          <input type="email" name="email" id="email" required
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">Telefone</label>
          <input type="tel" name="phone" id="phone"
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">Genero</label>
          <select name="gender" id="gender"
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
            <option value="male">Masculino</option>
            <option value="female">Feminino</option>
            <option value="other">Outros</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">Endereço</label>
          <textarea name="address" id="address" rows="3"
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
        </div>
        <div class="flex justify-end">
          <button
            class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-gray-900 text-white hover:bg-primary/90 h-10 px-4 py-2 w-full">
            Cadastrar Cliente
          </button>
        </div>
      </form>
    </div>
  </div>
</body>

</html>