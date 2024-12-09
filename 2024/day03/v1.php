<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

$sum = 0;
foreach($lines as $l) {
	preg_match_all('/mul\((\d+),(\d+)\)/', $l, $matches);

	for ($i = 0; $i < count($matches[0]); $i++) {
		$sum += $matches[1][$i] * $matches[2][$i];
	}
}

echo '<pre>';
var_dump($sum);
echo '</pre>';
die();
