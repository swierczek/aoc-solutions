<?php

$inputPath = str_replace('/aoc-solutions/', '/aoc-inputs/', getcwd()) . '/';
$filename = $inputPath . ($argv[1] ?? 'testinput.txt');

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

const A = 0;
const B = 1;
const C = 2;

$regs = [
	0,
	0,
	0,
];
$program = [];
foreach($lines as $l) {
	if (preg_match('/Register A: (\d+)/', $l, $matches)) {
		$regs[0] = intval($matches[1]);
	} else if (preg_match('/Register B: (\d+)/', $l, $matches)) {
		$regs[1] = intval($matches[1]);
	} else if (preg_match('/Register C: (\d+)/', $l, $matches)) {
		$regs[2] = intval($matches[1]);
	} else if (preg_match('/Program: ((\d+,?)+)/', $l, $matches)) {
		$program = array_map('intval', explode(',', $matches[1]));
	}
}

$output = [];
for($i=0; $i<count($program)-1; $i+=2) {
	$opcode = $program[$i];
	$operand = $program[$i+1];

	// echo "opcode $opcode, operand $operand\n";

	switch($opcode) {
		case 0: // adv
			$regs[A] = intval($regs[A] / (pow(2, combo($operand))));
			// echo "op $opcode set A to " . $regs[A] . "\n";
			break;
		case 1: // bxl
			$regs[B] = $regs[B] ^ $operand;
			// echo "op $opcode set B to " . $regs[B] . "\n";
			break;
		case 2: // bst
			$regs[B] = combo($operand) % 8;
			// echo "op $opcode set B to " . $regs[B] . "\n";
			break;
		case 3: // jnz
			if ($regs[A] !== 0) {
				$i = $operand - 2; // maybe
			}
			// echo "op $opcode jumping to " . $i . "\n";
			break;
		case 4: // bxc
			$regs[B] = $regs[B] ^ $regs[C];
			// echo "op $opcode set B to " . $regs[B] . "\n";
			break;
		case 5: // out
			$val = combo($operand) % 8;
			$output[] = $val;
			// echo "op $opcode adding to output " . $val . "\n";
			break;
		case 6: // bdv
			$regs[B] = intval($regs[A] / (pow(2, combo($operand))));
			// echo "op $opcode set B to " . $regs[B] . "\n";
			break;
		case 7: // cdv
			$regs[C] = intval($regs[A] / (pow(2, combo($operand))));
			// echo "op $opcode set C to " . $regs[C] . "\n";
			break;
	}

	// echo "\n";
}

echo implode(',', $output) . "\n";

// echo '<pre>';
// var_dump($regs);
// var_dump($program);
// echo '</pre>';
die();

function combo(int $opcode): int
{
	global $regs;

	if ($opcode <= 3) {
		return $opcode;
	} else if ($opcode === 4) {
		return $regs[0];
	} else if ($opcode === 5) {
		return $regs[1];
	} else if ($opcode === 6) {
		return $regs[2];
	// } else if ($opcode === 7) {
		// return -1
	}
}