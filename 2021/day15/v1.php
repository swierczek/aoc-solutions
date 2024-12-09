<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$map = array_map('trim', explode("\n", $input));
$maxY = count($map) - 1;

foreach($map as $key => $l) {
	$map[$key] = str_split($l);
	$maxX = count($map[$key]) - 1;
}

foreach($map as $x => $row) {
	foreach($row as $y => $val) {
		$map[$x][$y] = [
			'dist' => PHP_INT_MAX,
			'prev' => ['x' => null, 'y' => null],
			'visited' => false,
			'x' => $x,
			'y' => $y,
			'val' => $val,
		];
	}
}

$map[0][0]['dist'] = 0;
$map[0][0]['visited'] = true;

// add the starting point to the queue and start processing
$queue[] = $map[0][0];

while (count($queue) > 0) {
	$curr = array_pop($queue);

	$neighbors = getNeighbors($curr);

	foreach($neighbors as $n) {
		$x = $n['x'];
		$y = $n['y'];

		$map[$x][$y]['visited'] = true;

		$alt = $curr['dist'] + $n['val'];

		if ($alt < $n['dist']) {
			$map[$x][$y]['dist'] = $alt;
			$map[$x][$y]['prev']['x'] = $curr['x'];
			$map[$x][$y]['prev']['y'] = $curr['y'];
		}

		array_push($queue, $map[$x][$y]);
	}

	// sort the queue by distance descending
	$dist = array_column($queue, 'dist');
	array_multisort(
		$queue, SORT_DESC, SORT_NUMERIC,
		$dist, SORT_DESC, SORT_NUMERIC
	);
}

echo $map[$maxX][$maxY]['dist'];
die();



function getNeighbors($node) {
	$neighbors = [];

	$x = $node['x'];
	$y = $node['y'];

	$up = getNode($x, $y-1);
	$down = getNode($x, $y+1);
	$left = getNode($x-1, $y);
	$right = getNode($x+1, $y);

	foreach([$up, $down, $left, $right] as $n) {
		if ($n !== false) {
			$neighbors[] = $n;
		}
	}

	return $neighbors;
}


function getNode($x, $y) {
	global $map;

	if (isset($map[$x][$y])) {
		return $map[$x][$y]['visited'] ? false : $map[$x][$y];
	}

	return false;
}
