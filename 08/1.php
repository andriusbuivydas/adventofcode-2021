<?php

$digits = readDigits('input.txt');

$count = uniqDigitCount($digits);
printf("Uniq digit count: %d\n", $count);

function readDigits(string $path): array
{
    $digits = [];
    $handle = fopen('input.txt', 'r');
    while (($data = fgetcsv($handle, 1024, '|')) !== false) {
        $newDigits = explode(' ', trim($data[1]));
        foreach ($newDigits as $newDigit) {
            $digits[] = $newDigit;
        }
    }
    fclose($handle);

    return $digits;
}

function uniqDigitCount($digits): int
{
    $count = 0;
    foreach ($digits as $digit) {
        $count += match (strlen($digit)) {
            2, 4, 3, 7 => 1,
            default => 0,
        };
    }

    return $count;
}
