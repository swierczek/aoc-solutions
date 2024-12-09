<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

foreach($lines as $key => $l) {
	$lines[$key] = str_split($l);
}

$lowPoints = [];
for($x=0; $x<count($lines); $x++) {
	for($y=0; $y<count($lines[$x]); $y++) {
		if (isLowest($lines, $x, $y)) {
			$lowPoints[] = $lines[$x][$y] + 1;
		}
	}
}

$score = array_sum($lowPoints);

echo '<pre>';
var_dump($score);
echo '</pre>';
die();

function isLowest($lines, $x, $y) {
	$max = 10;

	$val = $lines[$x][$y];

	$up = isset($lines[$x][$y-1]) ? intval($lines[$x][$y-1]) : $max;
	$down = isset($lines[$x][$y+1]) ? intval($lines[$x][$y+1]) : $max;
	$left = isset($lines[$x-1][$y]) ? intval($lines[$x-1][$y]) : $max;
	$right = isset($lines[$x+1][$y]) ? intval($lines[$x+1][$y]) : $max;

	$isLowest = $up > $val
		&& $down > $val
		&& $left > $val
		&& $right > $val;

	return $isLowest;
}