<?php

$inputPath = str_replace('/aoc-solutions/', '/aoc-inputs/', getcwd()) . '/';
$filename = $inputPath . ($argv[1] ?? 'testinput.txt');

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

const OP_AND = 'AND';
const OP_OR = 'OR';
const OP_XOR = 'XOR';

$regs = [];
$cmds = [];
$deps = [];
foreach($lines as $l) {
	if (preg_match('/(\w{3}): (1|0)/', $l, $matches)) {
		$regs[$matches[1]] = intval($matches[2]);
	} else if (preg_match('/(\w{3}) (.*) (\w{3}) -> (\w{3})/', $l, $matches)) {
		$cmds[] = [
			'a' => $matches[1],
			'op' => $matches[2],
			'b' => $matches[3],
			'out' => $matches[4],
		];

		// set these for sort order
		$deps[$matches[1]][$matches[4]] = true;
		$deps[$matches[3]][$matches[4]] = true;
	}
}

// usort($cmds, function($a, $b) use ($deps) {
// 	// var_dump($deps[$a['a']]);
// 	// var_dump($deps[$a['b']]);
// 	// var_dump($deps[$b['b']]);

// 	$first = $deps[$a['a']][$b['out']] ?? false;
// 	$second = $deps[$a['b']][$b['out']] ?? false;

// 	// if a's inputs are dependent on b's output, swap em
// 	return $first && $second ? 1 : -1;
// 	// return isset($deps[$a][$b]) && $deps[$a][$b] ? 1 : -1;
// });

// echo '<pre>';
// var_dump($cmds);
// echo '</pre>';
// die();

while (!checkGates()) {
	echo "checking remaining gates\n";
};

krsort($regs);

$bin = '';
foreach($regs as $key => $r) {
	if (!preg_match('/z\d{2}/', $key)) {
		continue;
	}

	$bin .= $r;
}

echo '<pre>';
var_dump($bin);
var_dump(bindec($bin));
echo '</pre>';
die();

// 0011111101000

function checkGates() {
	global $cmds, $regs;

	$bothSet = true;
	foreach($cmds as $cmd) {
		if (!isset($regs[$cmd['a']]) || !isset($regs[$cmd['b']])) {
			$bothSet = false;
			// echo $cmd['a'] . " or " . $cmd['b'] . " not set yet\n";
			continue;
		}

		$a = $regs[$cmd['a']];
		$b = $regs[$cmd['b']];
		$op = $cmd['op'];
		$out = $cmd['out'];

		if ($op === OP_AND) {
			// echo "ANDing $a and $b\n";
			$regs[$out] = intval($a & $b);
		} else if ($op === OP_OR) {
			// echo "ORing $a and $b\n";
			$regs[$out] = intval($a | $b);
		} else if ($op === OP_XOR) {
			// echo "XORing $a and $b\n";
			$regs[$out] = intval($a ^ $b);
		}

		// echo "result: " . $regs[$out] . "\n";
	}

	return $bothSet;
}