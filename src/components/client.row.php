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
            <div class='flex items-center gap-1'>
                <span class='text-gray-500 text-sm'>Faltam {birthday} dias<br> para o aniversário</span>
            </div>
        </div>
  ";

    if ($client["photo"] != null) {
        $html = str_replace("{photo}", $client["photo"], $html);
    } else {
        $html = str_replace("{photo}", "/fitsystem/placeholder.webp", $html);
    }

    $html = str_replace("{name}", $client["name"], $html);

    $birthday = new DateTime($client["date_of_birth"]);
    $currentYear = (new DateTime())->format("Y");
    $nextBirthday = DateTime::createFromFormat(
        "Y-m-d",
        $currentYear . "-" . $birthday->format("m-d")
    );

    if ($nextBirthday < new DateTime()) {
        $nextBirthday->modify("+1 year");
    }

    $diff = (new DateTime())->diff($nextBirthday);
    $days = $diff->days;

    $html = str_replace("{birthday}", $days, $html);

    return $html;
} ?>

