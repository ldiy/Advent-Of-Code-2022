<?php
include_once "../tools.php";

const TEST = false;
//const TEST = true;


function parse_input(): array
{
    $filename = "input.txt";
    if (TEST)
        $filename = "test_input.txt";

    return str_split(lines($filename)[0]);
}

function unique(array $chars): bool
{
    foreach ($chars as $k1=>$char1) {
        foreach ($chars as $k2=>$char2){
            if ($k1 == $k2) continue;
            if ($char1 == $char2) return false;
        }
    }
    return true;
}

function solve(array $input, int $n): int
{
    for($i=0; $i<count($input); $i++)
        if (unique(array_slice($input, $i, $n)))
            return $i + $n;
    return 0;
}

$input = parse_input();
echo "Part1: " . solve($input, 4) . PHP_EOL;
echo "Part2: " . solve($input, 14) . PHP_EOL;