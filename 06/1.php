<?php

$ages = readAges('input.txt');
printf("Day %d - %d lanternfishes\n", 0, count($ages));

for ($i = 0; $i < 80; $i++) {
    $ages = tick($ages);
    printf("Day %d - %d lanternfishes\n", $i + 1, count($ages));
}

function readAges(string $path): array
{
    $ages = [];
    $handle = fopen('input.txt', 'r');
    while (($data = fgetcsv($handle)) !== false) {
        $ages = $data;
    }
    fclose($handle);
    $ages = array_map(fn ($x) => (int) $x, $ages);

    return $ages;
}

function tick(array $ages): array
{
    $newborns = [];
    foreach ($ages as &$age) {
        if ($age > 0) {
            $age -= 1;
        } else {
            $age = 6;
            $newborns[] = 8;
        }
    }

    return array_merge($ages, $newborns);
}
