<?php

$filename = $argv[1] ?? 'testinput.txt';

if ($filename == 'input.txt') {
	define('LINE', 2000000);
} else {
	define('LINE', 10);
}

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));



// create hashmap of sensors and their distance to closest beacon (size)
$sensors = [];
$beacons = [];
foreach($lines as $line) {
	preg_match('/Sensor at x=(-?\d+), y=(-?\d+): closest beacon is at x=(-?\d+), y=(-?\d+)/', $line, $matches);

	$sx = intval($matches[1]);
	$sy = intval($matches[2]);
	$bx = intval($matches[3]);
	$by = intval($matches[4]);

	$size = abs($sx - $bx) + abs($sy - $by);

	$sensors[$sx.','.$sy] = (object) [
		'x' => $sx,
		'y' => $sy,
		'size' => $size,
	];

	$beacons[$bx.','.$by] = 'B';
}

// create hashmap of sensor areas that cover the line
$line = [];
foreach($sensors as $key => $val) {
	// if ($val->x == 16 && $val->y == 7) {
		// echo "looking at sensor at $val->x, $val->y, size $val->size\n";

		// distance to the line
		$distance = abs(LINE - $val->y);

		// echo "distance is $distance\n";

		// if the distance is more than the size of the sensor, this sensor isn't relevant (i.e. width would be negative)
		if ($distance > $val->size) {
			// echo "skipping beacon\n";
			continue;
		}

		// calculate the width of the beacon on the line
		$width = $val->size - $distance;

		// echo "width is $width\n";

		$start = $val->x - $width;
		$end = $val->x + $width;

		// echo "start/end is $start, $end\n";

		for($x=$start; $x<=$end; $x++) {
			$line[$x.','.LINE] = true;
		}
	// }
}

// unset all spaces that are known beacons
foreach($beacons as $key => $val) {
	unset($line[$key]);
}

echo '<pre>';
var_dump(count($line));
echo '</pre>';
die();
