<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

$grid = [];

const M = 'M';
const A = 'A';
const S = 'S';

foreach($lines as $l) {
	$grid[] = str_split($l);
}

$count = 0;
foreach($grid as $y => $row) {
	foreach($row as $x => $cell) {

		// echo "checking " . $grid[$y][$x] . "\n";

		// M S
		//  A
		// M S
		if (@$grid[$y][$x] === A && @$grid[$y-1][$x-1] === M && @$grid[$y-1][$x+1] === S && @$grid[$y+1][$x+1] === S && @$grid[$y+1][$x-1] === M) {
			$count++;
		}

		// M M
		//  A
		// S S
		if (@$grid[$y][$x] === A && @$grid[$y-1][$x-1] === M && @$grid[$y-1][$x+1] === M && @$grid[$y+1][$x+1] === S && @$grid[$y+1][$x-1] === S) {
			$count++;
		}

		// S M
		//  A
		// S M
		if (@$grid[$y][$x] === A && @$grid[$y-1][$x-1] === S && @$grid[$y-1][$x+1] === M && @$grid[$y+1][$x+1] === M && @$grid[$y+1][$x-1] === S) {
			$count++;
		}

		// S S
		//  A
		// M M
		if (@$grid[$y][$x] === A && @$grid[$y-1][$x-1] === S && @$grid[$y-1][$x+1] === S && @$grid[$y+1][$x+1] === M && @$grid[$y+1][$x-1] === M) {
			$count++;
		}
	}
}

echo '<pre>';
var_dump($count);
echo '</pre>';
die();
