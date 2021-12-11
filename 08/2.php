<?php

$digitMap = [
    'abcefg' => 9,
    'cf' => 1,
    'acdeg' => 2,
    'acdfg' => 3,
    'bcdf' => 4,
    'abdfg' => 5,
    'abdefg' => 6,
    'acf' => 7,
    'abcdefg' => 8,
    'abcdfg' => 0,
];

$sum = 0;
foreach (readLines('input.txt') as [$unknowns, $digits]) {
    $number = mapToNumber($unknowns, $digits, $digitMap);
    printf("Number: %d\n", $number);
    $sum += $number;
}
printf("Sum: %d\n", $sum);

function readLines(string $path): \Generator
{
    $handle = fopen('input.txt', 'r');
    while (($data = fgetcsv($handle, 1024, '|')) !== false) {
        $unknowns = explode(' ', trim($data[1]));
        $digits = explode(' ', trim($data[1]));
        yield [$unknowns, $digits];
    }
    fclose($handle);
}

function mapToNumber(array $unknowns, array $digits, array $digitMap): int
{
    foreach (getPermutations(range('a', 'g'), 7) as $permutation) {
        $pp = implode('', $permutation);
        //printf("Permutation: %s\n", $pp);

        $mappingFound = true;
        foreach ($unknowns as $unknown) {
            $mapping = array_combine($permutation, range('a', 'g'));
            $mapped = map($unknown, $mapping);

            if (array_key_exists($mapped, $digitMap)) {
                //printf("%s => %s\n", $unknown, $mapped);
            } else {
                //printf("%s => NOT\n", $unknown);
                $mappingFound = false;
            }
        }
        //echo PHP_EOL;

        $number = [];
        if ($mappingFound) {
            $mapping = array_combine($permutation, range('a', 'g'));
            //echo json_encode($permutation) . PHP_EOL;
            foreach ($digits as $digit) {
                $digit = sortString($digit);
                $mapped = map($digit, $mapping);

                $number[] = $digitMap[$mapped];
            }
            return (int) implode('', $number);
        }
    }
}

function sortString(string $s): string
{
    $xs = str_split($s);
    sort($xs);
    return implode('', $xs);
}

function map(string $s, array $mapping): string
{
    $xs = str_split($s);
    $ys = [];
    foreach ($xs as $x) {
        $ys[] = $mapping[$x];
    }

    $mapped = implode('', $ys);
    return sortString($mapped);
}

die;



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

function getPermutations(array $data, int $size = 1): \Generator
{
    // getPermutations(range(0, 2)) --> 012 021 102 120 201 210
    $n = count($data);
    if ($n < 1) {
        return;
    }

    $indices = range(0, $n - 1);
    $cycles = range($n, 1, -1);

    $xs = [];
    foreach ($indices as $index) {
        $xs[] = $data[$index];
    }
    yield $xs;

    while (true) {
        $newIteration = false;
        foreach (range($n - 1, 0, -1) as $i) {
            $cycles[$i] -= 1;
            if ($cycles[$i] === 0) {
                $left = array_slice($indices, $i + 1);
                $right = array_slice($indices, $i, 1);
                array_splice($indices, $i, $n - $i, array_merge($left, $right));
                $cycles[$i] = $n - $i;
            } else {
                $j = $cycles[$i];
                $iTemp = $indices[$n - $j];
                $jTemp = $indices[$i];
                $indices[$i] = $iTemp;
                $indices[$n - $j] = $jTemp;

                $xs = [];
                foreach ($indices as $index) {
                    $xs[] = $data[$index];
                }
                yield $xs;

                $newIteration = true;

                break;
            }
        }

        if ($newIteration === false) {
            return;
        }
    }
}

function getCombinations(array $data, int $size = 1): \Generator
{
    // getCombinations(['a', 'b', 'c'], 2) --> [['a', 'b'], ['a', 'c'], ['b', 'c']]
    $n = count($data);

    if ($n < 1 || $size < 1 || $size > $n) {
        return;
    }

    $indices = saneRange(0, $size); // [0, 1, 2, ..., size - 1]

    $xs = [];
    foreach ($indices as $index) {
        $xs[] = $data[$index];
    }
    yield $xs;

    while (true) {
        $newIteration = false;
        foreach (saneRange($size - 1, -1, -1) as $i) {
            if ($indices[$i] !== $i + $n - $size) {
                $newIteration = true;

                break;
            }
        }

        if ($newIteration === false) {
            return;
        }

        ++$indices[$i];

        foreach (saneRange($i + 1, $size) as $j) {
            $indices[$j] = $indices[$j - 1] + 1;
        }

        $xs = [];
        foreach ($indices as $index) {
            $xs[] = $data[$index];
        }

        yield $xs;
    }
}

function saneRange(int $start, int $end, int $step = 1): array
{
    if ($step === 0) {
        throw new \InvalidArgumentException('step value must be non-zero');
    }

    if ($start === $end) {
        return [];
    }

    if ($end > $start && $step < 0) {
        return [];
    }

    if ($start > $end && $step > 0) {
        return [];
    }

    if ($step > 0) {
        return range($start, $end - 1, $step);
    }

    return range($start, $end + 1, $step);
}
