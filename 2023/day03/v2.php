<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

$gears = [];
$numbers = [];

foreach($lines as $y => $l) {
	preg_match_all('/([\d]+|[^\.])/', $l, $matches, PREG_OFFSET_CAPTURE);

	// no matches found?
	if (count($matches[1]) === 0) {
		continue;
	}

	$match = $matches[1];

	foreach($match as $m) {
		$val = $m[0];
		$x = $m[1];

		$key = $x . ',' . $y;

		if (intval($val) !== 0) {
			$numbers[$key] = $val;
		} else if ($val === '*') {
			$gears[$key] = []; // list of numbers around the gear
		}
	}
}

foreach($numbers as $coords => $val) {
	$split = explode(',', $coords);
	$x = $split[0];
	$y = $split[1];

	findGears($x, $y, $val, $gears);
}

foreach ($gears as $i => $vals) {
	if (count($vals) !== 2) {
		unset($gears[$i]);
	} else {
		$gears[$i] = array_product($vals);
	}
}

echo '<pre>';
var_dump(array_sum($gears));
echo '</pre>';
die();

function findGears($x, $y, $val, &$gears): void
{
	$len = strlen($val);

	// left side
	if (isset($gears[($x-1).','.($y-1)])) {
		$gears[($x-1).','.($y-1)][] = $val;
	}
	if (isset($gears[($x-1).','.($y)])) {
		$gears[($x-1).','.($y)][] = $val;
	}
	if (isset($gears[($x-1).','.($y+1)])) {
		$gears[($x-1).','.($y+1)][] = $val;
	}

	// right side
	if (isset($gears[($x+$len).','.($y-1)])) {
		$gears[($x+$len).','.($y-1)][] = $val;
	}
	if (isset($gears[($x+$len).','.($y)])) {
		$gears[($x+$len).','.($y)][] = $val;
	}
	if (isset($gears[($x+$len).','.($y+1)])) {
		$gears[($x+$len).','.($y+1)][] = $val;
	}

	// check top/bottom
	for($i=0; $i<$len; $i++) {
		if (isset($gears[($x+$i).','.($y-1)])) {
			$gears[($x+$i).','.($y-1)][] = $val;
		}
		if (isset($gears[($x+$i).','.($y+1)])) {
			$gears[($x+$i).','.($y+1)][] = $val;
		}
	}
}