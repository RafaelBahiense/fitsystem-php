<?php
function classRowComponent($class)
{
    $html = "
                <div class='flex items-center justify-between'>
                    <div class='flex items-center gap-2'>
                        <i data-lucide='{icon}' ></i> 
                        <div>
                            <h3 class='font-medium'>{name}</h3>
                            <p class='text-gray-500 text-sm'>Segunda, 18:00</p>
                        </div>
                    </div>
                    <div class='flex items-center gap-1'>
                        <i data-lucide='users'></i> 
                        <span>{subscription_count} Inscritos</span>
                    </div>
                </div>
";
    $html = str_replace("{name}", $class["name"], $html);
    $html = str_replace("{icon}", $class["icon"], $html);
    $html = str_replace(
        "{subscription_count}",
        $class["subscription_count"],
        $html
    );

    return $html;
}
