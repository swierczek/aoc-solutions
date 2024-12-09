<?php

$filename = $argv[1] ?? 'testinput.txt';

define('MULTIPLIER', 4000000);
if ($filename == 'input.txt') {
	define('MAX', 4000000);
} else {
	define('MAX', 20);
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

// need to do this line by line, but now we need to deal with ranges

// create hashmap of sensor areas that cover the line
for($i=0; $i<=MAX; $i++) {
	echo "checking line $i\n";

	$line = [];
	foreach($sensors as $key => $val) {
		// if ($val->x == 16 && $val->y == 7) {
			// echo "looking at sensor at $val->x, $val->y, size $val->size\n";

			// distance to the line
			$distance = abs($i - $val->y);

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

			$line[] = new Range($start, $end);

			// echo "start/end is $start, $end\n";
		// }
	}

	// now sort the ranges and merge them if possible
	usort($line, function ($a, $b) {
		return $a->start >= $b->start;
	});

	$newRange = '';
	foreach($line as $key => $val) {
		if ($key == 0) {
			$newRange = $val;
		} else {
			$newRange = mergeRanges($newRange, $val);

			if (is_int($newRange)) {
				echo "solution found at $newRange, $i\n";

				$answer = ($newRange * MULTIPLIER) + $i;

				var_dump($answer);
				die();
			}
		}
	}
}




class Range {
	public $start;
	public $end;

	function __construct(int $start, int $end) {
		if ($end < $start) {
			throw new Exception('Invalid range start/end: '.$start.':'.$end);
		}
		$this->start = $start;
		$this->end = $end;
	}
}

function mergeRanges(Range $r1, Range $r2)
{
	// r1 completely covers r2, return r1
	if ($r1->start <= $r2->start && $r1->end >= $r2->end) {
		// echo "r1 completely covers r2\n";
		return $r1;

	// r2 completely covers r1, return r2
	} else if ($r2->start <= $r1->start && $r2->end >= $r1->end) {
		// echo "r2 completely covers r1\n";
		return $r2;

	// r1 overlaps r2, return new range
	} else if ($r1->start <= $r2->start && $r1->end <= $r2->end && $r1->end >= $r2->start) {
		// echo "r1 overlaps r2\n";
		return new Range(min($r1->start, $r2->start), max($r1->end, $r2->end));

	// r1 doesn't overlap r2, return 2 ranges (is this the answer then?)
	} else {
		echo '<pre>';
		var_dump('no overlap??');
		var_dump($r1);
		var_dump($r2);
		echo '</pre>';
		// die();

		return $r1->end + 1;
	}
}
