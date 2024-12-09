<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

$sum = 0;
foreach($lines as $l) {
	$nums = str_split(filter_var($l, FILTER_SANITIZE_NUMBER_INT));
	$sum += (10 * $nums[0]) + $nums[count($nums) - 1];
}

echo '<pre>';
var_dump($sum);
echo '</pre>';
die();