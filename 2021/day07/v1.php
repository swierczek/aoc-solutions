<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

$positions = explode(',', current($lines));



$attempts = [];
foreach($positions as $key => $attempt) {
	$fuel = 0;
	foreach($positions as $posAttempt) {
		$fuel += abs($posAttempt - $attempt);
	}
	$attempts[$key] = $fuel;
}

echo '<pre>';
var_dump(min($attempts));
echo '</pre>';
die();