<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

$positions = explode(',', current($lines));



$min = min($positions);
$max = max($positions);

//try all positions
$allPositions = array_fill($min, $max, 0);

foreach($allPositions as $key => $val) {
	$fuel = 0;
	foreach($positions as $attempt) {

		$diff = abs($key - $attempt);

		$fuel += ($diff / 2)  * ($diff + 1);

	}

	$allPositions[$key] = $fuel;
}

echo '<pre>';
var_dump(min($allPositions));
echo '</pre>';
die();