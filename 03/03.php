<?php
include_once "../tools.php";

const TEST = false;
//const TEST = true;

function parse_input(): array
{
    $filename = "input.txt";
    if (TEST)
        $filename = "test_input.txt";

    return array_map(function ($line) {
        return str_split($line);
    }, lines($filename));
}

function priority($char): int
{
    if (ctype_lower($char))
        return ord($char) - 96;

    return ord($char) - 65 + 27;
}

function part1($input): int
{
    $result = 0;
    foreach ($input as $line) {
        $part1 = array_slice($line, 0, count($line) / 2);
        $part2 = array_slice($line, count($line) / 2);
        $result += priority(array_values(array_intersect($part1, $part2))[0]);
    }
    return $result;
}

function part2($input): int
{
    $result = 0;
    for ($i = 0; $i < count($input); $i+=3)
        $result += priority(array_values(array_intersect($input[$i], $input[$i + 1], $input[$i + 2]))[0]);
    return $result;
}

$input = parse_input();
echo "Part1: " . part1($input) . PHP_EOL;
echo "Part2: " . part2($input) . PHP_EOL;