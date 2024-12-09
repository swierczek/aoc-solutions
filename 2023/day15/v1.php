<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$commands = explode(',', $input);

$values = [];
foreach($commands as $command) {
	$chars = str_split($command);
	$value = 0;

	foreach($chars as $c) {
		$value += ord($c);
		$value *= 17;
		$value = $value % 256;
	}

	$values[] = $value;
}

echo '<pre>';
var_dump(array_sum($values));
echo '</pre>';
die();
