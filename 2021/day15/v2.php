<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$oldMap = array_map('trim', explode("\n", $input));

foreach($oldMap as $key => $l) {
	$oldMap[$key] = str_split($l);
}

// multipliers for building the larger map
$length = count($oldMap);
$height = count($oldMap[0]);

// build the full map [y][x] cuz of how lines/rows are parsed
$map = [];
$mult = 5;
foreach($oldMap as $y => $row) {
	foreach($row as $x => $val) {
		// new 5x grid
		for($j=0; $j<$mult; $j++) {
			for($i=0; $i<$mult; $i++) {
				// extend each of these values 1 length/height each time
				$newX = $x + ($length * $i);
				$newY = $y + ($height * $j);

				// determine value for new extended coordinate
				$value = ($val + $i + $j) % 9;
				$value = $value == 0 ? 9 : $value;

				$map[$newY][$newX] = [
					'dist' => PHP_INT_MAX,
					'prev' => ['x' => null, 'y' => null],
					'visited' => false,
					'x' => $newX,
					'y' => $newY,
					'val' => $value,
				];
			}
		}
	}
}



// use to determine end coordinate
$maxY = count($map) - 1;
$maxX = count($map[0]) - 1;

$map[0][0]['dist'] = 0;
$map[0][0]['visited'] = true;

// add the starting point to the queue and start processing
$queue[] = $map[0][0];

// Dijkstra
while (count($queue) > 0) {
	// get the next shortest node
	$curr = array_pop($queue);

	// for each of its neighbors (u/d/l/r in this case)...
	$neighbors = getNeighbors($curr);

	foreach($neighbors as $n) {
		$x = $n['x'];
		$y = $n['y'];

		$map[$y][$x]['visited'] = true;

		$alt = $curr['dist'] + $n['val'];

		if ($alt < $n['dist']) {
			$map[$y][$x]['dist'] = $alt;
			$map[$y][$x]['prev']['x'] = $curr['x'];
			$map[$y][$x]['prev']['y'] = $curr['y'];
		}

		array_push($queue, $map[$y][$x]);
	}

	// sort the queue by distance descending
	$dist = array_column($queue, 'dist');
	array_multisort(
		$queue, SORT_DESC, SORT_NUMERIC,
		$dist, SORT_DESC, SORT_NUMERIC
	);
}

// use to print the path at the end
// $order = [];
// $node = $map[$maxY][$maxX];
// while($node['prev']['x'] !== null && $node['prev']['y'] !== null) {
// 	$prev = $node['prev'];

// 	$prevX = $prev['x'];
// 	$prevY = $prev['y'];

// 	$x = $node['x'];
// 	$y = $node['y'];
// 	$val = $node['val'];

// 	$newVal = $map[$prevY][$prevX]['val'];

// 	$direction = '';
// 	if ($prevX == $x && $prevY < $y) {
// 		$direction = 'up';
// 	} else if ($prevX == $x && $prevY > $y) {
// 		$direction = 'down';
// 	} else if ($prevX < $x && $prevY == $y) {
// 		$direction = 'left';
// 	} else if ($prevX > $x && $prevY == $y) {
// 		$direction = 'right';
// 	} else {
// 		echo '<pre>';
// 		var_dump($x);
// 		var_dump($y);
// 		var_dump($prevX);
// 		var_dump($prevY);
// 		echo '</pre>';
// 		die();
// 	}

// 	echo "going $direction from ($x, $y) to ($prevX, $prevY), value $val to $newVal".PHP_EOL;

// 	if ($prevX && $prevY) {
// 		$order[] = $map[$prevY][$prevX]['val'];
// 		$node = $map[$prevY][$prevX];
// 	} else {
// 		$node = false;
// 	}
// }
// array_reverse($order);

// echo '<pre>';
// var_dump($order);
// echo '</pre>';
// die();

echo $map[$maxY][$maxX]['dist'];
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

	if (isset($map[$y][$x])) {
		return $map[$y][$x]['visited'] ? false : $map[$y][$x];
	}

	return false;
}

function printGrid($map) {
	foreach($map as $y => $row) {
		foreach($row as $x => $val) {
			echo $map[$y][$x];
		}
		echo PHP_EOL;
	}
}