<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

$gameSum = 0;
foreach($lines as $l) {
	$l = str_replace([':', ',', ';'], '', $l);
	$parts = explode(' ', $l);

	$blocks = [];

	for($i=2; $i<count($parts)-1; $i+=2) {
		$num = $parts[$i];
		$type = $parts[$i+1];

		if (!isset($blocks[$type])) {
			$blocks[$type] = 0;
		}

		$blocks[$type] = max($blocks[$type], $num);
	}

	$gameProduct = array_product(array_values($blocks));

	$gameSum += $gameProduct;
}

echo '<pre>';
var_dump($gameSum);
echo '</pre>';
die();