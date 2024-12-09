<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

$symbols = [];
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
		} else {
			$symbols[$key] = $val;
		}
	}
}

// now check all numbers to see if there is a symbol around it anywhere
foreach($numbers as $coords => $val) {
	$split = explode(',', $coords);
	$x = $split[0];
	$y = $split[1];

	if (!hasSymbol($x, $y, $val, $symbols)) {
		// echo 'unsetting ' . $val . "\n";
		unset($numbers[$coords]);
	}
}

echo '<pre>';
var_dump(array_sum($numbers));
echo '</pre>';
die();

function hasSymbol($x, $y, $val, $symbols): bool
{
	$len = strlen($val);

	if (
		// check left side
		isset($symbols[($x-1).','.($y-1)])
		|| isset($symbols[($x-1).','.($y)])
		|| isset($symbols[($x-1).','.($y+1)])

		// check right side
		|| isset($symbols[($x+$len).','.($y-1)])
		|| isset($symbols[($x+$len).','.($y)])
		|| isset($symbols[($x+$len).','.($y+1)])
	) {
		return true;
	}

	// check top/bottom
	for($i=0; $i<$len; $i++) {
		if (
			isset($symbols[($x+$i).','.($y-1)])
			|| isset($symbols[($x+$i).','.($y+1)])

		) {
			return true;
		}
	}

	return false;
}