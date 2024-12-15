<?php

$inputPath = str_replace('/aoc-solutions/', '/aoc-inputs/', getcwd()) . '/';
$filename = $inputPath . ($argv[1] ?? 'testinput.txt');

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

$grid = [];
foreach($lines as $y => $l) {
	foreach(str_split($l) as $x => $cell) {
		$grid[$y][$x] = [
			'y' => $y,
			'x' => $x,
			'val' => $cell,
			'visited' => false,
		];
	}
}

$regions = [];
$regionsRef = [];
foreach($grid as $tempY => $row) {
	foreach($row as $tempX => $cell) {
		// echo "checking " .$cell['val']. " at $tempY, $tempX\n";

		// skip already-seen cells
		if ($grid[$tempY][$tempX]['visited']) {
			continue;
		}

		$grid[$tempY][$tempX]['visited'] = true;
		$stack = [$grid[$tempY][$tempX]];

		$region = [];
		$regionRef = [];
		while (count($stack) > 0) {
			$curr = array_pop($stack);
			$region[] = $curr;

			$y = $curr['y'];
			$x = $curr['x'];
			$val = $curr['val'];

			// echo "stacking $val at $y, $x\n";

			$regionRef[$y.'|'.$x] = true;

			$n = $grid[$y-1][$x] ?? false;
			$e = $grid[$y][$x+1] ?? false;
			$s = $grid[$y+1][$x] ?? false;
			$w = $grid[$y][$x-1] ?? false;

			// echo "curr is $val at $y, $x\n";

			if ($n && $n['val'] === $val && !$n['visited']) {
				// echo "adding n cell to stack\n";
				$grid[$y-1][$x]['visited'] = true;
				$stack[] = $n;
			}

			if ($e && $e['val'] === $val && !$e['visited']) {
				// echo "adding e cell to stack\n";
				$grid[$y][$x+1]['visited'] = true;
				$stack[] = $e;
			}

			if ($s && $s['val'] === $val && !$s['visited']) {
				// echo "adding s cell to stack\n";
				$grid[$y+1][$x]['visited'] = true;
				$stack[] = $s;
			}

			if ($w && $w['val'] === $val && !$w['visited']) {
				// echo "adding w cell to stack\n";
				$grid[$y][$x-1]['visited'] = true;
				$stack[] = $w;
			}
		}

		if (!isset($regions[$cell['val']])) {
			$regions[$cell['val']] = [];
			$regionsRef[$cell['val']] = [];
		}

		$regions[$cell['val']][] = $region;
		$regionsRef[$cell['val']][] = $regionRef;
	}
}

$price = 0;
foreach($regions as $key1 => $r) {
	foreach($r as $key2 => $plots) {
		$area = count($plots);

		// echo "area of $area\n";

		$perimiter = 0;
		foreach($plots as $p) {
			$others = $regionsRef[$key1][$key2];

			$y = $p['y'];
			$x = $p['x'];

			$n = isset($others[$y-1 . '|' . $x])   ? 0 : 1;
			$e = isset($others[$y   . '|' . $x+1]) ? 0 : 1;
			$s = isset($others[$y+1 . '|' . $x])   ? 0 : 1;
			$w = isset($others[$y   . '|' . $x-1]) ? 0 : 1;

			$perimiter += $n + $e + $s + $w;
		}

		$price += intval($area * $perimiter);
	}
}

echo '<pre>';
var_dump($price);
echo '</pre>';
die();
