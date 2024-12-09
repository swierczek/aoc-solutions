<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

$scores = 0;
foreach($lines as $line) {
	$midpoint = strlen($line) / 2;

	$comp1 = str_split(substr($line, 0, $midpoint));
	$comp2 = str_split(substr($line, $midpoint));

	$overlap = array_unique(array_intersect($comp1, $comp2));

	foreach($overlap as $o) {
		$value = ord($o);

		// already uppercase
		if (strtoupper($o) === $o) {
			$value += -65 + 1 + 26; // A ascii code, +1, +26
		} else {
			$value += -97 + 1; // a ascii code, +1
		}

		$scores += $value;
	}
}

echo '<pre>';
var_dump($scores);
echo '</pre>';
die();