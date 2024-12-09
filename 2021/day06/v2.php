<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

$fish = explode(',', $lines[0]);

$fishCount = array_fill(0, 9, 0);

foreach($fish as $f) {
	$fishCount[$f]++;
}

// printFish($fishCount);

// # days
$numDays = 256;
for ($i=0; $i<$numDays; $i++) {
	// backwards over all fish days
	$new = $fishCount[0];
	for($j=0; $j<8; $j++) {
		$fishCount[$j] = $fishCount[$j+1];
	}
	$fishCount[6] += $new;
	$fishCount[8] = $new;

	// printFish($fishCount);
}

function printFish(array $arr) {
	echo implode(',', $arr).PHP_EOL;
}

echo '<pre>';
var_dump(array_sum($fishCount));
echo '</pre>';
die();