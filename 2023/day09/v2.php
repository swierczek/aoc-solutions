<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

$sum = 0;
foreach($lines as $l) {
	$row = explode(' ', $l);

	echo '<pre>';
	var_dump($row);
	echo '</pre>';
	// die();

	$diff = findDiff($row);

	echo "$diff extrapolated\n";

	$sum += $diff;
}

echo '<pre>';
var_dump($sum);
echo '</pre>';
die();

function findDiff($row)
{
	// if all values are 0, return
	if (@array_count_values($row)[0] === count($row) || count($row) <= 1) {
		echo "returning 0\n";
		return 0;
	}

	$newRow = [];
	for($i=0; $i<count($row)-1; $i++) {
		$newRow[] = $row[$i] - $row[$i+1];
	}

	echo '<pre>';
	var_dump('new row');
	var_dump($newRow);
	echo '</pre>';
	// die();

	// return new sum
	$newVal = findDiff($newRow);

	echo "adding $newVal to {$row[0]}\n";

	return $newVal + $row[0];
}