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
			'visited' => false,
		];

		$grid[$y][$x] = $node;

		if ($cell === START) {
			$starts[] = $node;
		}
	}
}

// starts = [[x: 3, y: 0, val: 0]]

$resetGrid = $grid; // copy of this to make resetting all to visited easy?

$scores = [];
foreach($starts as $start) {
	$stack = [$start];

	// reset all as not visited
	$grid = $resetGrid;

	$score = 0;
	$loop = 0;
	while (count($stack) > 0 && $loop < 1000) {
		$curr = array_pop($stack);
		$next = $curr['val'] + 1;

		$x = $curr['x'];
		$y = $curr['y'];

		$u = @$grid[$y-1][$x];
		$r = @$grid[$y][$x+1];
		$d = @$grid[$y+1][$x];
		$l = @$grid[$y][$x-1];

		if (@$u['val'] === $next && !$u['visited']) {
			// echo "adding down\n";
			@$grid[$y-1][$x]['visited'] = true;
			$stack[] = @$grid[$y-1][$x];
		}

		if (@$r['val'] === $next && !$r['visited']) {
			// echo "adding right\n";
			@$grid[$y][$x+1]['visited'] = true;
			$stack[] = @$grid[$y][$x+1];
		}

		if (@$d['val'] === $next && !$d['visited']) {
			// echo "adding down\n";
			@$grid[$y+1][$x]['visited'] = true;
			$stack[] = @$grid[$y+1][$x];
		}

		if (@$l['val'] === $next && !$l['visited']) {
			// echo "adding left\n";
			@$grid[$y][$x-1]['visited'] = true;
			$stack[] = @$grid[$y][$x-1];
		}

		$loop++;

		if ($curr['val'] === END) {
			$score++;
		}
	}

	$scores[] = $score;
}

echo '<pre>';
var_dump($scores);
var_dump(array_sum($scores));
echo '</pre>';
die();
