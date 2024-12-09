<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

$sum = 0;
$enabled = true;
foreach($lines as $l) {
	preg_match_all('/mul\((\d+),(\d+)\)|do\(\)|don\'t\(\)/', $l, $matches);

	for ($i = 0; $i < count($matches[0]); $i++) {
		if (stripos($matches[0][$i], "don't") === 0) {
			echo "disabling " . $matches[0][$i] . "\n";
			$enabled = false;
		} else if (stripos($matches[0][$i], "do") === 0) {
			echo "enabling " . $matches[0][$i] . "\n";
			$enabled = true;
		} else if ($enabled) {
			echo "multiplying " . $matches[0][$i] . "\n";
			$sum += $matches[1][$i] * $matches[2][$i];
		} else {
			echo "skipping " . $matches[0][$i] . "\n";
		}
	}
}

echo '<pre>';
var_dump($sum);
echo '</pre>';
die();
