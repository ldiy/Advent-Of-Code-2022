<?php
include_once "../tools.php";

//const TEST = false;
const TEST = true;

function parse_input(): array
{
    $filename = "input.txt";
    if (TEST)
        $filename = "test_input.txt";

    // Read line by line
    $lines = lines($filename);
    $result = [
        'crates' => [],
        'steps' => [],
    ];

    $parseCrates = true;

    foreach ($lines as $line) {
        if ($line == '')
            continue;
        if ($parseCrates) {
            $chars = str_split($line);
            for ($i = 0; $i < count($chars); $i++) {
                if ($chars[$i] == '[') {
                    $result['crates'][$i/4 + 1][] = $chars[$i+1];
                    $i += 2;
                }elseif (is_numeric($chars[$i])) {
                    $parseCrates = false;
                    break;
                }
            }
        } else {
            $instruction = explode(' ', $line);
            $result['steps'][] = [
                'amount' => $instruction[1],
                'from' => $instruction[3],
                'to' => $instruction[5],
            ];
        }
    }
    return $result;
}

function part1($input): string
{
    $result = "";
    $steps = $input['steps'];
    $crates = $input['crates'];

    foreach ($steps as $step) {
        for ($i = 0; $i < $step['amount']; $i++)
            array_unshift($crates[$step['to']], array_shift($crates[$step['from']]));
    }

    for ($i = 1; $i <= count($crates); $i++)
        $result .= $crates[$i][0];

    return $result;
}

function part2($input): string
{
    $result = "";
    $steps = $input['steps'];
    $crates = $input['crates'];

    foreach ($steps as $step) {
        $from = $step['from'];
        $to = $step['to'];
        $amount = $step['amount'];

        $stack = array_reverse(array_slice($crates[$from], 0, $amount));
        $crates[$from] = array_slice($crates[$from], $amount);
        for ($i = 0; $i < $amount; $i++)
            array_unshift($crates[$to], array_shift($stack));

    }

    for ($i = 1; $i <= count($crates); $i++)
        $result .= $crates[$i][0];

    return $result;
}

$input = parse_input();
echo "Part1: " . part1($input) . PHP_EOL;
echo "Part2: " . part2($input) . PHP_EOL;