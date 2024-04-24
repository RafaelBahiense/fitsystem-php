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
  <script src="classes.js"></script>
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
        <a class="underline" href="#">
          Aulas
        </a>
        <a class="hover:underline" href="/fitsystem/progress.php">
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
    <main class="flex-1">
      <section class="p-6">
        <div class="flex items-center justify-between">
          <h2 class="text-lg font-bold">Clientes</h2>
          <button id="add-class"
            class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-gray-900 text-white hover:bg-primary/90 h-10 px-4 py-2 mb-2">
            Adicionar Aula
          </button>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full table-auto">
            <thead>
              <tr class="bg-gray-100 text-gray-600">
                <th class="px-4 py-2 text-left"></th>
                <th class="px-4 py-2 text-left">Nome</th>
                <th class="px-4 py-2 text-left">Dia e Horário</th>
                <th class="px-4 py-2 text-left">Inscritos</th>
                <th class="px-4 py-2 text-left">Status</th>
                <th class="px-4 py-2 text-left">Ações</th>
              </tr>
            </thead>
            <tbody>
              <tr class="border-b border-gray-200">
                <td class="px-4 py-2 text-left">1</td>
                <td class="px-4 py-2 text-left">Musculação</td>
                <td class="px-4 py-2 text-left">Segunda e Quarta 08:00</td>
                <td class="px-4 py-2 text-left">
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
                </td>
                <td class="px-4 py-2 text-left">Ativo</td>
                <td class="px-4 py-2 text-left">
                  <div class='flex items-center gap-2'>
                    <div class='flex items-center gap-2'>
                      <button
                        class='inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 w-10'>
                        <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none'
                          stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'
                          class='h-4 w-4'>
                          <path d='M4 13.5V4a2 2 0 0 1 2-2h8.5L20 7.5V20a2 2 0 0 1-2 2h-5.5'></path>
                          <polyline points='14 2 14 8 20 8'></polyline>
                          <path d='M10.42 12.61a2.1 2.1 0 1 1 2.97 2.97L7.95 21 4 22l.99-3.95 5.43-5.44Z'></path>
                        </svg>
                      </button>
                      <button
                        class='inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 w-10'>
                        <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none'
                          stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'
                          class='h-4 w-4'>
                          <path d='M3 6h18'></path>
                          <path d='M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6'></path>
                          <path d='M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2'></path>
                        </svg>
                      </button>
                    </div>
                </td>
              </tr>
              <tr class="border-b border-gray-200">
                <td class="px-4 py-2 text-left">2</td>
                <td class="px-4 py-2 text-left">Crossfit</td>
                <td class="px-4 py-2 text-left">Terça e Quinta 18:00</td>
                <td class="px-4 py-2 text-left">
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
                </td>
                <td class="px-4 py-2 text-left">Ativo</td>
                <td class="px-4 py-2 text-left">
                  <div class='flex items-center gap-2'>
                    <div class='flex items-center gap-2'>
                      <button
                        class='inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 w-10'>
                        <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none'
                          stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'
                          class='h-4 w-4'>
                          <path d='M4 13.5V4a2 2 0 0 1 2-2h8.5L20 7.5V20a2 2 0 0 1-2 2h-5.5'></path>
                          <polyline points='14 2 14 8 20 8'></polyline>
                          <path d='M10.42 12.61a2.1 2.1 0 1 1 2.97 2.97L7.95 21 4 22l.99-3.95 5.43-5.44Z'></path>
                        </svg>
                      </button>
                      <button
                        class='inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 w-10'>
                        <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none'
                          stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'
                          class='h-4 w-4'>
                          <path d='M3 6h18'></path>
                          <path d='M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6'></path>
                          <path d='M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2'></path>
                        </svg>
                      </button>
                    </div>
                </td>
              </tr>
              <tr class="border-b border-gray-200">
                <td class="px-4 py-2 text-left">3</td>
                <td class="px-4 py-2 text-left">Zumba</td>
                <td class="px-4 py-2 text-left">Sexta 19:00</td>
                <td class="px-4 py-2 text-left">
                  <div class="flex items-center gap-1 text-green-500">
                      <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                          fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                          stroke-linejoin="round" class="h-4 w-4">
                          <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                          <circle cx="9" cy="7" r="4"></circle>
                          <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                          <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                      </svg>
                      <span>20/20 Inscritos</span>
                  </div>
                </td>
                <td class="px-4 py-2 text-left">Ativo</td>
                <td class="px-4 py-2 text-left">
                  <div class='flex items-center gap-2'>
                    <div class='flex items-center gap-2'>
                      <button
                        class='inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 w-10'>
                        <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none'
                          stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'
                          class='h-4 w-4'>
                          <path d='M4 13.5V4a2 2 0 0 1 2-2h8.5L20 7.5V20a2 2 0 0 1-2 2h-5.5'></path>
                          <polyline points='14 2 14 8 20 8'></polyline>
                          <path d='M10.42 12.61a2.1 2.1 0 1 1 2.97 2.97L7.95 21 4 22l.99-3.95 5.43-5.44Z'></path>
                        </svg>
                      </button>
                      <button
                        class='inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 w-10'>
                        <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none'
                          stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'
                          class='h-4 w-4'>
                          <path d='M3 6h18'></path>
                          <path d='M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6'></path>
                          <path d='M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2'></path>
                        </svg>
                      </button>
                    </div>
                </td>
              </td>
            </tbody>
          </table>
        </div>
      </section>
    </main>
  </div>
  <!-- Modal Content -->
  <div id="class-modal" class="hidden fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full">
      <div class="flex justify-end">
        <button id="close-class-modal" class="text-gray-500 hover:text-gray-800">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
            stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
      <form id="classForm" class="space-y-6">
        <h2 class="text-lg font-bold text-center">Informações da Aula</h2>
        <div>
          <label class="block text-sm font-medium text-gray-700">Nome</label>
          <input type="text" name="name" id="name" required
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">Descrição</label>
          <textarea name="description" id="description" rows="3"
            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Dias da Semana</label>
            <div class="flex flex-wrap gap-2 mt-1">
                <label class="inline-flex items-center">
                    <input type="checkbox" class="form-checkbox" name="weekdays" value="Seg">
                    <span class="ml-2">Seg</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="checkbox" class="form-checkbox" name="weekdays" value="Ter">
                    <span class="ml-2">Ter</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="checkbox" class="form-checkbox" name="weekdays" value="Qua">
                    <span class="ml-2">Qua</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="checkbox" class="form-checkbox" name="weekdays" value="Qui">
                    <span class="ml-2">Qui</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="checkbox" class="form-checkbox" name="weekdays" value="Sex">
                    <span class="ml-2">Sex</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="checkbox" class="form-checkbox" name="weekdays" value="Sáb">
                    <span class="ml-2">Sáb</span>
                </label>
                <label class="inline-flex items-center">
                    <input type="checkbox" class="form-checkbox" name="weekdays" value="Dom">
                    <span class="ml-2">Dom</span>
                </label>
            </div>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700">Hora do Dia</label>
            <select name="time" id="time" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                <option value="00:00">00:00</option>
                <option value="01:00">01:00</option>
                <option value="02:00">02:00</option>
                <option value="03:00">03:00</option>
                <option value="04:00">04:00</option>
                <option value="05:00">05:00</option>
                <option value="06:00">06:00</option>
                <option value="07:00">07:00</option>
                <option value="08:00">08:00</option>
                <option value="09:00">09:00</option>
                <option value="10:00">10:00</option>
                <option value="11:00">11:00</option>
                <option value="12:00">12:00</option>
                <option value="13:00">13:00</option>
                <option value="14:00">14:00</option>
                <option value="15:00">15:00</option>
                <option value="16:00">16:00</option>
                <option value="17:00">17:00</option>
                <option value="18:00">18:00</option>
                <option value="19:00">19:00</option>
                <option value="20:00">20:00</option>
                <option value="21:00">21:00</option>
                <option value="22:00">22:00</option>
                <option value="23:00">23:00</option>
            </select>
        </div>
        <div class="flex justify-end">
          <button type="submit"
            class="inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-gray-900 text-white hover:bg-primary/90 h-10 px-4 py-2 w-full">
            Cadastrar Aula
          </button>
        </div>
      </form>
    </div>
  </div>
</body>

</html>