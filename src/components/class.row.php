<?php
function classRowComponent($class, $mode = "light")
{
    if ($mode === "light") {
        $html = "
                <div class='flex items-center justify-between'>
                    <div class='flex items-center gap-2'>
                        <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24'
                             fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round'
                             stroke-linejoin='round' class='h-6 w-6 text-gray-500'>
                            <path d='m6.5 6.5 11 11'></path>
                            <path d='m21 21-1-1'></path>
                            <path d='m3 3 1 1'></path>
                            <path d='m18 22 4-4'></path>
                            <path d='m2 6 4-4'></path>
                            <path d='m3 10 7-7'></path>
                            <path d='m14 21 7-7'></path>
                        </svg>
                        <div>
                            <h3 class='font-medium'>{name}</h3>
                            <p class='text-gray-500 text-sm'>Segunda, 18:00</p>
                        </div>
                    </div>
                    <div class='flex items-center gap-1 text-green-500'>
                        <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24'
                             fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round'
                             stroke-linejoin='round' class='h-4 w-4'>
                            <path d='M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2'></path>
                            <circle cx='9' cy='7' r='4'></circle>
                            <path d='M22 21v-2a4 4 0 0 0-3-3.87'></path>
                            <path d='M16 3.13a4 4 0 0 1 0 7.75'></path>
                        </svg>
                        <span>15/20 Inscritos</span>
                    </div>
                </div>
";
        $html = str_replace("{name}", $class["name"], $html);

        return $html;
    } elseif ($mode === "full") {
        $html = "
              <tr class='border-b border-gray-200'>
                <td class='px-4 py-2 text-left'>2</td>
                <td class='px-4 py-2 text-left'>{name}</td>
                <td class='px-4 py-2 text-left'>Terça e Quinta 18:00</td>
                <td class='px-4 py-2 text-left'>
                  <div class='flex items-center gap-1 text-green-500'>
                      <svg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24'
                          fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round'
                          stroke-linejoin='round' class='h-4 w-4'>
                          <path d='M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2'></path>
                          <circle cx='9' cy='7' r='4'></circle>
                          <path d='M22 21v-2a4 4 0 0 0-3-3.87'></path>
                          <path d='M16 3.13a4 4 0 0 1 0 7.75'></path>
                      </svg>
                      <span>15/20 Inscritos</span>
                  </div>
                </td>
                <td class='px-4 py-2 text-left'>Ativo</td>
                <td class='px-4 py-2 text-left'>
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
";
        $html = str_replace("{name}", $class["name"], $html);

        return $html;
    } else {
        throw new Exception("Invalid mode");
    }
}
