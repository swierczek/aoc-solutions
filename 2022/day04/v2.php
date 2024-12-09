<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

const MIN = 0;
const MAX = 1;

$count = 0;
foreach($lines as $line) {
	$pairs = explode(',', $line);

	$section1 = explode('-', $pairs[0]);
	$section2 = explode('-', $pairs[1]);

	if (
		$section1[MAX] < $section2[MIN]
		|| $section2[MAX] < $section1[MIN]
	) {
		// no overlap
	} else {
		$count++;
	}
}

echo '<pre>';
var_dump($count);
echo '</pre>';
die();
