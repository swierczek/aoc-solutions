<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

$scores = 0;

$bag1 = [];
$bag2 = [];
$bag3 = [];

foreach($lines as $line) {
	if (count($bag1) === 0) {
		$bag1 = str_split($line);
	} else if (count($bag2) === 0) {
		$bag2 = str_split($line);
	} else if (count($bag3) === 0) {
		$bag3 = str_split($line);

		// bags are filled, figure out the similar badge now
		$overlap = array_unique(array_intersect(array_intersect($bag1, $bag2), $bag3));

		foreach($overlap as $o) {
			$value = ord($o);

			// already uppercase
			if (strtoupper($o) === $o) {
				$value += -65 + 1 + 26; // A ascii code (+1), +26
			} else {
				$value += -97 + 1; // a ascii code (+1)
			}

			$scores += $value;
		}

		$bag1 = [];
		$bag2 = [];
		$bag3 = [];
	}
}

echo '<pre>';
var_dump($scores);
echo '</pre>';
die();