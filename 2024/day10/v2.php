<?php

$inputPath = str_replace('/aoc-solutions/', '/aoc-inputs/', getcwd()) . '/';
$filename = $inputPath . ($argv[1] ?? 'testinput.txt');

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

const SPACE = '.';
const START = 0;
const END = 9;

$height = count($lines);
$width = strlen($lines[0]);

$grid = [];
$starts = [];
foreach($lines as $y => $l) {
	$row = str_split($l);

	foreach($row as $x => $cell) {
		$cell = $cell === SPACE ? $cell : intval($cell);

		$node = [
			'x' => $x,
			'y' => $y,
			'val' => $cell,
			// 'visited' => false,
		];

		$grid[$y][$x] = $node;

		if ($cell === START) {
			$starts[] = $node;
		}
	}
}

// testing!
// clear out all except for index...
// $checkKey = 8;
// foreach($starts as $key => $cell) {
// 	if ($key !== $checkKey) {
// 	// if ($cell['x'] !== $checkX || $cell['y'] !== $checkY) {
// 		unset($starts[$key]);
// 	}
// }

// starts = [[x: 3, y: 0, val: 0]]

$scores = [];
$cleared = false;
foreach($starts as $start) {
	echo "starting at node " . $start['y'] . ", " . $start['x'] . "\n";
	$stack = [$start];

	$score = 0;
	while (count($stack) > 0) {
		// get the latest but leave it on the stack
		$curr = array_pop($stack);
		$stack[] = $curr;

		$next = $curr['val'] + 1;

		$x = $curr['x'];
		$y = $curr['y'];

		$u = @$grid[$y-1][$x];
		$r = @$grid[$y][$x+1];
		$d = @$grid[$y+1][$x];
		$l = @$grid[$y][$x-1];

		$found = false;

		if (@$u['val'] === $next) {
			echo "adding ".$u['val']." up\n";
			$stack[] = $u;
			$found = true;
		}

		if (@$r['val'] === $next) {
			echo "adding ".$r['val']." right\n";
			$stack[] = $r;
			$found = true;
		}

		if (@$d['val'] === $next) {
			echo "adding ".$d['val']." down\n";
			$stack[] = $d;
			$found = true;
		}

		if (@$l['val'] === $next) {
			echo "adding ".$l['val']." left\n";
			$stack[] = $l;
			$found = true;
		}

		if ($curr['val'] === END) {
			echo "found end!\n";

			$score++;
			$stack = clearStackToLatestBranch($stack);

		} else if (!$found) {
			echo "no next nodes found, clearing stack\n";

			$stack = clearStackToLatestBranch($stack);
		}
	}

	$scores[] = $score;
}

echo '<pre>';
var_dump($scores);
var_dump(array_sum($scores));
echo '</pre>';
die();

function clearStackToLatestBranch($stack) {
	$prev1 = array_pop($stack); // remove the end node

	// clear the stack back to the latest branch
	while ($prev2 = array_pop($stack)) {
		echo "clearing " . $prev1['val'] . " from the stack\n";

		if ($prev1['val'] === $prev2['val']) {
			// add prev2 back to the stack to continue that branch
			$stack[] = $prev2;
			echo "stack cleared back to " . $prev2['val'] . " branch\n";

			break;
		}

		$prev1 = $prev2;
	}

	return $stack;
}