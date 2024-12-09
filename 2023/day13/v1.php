<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

$maps = [];
$map = [];
foreach($lines as $l) {
	if ($l === '') {
		$maps[] = $map;
		$map = [];
	} else {
		$map[] = str_split($l);
	}
}

$reflections = [];
foreach($maps as $key => $map) {
// $map = $maps[0];
	$height = count($map);
	$width = count($map[0]);
	$anyMatch = false;

	// check rows
	$prev = '';
	foreach($map as $y => $row) {
		$curr = implode($row);

		if ($curr === $prev) {
			echo "comparing rows\n";
			$match = true;
			$i=0;
			// walk backwards/forwards to see if they all match
			while ($y-$i-1 >= 0 && $y+$i < $height) {
				echo "row " . ($y-$i-1) . " " . implode($map[$y-$i-1]) . "\n";
				echo "row " . ($y+$i) . " " . implode($map[$y+$i]) . "\n";

				if ($map[$y-$i-1] !== $map[$y+$i]) {
					echo "no match\n";
					$match = false;
					break;
				}

				$i++;
			}

			if ($match) {
				$anyMatch = true;
				echo "match!\n";
				$reflections[] = 100 * $y;
			}
		} else {
			$prev = $curr;
		}
	}


	// check cols
	$prev = '';
	for($x=0; $x<count($map[0]); $x++) {
		$col = array_column($map, $x);
		$curr = implode($col);

		if ($curr === $prev) {
			echo "comparing cols\n";
			$match = true;
			$i=0;
			// walk backwards/forwards to see if they all match
			while ($x-$i-1 >= 0 && $x+$i < $width) {
				echo "col " . ($x-$i-1) . " " . implode(array_column($map, $x-$i-1)) . "\n";
				echo "col " . ($x+$i) . " " . implode(array_column($map, $x+$i)) . "\n";

				if (array_column($map, $x-$i-1) !== array_column($map, $x+$i)) {
					echo "no match\n";
					$match = false;
					break;
				}

				$i++;
			}

			if ($match) {
				$anyMatch = true;
				echo "match!\n";
				$reflections[] = $x;
			}
		} else {
			$prev = $curr;
		}
	}

	if (!$anyMatch) {
		echo '<pre>';
		var_dump($key);
		var_dump($map);
		echo '</pre>';
		die();
	}
}

echo '<pre>';
var_dump(array_sum($reflections));
echo '</pre>';
die();

echo '<pre>';
var_dump('');
echo '</pre>';
die();