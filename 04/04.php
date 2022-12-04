<?php
include_once "../tools.php";

const TEST = false;
//const TEST = true;


function parse_input(): array
{
    $filename = "input.txt";
    if (TEST)
        $filename = "test_input.txt";

    $lines = lines($filename);
    $input = [];
    foreach ($lines as $line) {
        [$elf1, $elf2] = explode(",", $line);
        $input[] = [explode("-", $elf1), explode("-", $elf2)];
    }
    return $input;
}

function contains($range1, $range2): bool
{
    $min1 = $range1[0];
    $max1 = $range1[1];
    $min2 = $range2[0];
    $max2 = $range2[1];

    // Check if range1 is inside range2
    if ($min1 >= $min2 && $max1 <= $max2)
        return true;
    return false;
}

function overlap($range1, $range2): bool
{
    $min1 = $range1[0];
    $max1 = $range1[1];
    $min2 = $range2[0];
    $max2 = $range2[1];

    if (contains($range1, $range2))
        return true;

    if ($min1 >= $min2 && $min1 <= $max2 || $max1 >= $min2 && $max1 <= $max2)
        return true;

    return false;
}

function part1($input): int
{
    $result = 0;

    foreach ($input as $pair) {
        if (contains($pair[0], $pair[1]) || contains($pair[1], $pair[0]))
            $result++;
    }

    return $result;
}

function part2($input): int
{
    $result = 0;

    foreach ($input as $pair) {
        if (overlap($pair[0], $pair[1]) || overlap($pair[1], $pair[0]))
            $result++;
    }

    return $result;
}

$input = parse_input();
echo "Part1: " . part1($input) . PHP_EOL;
echo "Part2: " . part2($input) . PHP_EOL;