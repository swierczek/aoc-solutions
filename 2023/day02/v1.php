<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

$limit = [
	'red' => 12,
	'green' => 13,
	'blue' => 14,
];

$gameCount = 0;
foreach($lines as $l) {
	$l = str_replace([':', ',', ';'], '', $l);
	$parts = explode(' ', $l);

	$gameNum = $parts[1];

	$validGame = true;

	for($i=2; $i<count($parts)-1; $i+=2) {
		$num = $parts[$i];
		$type = $parts[$i+1];

		if ($num > $limit[$type]) {
			$validGame = false;
			break;
		}

	}

	if ($validGame) {
		$gameCount += $gameNum;
	}
}

echo '<pre>';
var_dump($gameCount);
echo '</pre>';
die();