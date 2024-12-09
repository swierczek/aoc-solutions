<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_filter(array_map('trim', explode("\n", $input)));

$left = [];
$right = [];
foreach($lines as $l) {
	$nums = array_values(array_filter(explode(" ", $l)));

	$left[] = intval($nums[0]);
	$right[] = intval($nums[1]);
}

sort($left);
sort($right);

$sum = 0;
foreach($left as $key => $val) {
	$sum += abs($left[$key] - $right[$key]);
}

echo '<pre>';
var_dump($sum);
echo '</pre>';
die();