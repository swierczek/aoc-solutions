<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

$sums = [0];
$i = 0;
foreach($lines as $l) {
	if ($l === '') {
		$i++;
		$sums[$i] = 0;
	}

	$sums[$i] += $l;
}

rsort($sums);

echo '<pre>';
var_dump($sums[0]);
echo '</pre>';
die();