<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

foreach($lines as $key => $l) {
	$lines[$key] = str_split($l);
}

$basins = [];
$visited = [];
$lowPoints = [];

// find the lowest points
for($x=0; $x<count($lines); $x++) {
	for($y=0; $y<count($lines[$x]); $y++) {
		if (isLowest($x, $y)) {
			$lowPoints[] = [
				'x' => $x,
				'y' => $y,
				'val' => $lines[$x][$y],
			];
		}
	}
}

// at this point $lowPoints is populated. Now we need to discover basins and count them.
foreach($lowPoints as $p) {
	$basins[] = discoverBasins($p['x'], $p['y'], 0);
}

// sort descending and slice to length 3
arsort($basins);

$products = array_slice($basins, 0, 3);

echo array_product($products).PHP_EOL;
exit();



// true if the 4 adjacent cells are all higher than this one
function isLowest($x, $y) {
	global $lines;

	$val = $lines[$x][$y];

	$up = getVal($x, $y-1);
	$down = getVal($x, $y+1);
	$left = getVal($x-1, $y);
	$right = getVal($x+1, $y);

	$isLowest = $up > $val
		&& $down > $val
		&& $left > $val
		&& $right > $val;

	return $isLowest;
}

function discoverBasins($x, $y, $basin) {
	global $visited;

	// end case (we've searched all adjacent directions)
	// or if the value is >=9
	if ((isset($visited[$x][$y]) && $visited[$x][$y] === true) || getVal($x, $y) >= 9) {
		return $basin;
	}

	// mark visited to determine next direction
	$visited[$x][$y] = true;

	// count this as part of the basin
	$basin++;

	// check the 4 directions recursively
	$basin = discoverBasins($x-1, $y, $basin);
	$basin = discoverBasins($x+1, $y, $basin);
	$basin = discoverBasins($x, $y-1, $basin);
	$basin = discoverBasins($x, $y+1, $basin);

	return $basin;
}

function getVal($x, $y) {
	global $lines;
	return isset($lines[$x][$y]) ? intval($lines[$x][$y]) : 10;
}