<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

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

foreach($binary as $key => $bin) {
	$binary[$key] = $bin > count($lines) / 2 ? 1 : 0;
}

$gamma = bindec(intval(implode('', $binary)));
$epsilon = bindec(intval(strtr(implode('', $binary),[1,0])));

echo '<pre>';
var_dump($gamma * $epsilon);
echo '</pre>';
die();
