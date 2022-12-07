<?php
include_once "../tools.php";

const TEST = false;
//const TEST = true;

class Node {
    public int $size;
    public bool $isDir;
    public string $name;
    public array $children = [];
    public Node|null $parent = null;

    public function __construct(string $name, int $size, bool $isDir = false)
    {
        $this->name = $name;
        $this->size = $size;
        $this->isDir = $isDir;
    }

    public function addChild(Node $child): void
    {
        $this->children[] = $child;
        $child->parent = $this;
    }

    public function size(): int
    {
        if (!$this->isDir) {
            return $this->size;
        }
        $size = 0;
        foreach ($this->children as $child) {
            $size += $child->size();
        }
        return $size;
    }
}


function parse_input(): array
{
    $filename = "input.txt";
    if (TEST)
        $filename = "test_input.txt";

    $lines =  lines($filename);

    $nodes = [];
    $current_node = new Node('/', 0 , true);
    $nodes['/'] = $current_node;
    foreach ($lines as $line) {
        $parts = explode(' ', $line);
        if ($parts[0] == '$') {
            if ($parts[1] == 'cd') {
                if ($parts[2] == '/')
                    $current_node = $nodes['/'];
                elseif ($parts[2] == '..')
                    $current_node = $current_node->parent;
                else
                    $current_node = $nodes[$current_node->name . '/' . $parts[2]];
            }
        } else {
            if ($parts[0] == 'dir') {
                $newNode = new Node($current_node->name . '/' . $parts[1], 0, true);
                $nodes[$current_node->name . '/' . $parts[1]] = $newNode;
            } else {
                $newNode = new Node($current_node->name . '/' . $parts[1], $parts[0]);
                $nodes[$current_node->name . '/' . $parts[0]] = $newNode;
            }
            $current_node->addChild($newNode);
        }
    }
    return $nodes;
}

function part1($input): int
{
    $result = 0;

    foreach ($input as $node) {
        if (!$node->isDir) continue;
        $size = $node->size();
        if ($size <= 100000)
            $result += $size;
    }

    return $result;
}

function part2($input): int
{
    $totCap = 70000000;
    $minCleanup = 30000000 - ($totCap -  $input['/']->size());
    $minSize = $totCap;
    foreach ($input as $node) {
        if (!$node->isDir) continue;
        $size = $node->size();
        if ($size >= $minCleanup)
            $minSize = min($minSize, $size);
    }
    return $minSize;
}

$input = parse_input();
echo "Part1: " . part1($input) . PHP_EOL;
echo "Part2: " . part2($input) . PHP_EOL;