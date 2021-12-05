<?php

$n = 5;

$pickedNumbers = readPickedNumbers('input.txt');
$boards = readBoards('input.txt', $n);
[$board, $lastPickedNumber] = getWinningEntry($pickedNumbers, $boards, $n);

$total = 0;
foreach ($board as $entry) {
    if ($entry > 0) {
        $total += $entry;
    }
}
$score = $total * $lastPickedNumber;
printf("Score: %d\n", $score);

function readPickedNumbers(string $path): array
{
    $pickedNumbers = [];
    $handle = fopen($path, 'r');
    while (($data = fgetcsv($handle)) !== false) {
        foreach ($data as $pickedNumber) {
            $pickedNumbers[] = (int) $pickedNumber;
        }
        break;
    }
    fclose($handle);

    return $pickedNumbers;
}

function readBoards(string $path, int $n): array
{
    $index = 0;
    $boardsNumbers = [];
    $handle = fopen($path, 'r');
    while (($data = fgetcsv($handle)) !== false) {
        $index++;
        if ($index < 2 || $data[0] === null) {
            continue;
        }
        $entries = preg_replace('/\s+/', ',', trim($data[0]));
        $entries = explode(',', $entries);
        foreach ($entries as $entry) {
            $boardsNumbers[] = (int) $entry;
        }
    }
    fclose($handle);

    return array_chunk($boardsNumbers, $n * $n);
}

function getRows(array $board, int $n): array
{
    $rows = [];

    for ($i = 0; $i < $n; $i++) {
        $row = [];
        for ($j = 0; $j < $n; $j++) {
            $row[] = $board[$i * $n + $j];
        }

        $rows[] = $row;
    }

    return $rows;
}

function getColumns(array $board, int $n): array
{
    $columns = [];

    for ($i = 0; $i < $n; $i++) {
        $column = [];
        for ($j = 0; $j < $n * $n; $j = $j + $n) {
            $column[] = $board[$i + $j];
        }

        $columns[] = $column;
    }

    return $columns;
}

function markPickedNumber(array &$board, int $pickedNumber): void
{
    foreach ($board as &$entry) {
        if ($entry === $pickedNumber) {
            $entry = $entry * -1;
        }
    }
}

function isRowOrColumnMarked(array $entries): bool
{
    $isMarked = false;
    foreach ($entries as $entry) {
        if ($entry >= 0) {
            $isMarked = false;
            break;
        } else {
            $isMarked = true;
        }
    }

    return $isMarked;
}

function getWinningEntry(array $pickedNumbers, array $boards, int $n): array
{
    foreach ($pickedNumbers as $pickedNumber) {
        foreach ($boards as &$board) {
            markPickedNumber($board, $pickedNumber);

            $rows = getRows($board, $n);
            foreach ($rows as $row) {
                if (isRowOrColumnMarked($row)) {
                    return [$board, $pickedNumber];
                }
            }

            $columns = getColumns($board, $n);
            foreach ($columns as $column) {
                if (isRowOrColumnMarked($column)) {
                    return [$board, $pickedNumber];
                }
            }
        }
    }

    return [];
}
