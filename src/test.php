<?php

$name = (string) readline("Your name: ");
$age = (int) readline("Your age: ");
$budget = (int) readline("Your budget: ");
$location = (string) readline("Your location: ");
$interest = (string) readline("Your interest: ");

if (
    empty($name) ||
    empty($age) ||
    empty($budget) ||
    empty($location) ||
    empty($interest)
) {
    echo "Please fill in all fields";
} else {
    echo "Hello $name, you are $age years old and you have a budget of $budget. You are interested in $interest and you are located in $location";
}

$budget = (int) $budget;
if ($budget < 100) {
    echo "You have a low budget";
} elseif ($budget >= 100 && $budget <= 500) {
    echo "You have a moderate budget";
} else {
    echo "You have a high budget";
}

?>

