<?php

$points = [
    ')' => 1,
    ']' => 2,
    '}' => 3,
    '>' => 4,
];

$matches = [
    '(' => ')',
    '[' => ']',
    '{' => '}',
    '<' => '>',
];

$allPoints = [];
foreach (readLines('input.txt') as $chars) {
    if (!isCorruptedLine($chars)) {
        $closingChars = [];
        $interPoints = 0;
        $stack = findLastStackState($chars);
        while (!$stack->isEmpty()) {
            $head = $stack->pop();
            $closingChars[] = $matches[$head];
            $interPoints = $interPoints * 5 + $points[$matches[$head]];
        }
        echo "Intermediate points: $interPoints" . PHP_EOL;
        $allPoints[] = $interPoints;
    }
}

sort($allPoints);
$middlePoints = $allPoints[(count($allPoints) - 1) / 2];

printf("Middle points: %d\n", $middlePoints);

function isCorruptedLine(array $chars): bool
{
    $matches = [
        '(' => ')',
        '[' => ']',
        '{' => '}',
        '<' => '>',
    ];

    $stack = new SplStack();

    foreach ($chars as $char) {
        if ($stack->isEmpty()) {
            $stack->push($char);
            continue;
        }

        $head = $stack->pop();
        if (!array_key_exists($head, $matches)) {
            return true;
        }
        if ($matches[$head] !== $char) {
            $stack->push($head);
            $stack->push($char);
        }
    }

    return false;
}

function findLastStackState(array $chars): SplStack
{
    $matches = [
        '(' => ')',
        '[' => ']',
        '{' => '}',
        '<' => '>',
    ];

    $stack = new SplStack();

    foreach ($chars as $char) {
        if ($stack->isEmpty()) {
            $stack->push($char);
            continue;
        }

        $head = $stack->pop();
        if ($matches[$head] !== $char) {
            $stack->push($head);
            $stack->push($char);
        }
    }

    return $stack;
}

function readLines(string $path): \Generator
{
    $handle = fopen('input.txt', 'r');
    while (($data = fgetcsv($handle, 2048)) !== false) {
        yield str_split($data[0]);
    }
    fclose($handle);
}
