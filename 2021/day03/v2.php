<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);



$lines = array_map('trim', explode("\n", $input));

$index = 0;
while (count($lines) > 1) {

	$oxygenBit = calculateBinaryIndexes($lines, $index);

	foreach($lines as $key => $line) {
		if ($line[$index] != $oxygenBit) {
			unset($lines[$key]);
		}
	}

	$index++;
}

$oxygen = bindec(current($lines));




$lines = array_map('trim', explode("\n", $input));

$index = 0;
while (count($lines) > 1) {

	$co2Bit = calculateBinaryIndexes($lines, $index, false);

	foreach($lines as $key => $line) {
		if ($line[$index] != $co2Bit) {
			unset($lines[$key]);
		}
	}

	$index++;
}

$co2 = bindec(current($lines));

echo '<pre>';
var_dump($co2 * $oxygen);
echo '</pre>';
die();



function calculateBinaryIndexes(array $lines, $index = 0, $returnOxygen = true) {
	$binary = [];
	foreach($lines as $line) {
		$bin = str_split($line);

		foreach($bin as $key => $b) {
			if (!isset($binary[$key])) {
				$binary[$key] = 0;
			}

			$binary[$key] += $b;
		}
	}

	$oxygenBit = ($binary[$index] >= count($lines) / 2) ? 1 : 0;
	$co2Bit = $oxygenBit ? 0 : 1;

	return $returnOxygen ? $oxygenBit : $co2Bit;
}