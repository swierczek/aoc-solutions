<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

const ADD = '+';
const MULT = '*';
const CONCAT = '||';

$ops = [ADD, MULT, CONCAT];

$eqs = [];
foreach($lines as $l) {
	$split = explode(': ', $l);

	$nums = array_map('intval', explode(' ', $split[1]));

	$eqs[] = [
		'sum' => intval($split[0]),
		'nums' => $nums,
	];
}

$correct = [];
foreach($eqs as $eq) {
	$list = $eq['nums'];

	$stack = [
		$list[0]
	];

	// for each num in list...
	for($i=1; $i<count($list); $i++) {
		// ...get the next num...
		$num2 = $list[$i];

		// ... then iterate over the stack to calculate the possible equations
		$newStack = [];
		foreach($stack as $index => $num1) {
			foreach($ops as $op) {
				if ($op === ADD) {
					$result = $num1 + $num2;
					// echo "$num1 + $num2 = $result\n";
				} else if ($op === MULT) {
					$result = $num1 * $num2;
					// echo "$num1 * $num2 = $result\n";
				} else if ($op === CONCAT) {
					$result = intval("" . $num1 . $num2);
					// echo "$num1 . $num2 = $result\n";
				}

				// don't bother down this path if the sum is already too big
				if ($result > $eq['sum']) {
					continue;
				}

				$newStack[] = $result;
				// echo "adding to new stack\n";
			}

		}

		$stack = $newStack;
		// echo "resetting stack\n";
	}

	// check which sums match the calculated variants
	foreach($stack as $v) {
		if ($v === $eq['sum']) {
			$correct[] = $v;
			break;
		}
	}
}

echo '<pre>';
var_dump(array_sum($correct));
echo '</pre>';
die();

/*

add 81 to stack
count < 2, add another
81 40 in stack, calculate both sums and add them to the variants
81 + 40 = 121
81 * 40 = 3240

iterate over variants
121
3240

121, add to stack
121 27
calculate both sums and add
*/