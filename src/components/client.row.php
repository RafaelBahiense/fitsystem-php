<?php
function clientRowComponent($client, $mode = "light")
{
    if ($mode === "light") {
        $html = "
        <div class='flex items-center justify-between'>
            <div class='flex items-center gap-2'>
                <img src='/fitsystem/placeholder.svg' width='40' height='40' class='rounded-full' alt='Client Avatar' style='aspect-ratio:40/40;object-fit:cover' />
                <div>
                    <h3 class='font-medium'>{name}</h3>
                    <p class='text-gray-500 text-sm'>Membro Ativo</p>
                </div>
            </div>
            <div class='flex items-center gap-1 text-green-500'>
                <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='h-4 w-4'>
                    <polyline points='20 6 9 17 4 12'></polyline>
                </svg>
                <span>85% Progresso</span>
            </div>
        </div>
    ";
        $html = str_replace("{name}", $client["name"], $html);

        return $html;
    } elseif ($mode === "full") {
        $html = "
          <tr class='border-b'>
              <td class='px-4 py-2'>
                  <div class='flex items-center gap-2'>
                      <img src='/fitsystem/placeholder.svg' width='40' height='40' class='rounded-full' alt='Client Avatar' style='aspect-ratio:40/40;object-fit:cover' />
                      <span>{name}</span>
                  </div>
              </td>
              <td class='px-4 py-2'>{email}</td>
              <td class='px-4 py-2'>{phone}</td>
              <td class='px-4 py-2'>Ativo</td>
              <td class='px-4 py-2'>
                  <div class='flex items-center gap-2'>
                      <button class='inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 w-10'>
                          <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='h-4 w-4'>
                              <path d='M4 13.5V4a2 2 0 0 1 2-2h8.5L20 7.5V20a2 2 0 0 1-2 2h-5.5'></path>
                              <polyline points='14 2 14 8 20 8'></polyline>
                              <path d='M10.42 12.61a2.1 2.1 0 1 1 2.97 2.97L7.95 21 4 22l.99-3.95 5.43-5.44Z'></path>
                          </svg>
                      </button>
                      <button class='inline-flex items-center justify-center whitespace-nowrap rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 w-10'>
                          <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='h-4 w-4'>
                              <path d='M3 6h18'></path>
                              <path d='M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6'></path>
                              <path d='M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2'></path>
                          </svg>
                      </button>
                  </div>
              </td>
          </tr>
";

        $html = str_replace("{name}", $client["name"], $html);
        $html = str_replace("{email}", $client["email"], $html);
        $html = str_replace("{phone}", $client["phone"], $html);

        return $html;
    } else {
        throw new Exception("Invalid mode");
    }
} ?>

