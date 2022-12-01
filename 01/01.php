<?php
include_once "../tools.php";

const TEST = false;
//const TEST = true;

function parse_input(): array
{
    $filename = "input.txt";
    if (TEST)
        $filename = "test_input.txt";

    // Read line by line
    $lines = lines($filename);
    $result = [];
    $sum = 0;
    foreach ($lines as $line) {
        if ($line == "") {
            $result[] = $sum;
            $sum = 0;
            continue;
        }
        $sum += intval($line);
    }
    rsort($result);
    return $result;
}

function part1($input): int
{
    return $input[0];
}

function part2($input): int
{
    return $input[0] + $input[1] + $input[2];
}

$input = parse_input();
echo "Part1: " . part1($input) . PHP_EOL;
echo "Part2: " . part2($input) . PHP_EOL;