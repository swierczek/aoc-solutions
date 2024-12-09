<?php

$inputPath = str_replace('/aoc-solutions/', '/aoc-inputs/', getcwd()) . '/';
$filename = $inputPath . ($argv[1] ?? 'testinput.txt');

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

const BLANK = '.';

$height = count($lines);
$width = strlen($lines[0]);

$frequencies = [];
foreach($lines as $y => $l) {
	foreach(str_split($l) as $x => $cell) {
		if ($cell !== BLANK) {
			$frequencies['cell-' . $cell][] = [
				'x' => $x,
				'y' => $y,
			];
		}
	}
}

// calculate the antinodes
$antinodes = [];
foreach($frequencies as $key => $f) {

	// compare each freq to each other freq to calculate the diff
	for ($i=0; $i<count($f); $i++) {
		$diffs = [];

		for ($j=$i+1; $j<count($f); $j++) {
			$ant1 = $f[$i];
			$ant2 = $f[$j];

			$dx = $ant1['x'] - $ant2['x'];
			$dy = $ant1['y'] - $ant2['y'];

			// var_dump($ant1);
			// var_dump($ant2);
			// var_dump($dx);
			// var_dump($dy);
			// echo "--------\n";

			$ox = $ant1['x'];
			$oy = $ant1['y'];

			while (
				(0 <= $ox + $dx && $ox + $dx < $width)
				&& (0 <= $oy + $dy && $oy + $dy < $height)
			) {
				$xDiff = $ox + $dx;
				$yDiff = $oy + $dy;

				echo "antinode at $xDiff, $yDiff\n";

				$antinodes[$xDiff . ',' . $yDiff] = true;

				$ox += $dx;
				$oy += $dy;
			}

			$ox = $ant2['x'];
			$oy = $ant2['y'];

			while (
				(0 <= $ox - $dx && $ox - $dx < $width)
				&& (0 <= $oy - $dy && $oy - $dy < $height)
			) {
				$xDiff = $ox - $dx;
				$yDiff = $oy - $dy;

				echo "antinode at $xDiff, $yDiff\n";

				$antinodes[$xDiff . ',' . $yDiff] = true;

				$ox -= $dx;
				$oy -= $dy;
			}
		}
	}
}

// then add all frequencies as antinodes
foreach($frequencies as $key => $freq) {
	foreach($freq as $f) {
		$antinodes[$f['x'] . ',' . $f['y']] = true;
	}
}

echo '<pre>';
var_dump(count($antinodes));
echo '</pre>';
die();