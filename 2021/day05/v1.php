<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

// find the largest x/y to know how big of a grid to build
$largestX = 0;
$largestY = 0;
foreach($lines as $l) {
	list($before, $after) = explode(' -> ', $l);

	list($xi, $yi) = explode(',', $before);
	list($xo, $yo) = explode(',', $after);

	if ($xi > $largestX) {
		$largestX = $xi;
	} else if ($xo > $largestX) {
		$largestX = $xo;
	}

	if ($yi > $largestY) {
		$largestY = $yi;
	} else if ($yo > $largestY) {
		$largestY = $yo;
	}
}

// build the starting grid
$grid = array_fill(0, $largestX+1, 0);
foreach($grid as $key => $g) {
	$grid[$key] = array_fill(0, $largestY+1, 0);
}

// populate the array
foreach($lines as $l) {
	list($before, $after) = explode(' -> ', $l);

	list($xi, $yi) = explode(',', $before);
	list($xo, $yo) = explode(',', $after);

	if ($xi != $xo && $yi != $yo) {
		continue;
	}

	if ($xi != $xo) {
		if ($xi > $xo) {
			$temp = $xi;
			$xi = $xo;
			$xo = $temp;
		}
		for ($i=$xi; $i<=$xo; $i++) {
			$grid[$i][$yi]++;
		}
	}

	if ($yi != $yo) {
		if ($yi > $yo) {
			$temp = $yi;
			$yi = $yo;
			$yo = $temp;
		}
		for ($i=$yi; $i<=$yo; $i++) {
			$grid[$xi][$i]++;
		}
	}

	// printGrid($grid);
	// die();
}

$score = scoreGrid($grid);

echo '<pre>';
var_dump($score);
echo '</pre>';
die();

function printGrid(array $grid) {
	foreach($grid as $row) {
		foreach($row as $col) {
			echo $col.' ';
		}

		echo "\n";
	}

	echo "\n\n";
}

function scoreGrid(array $grid) {
	$score = 0;
	foreach($grid as $row) {
		foreach($row as $col) {
			if ($col >= 2) {
				$score++;
			}
		}
	}

	return $score;
}