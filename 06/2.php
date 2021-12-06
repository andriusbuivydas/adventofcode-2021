<?php

// with previous algo 236 ticks is not reachable
// have a frequency of how many lanternfishes have the age of X days
// and update the frequency only

$frequencies = readFrequencies('input.txt');
echo json_encode($frequencies) . PHP_EOL;

for ($i = 0; $i < 256; $i++) {
    $frequencies = tick($frequencies);
    echo json_encode($frequencies) . PHP_EOL;
}

$total = 0;
foreach ($frequencies as $frequency) {
    $total += $frequency;
}

printf("Lanternfish count %d\n", $total);

function readFrequencies(string $path): array
{
    $ages = [];
    $handle = fopen('input.txt', 'r');
    while (($data = fgetcsv($handle)) !== false) {
        $ages = $data;
    }
    fclose($handle);
    $ages = array_map(fn ($x) => (int) $x, $ages);

    $frequencies = [];
    for ($i = 0; $i <= 8; $i++) {
        $frequencies[$i] = 0;
    }

    foreach ($ages as $age) {
        $frequencies[$age]++;
    }

    return $frequencies;
}

function tick(array $frequencies): array
{
    $newborns = $frequencies[0];

    // rotate left
    $first = array_shift($frequencies);
    $frequencies[] = $first;
    $frequencies[6] += $newborns;

    return $frequencies;
}
