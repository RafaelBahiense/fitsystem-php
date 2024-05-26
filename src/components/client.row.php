<?php
function clientRowComponent($client)
{
    $html = "
        <div class='flex items-center justify-between'>
            <div class='flex items-center gap-2'>
                <img src='{photo}' width='40' height='40' class='rounded-full' alt='Client Avatar' style='aspect-ratio:40/40;object-fit:cover' />
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

    if ($client["photo"] != null) {
        $html = str_replace("{photo}", $client["photo"], $html);
    } else {
        $html = str_replace("{photo}", "/fitsystem/placeholder.webp", $html);
    }

    $html = str_replace("{name}", $client["name"], $html);

    return $html;
} ?>

