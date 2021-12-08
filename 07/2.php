<?php

$positions = readPositions('input.txt');

$min = min($positions);
$max = max($positions);
printf("Min position: %d, Max position: %d\n", $min, $max);

$prices = calculateStepPrices($max);

$fuelConsumption = [];
for ($i = $min; $i <= $max; $i++) {
    $fuelConsumption[$i] = calculateFuelConsumption($positions, $prices, $i);
}

asort($fuelConsumption);
foreach ($fuelConsumption as $index => $fuel) {
    printf("Min fuel consumption would be to move to %d position. Fuel needed: %d\n", $index, $fuel);
    break;
}

function readPositions(string $path): array
{
    $positions = [];
    $handle = fopen('input.txt', 'r');
    while (($data = fgetcsv($handle)) !== false) {
        $positions = $data;
    }
    fclose($handle);
    $positions = array_map(fn ($x) => (int) $x, $positions);

    return $positions;
}

function calculateStepPrices($max): array
{
    $prices = [];
    $prices[0] = 0;
    for ($i = 1; $i <= $max; $i++) {
        $prices[$i] = $prices[$i - 1] + $i;
    }

    return $prices;
}

function calculateFuelConsumption(array $positions, array $prices, int $i): int
{
    $consumption = 0;
    foreach ($positions as $position) {
        $consumption += $prices[abs($position - $i)];
    }

    return $consumption;
}

