<?php

$handle = fopen('input.txt', 'r');

$horizontal = $depth = $aim = 0;
while (($data = fgetcsv($handle)) !== false) {
    [$direction, $steps] = explode(' ', $data[0]);

    $aim += match ($direction) {
        'up' => -1 * (int) $steps,
        'down' => (int) $steps,
        default => 0,
    };

    $horizontal += match ($direction) {
        'forward' => (int) $steps,
        default => 0,
    };

    $depth += match ($direction) {
        'forward' => (int) $steps * $aim,
        default => 0,
    };
}
fclose($handle);

echo PHP_EOL;
printf("Horizontal: %d\n", $horizontal);
printf("Depth: %d\n", $depth);
printf("Aim: %d\n", $aim);
echo PHP_EOL;
printf("Multiplied: %d\n", $horizontal * $depth);
echo PHP_EOL;

