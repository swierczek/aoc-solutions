<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

$grid = [];

const X = 'X';
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

		// right
		if (@$grid[$y][$x] === X && @$grid[$y][$x+1] === M && @$grid[$y][$x+2] === A && @$grid[$y][$x+3] === S) {
			// echo "starting at $y, $x = right\n";
			$count++;
		}

		// left
		if (@$grid[$y][$x] === X && @$grid[$y][$x-1] === M && @$grid[$y][$x-2] === A && @$grid[$y][$x-3] === S) {
			// echo "starting at $y, $x = left\n";
			$count++;
		}

		// up
		if (@$grid[$y][$x] === X && @$grid[$y-1][$x] === M && @$grid[$y-2][$x] === A && @$grid[$y-3][$x] === S) {
			// echo "starting at $y, $x = up\n";
			$count++;
		}

		// down
		if (@$grid[$y][$x] === X && @$grid[$y+1][$x] === M && @$grid[$y+2][$x] === A && @$grid[$y+3][$x] === S) {
			// echo "starting at $y, $x = down\n";
			$count++;
		}

		// NE
		if (@$grid[$y][$x] === X && @$grid[$y-1][$x+1] === M && @$grid[$y-2][$x+2] === A && @$grid[$y-3][$x+3] === S) {
			// echo "starting at $y, $x = NE\n";
			$count++;
		}

		// SE
		if (@$grid[$y][$x] === X && @$grid[$y+1][$x+1] === M && @$grid[$y+2][$x+2] === A && @$grid[$y+3][$x+3] === S) {
			// echo "starting at $y, $x = SE\n";
			$count++;
		}

		// SW
		if (@$grid[$y][$x] === X && @$grid[$y+1][$x-1] === M && @$grid[$y+2][$x-2] === A && @$grid[$y+3][$x-3] === S) {
			// echo "starting at $y, $x = SW\n";
			$count++;
		}

		// NW
		if (@$grid[$y][$x] === X && @$grid[$y-1][$x-1] === M && @$grid[$y-2][$x-2] === A && @$grid[$y-3][$x-3] === S) {
			// echo "starting at $y, $x = NW\n";
			$count++;
		}
	}
}

echo '<pre>';
var_dump($count);
echo '</pre>';
die();
