<?php
include_once "../tools.php";

const TEST = false;
//const TEST = true;


function parse_input(): array
{
    $filename = "input.txt";
    if (TEST)
        $filename = "test_input.txt";

    $parsed = [];
    $input = file_get_contents($filename);
    $input = explode("\r\n\r\nMonkey ", $input);

    foreach ($input as $i => $player) {
        $player = explode("\r\n", $player);
        $parsed[] = [
            'starting_items' => explode(", ", explode(': ', $player[1])[1]),
            'operation' => explode(' ', explode(': new = ', $player[2])[1]),
            'div_by' => explode(': divisible by ', $player[3])[1],
            'true' => explode(': throw to monkey ', $player[4])[1],
            'false' => explode(': throw to monkey ', $player[5])[1],
        ];
    }
    return $parsed;
}

function new_worry_level($operation, $current_level)
{
    $left = $operation[0] == 'old' ? $current_level : $operation[0];
    $right = $operation[2] == 'old' ? $current_level : $operation[2];
    $new_level = 0;
    switch ($operation[1]) {
        case '+':
            $new_level = $left + $right;
            break;
        case '-':
            $new_level = $left - $right;
            break;
        case '*':
            $new_level = $left * $right;
            break;
        case '/':
            $new_level = $left / $right;
            break;
    }
    return $new_level;
}

function part1($monkeys): int
{
    $rounds = 20;
    $inspected_count = array_fill(0, count($monkeys), 0);
    for($i = 0; $i < $rounds; $i++) {
        for($monkey = 0; $monkey < count($monkeys); $monkey++) {
            $data = $monkeys[$monkey];
            foreach ($data['starting_items'] as $item) {
                $inspected_count[$monkey]++;
                $new_level =  floor(new_worry_level($data['operation'], $item) / 3);
                if ($new_level % $data['div_by'] == 0) {
                    $monkeys[$data['true']]['starting_items'][] = $new_level;
                } else {
                    $monkeys[$data['false']]['starting_items'][] = $new_level;
                }
            }
            $monkeys[$monkey]['starting_items'] = [];
        }
    }
    arsort($inspected_count);
    return array_shift($inspected_count) * array_shift($inspected_count);
}

function part2($monkeys): int
{
    $n = 2 * 3 * 5 * 7 * 11 * 13 * 17 * 19 * 23;
    $rounds = 10000;
    $inspected_count = array_fill(0, count($monkeys), 0);
    for($i = 0; $i < $rounds; $i++) {
        for($monkey = 0; $monkey < count($monkeys); $monkey++) {
            $data = $monkeys[$monkey];
            foreach ($data['starting_items'] as $item) {
                $inspected_count[$monkey]++;
                $new_level =  new_worry_level($data['operation'], $item);
                $new_level %= $n;
                if ($new_level % $data['div_by'] == 0) {
                    $monkeys[$data['true']]['starting_items'][] = $new_level;
                } else {
                    $monkeys[$data['false']]['starting_items'][] = $new_level;
                }
            }
            $monkeys[$monkey]['starting_items'] = [];
        }
    }
    arsort($inspected_count);
    return array_shift($inspected_count) * array_shift($inspected_count);
}

$input = parse_input();
echo "Part1: " . part1($input) . PHP_EOL;
echo "Part2: " . part2($input) . PHP_EOL;