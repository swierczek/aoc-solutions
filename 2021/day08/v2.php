<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

$sum = 0;

foreach($lines as $l) {
	$segments = array_fill(0, 9, '');

	$parts = explode(' | ', $l);
	$inputs = explode(' ', $parts[0]);
	$outputs = explode(' ', $parts[1]);

	// we can easily identify these unique values based on their lengths
	if (!$segments[1] || !$segments[4] || !$segments[7] || !$segments[8]) {
		$solvedUnique = 0;
		foreach($inputs as $key => $i) {
			$i = str_split($i);

			if (count($i) == 2) {
				$segments[1] = $i;
				unset($inputs[$key]);
				$solvedUnique++;
			} else if (count($i) == 4) {
				$segments[4] = $i;
				unset($inputs[$key]);
				$solvedUnique++;
			} else if (count($i) == 3) {
				$segments[7] = $i;
				unset($inputs[$key]);
				$solvedUnique++;
			} else if (count($i) == 7) {
				$segments[8] = $i;
				unset($inputs[$key]);
				$solvedUnique++;
			}

			if ($solvedUnique == 4) {
				break;
			}
		}
	}

	// now for the remaining 0, 2, 3, 5, 6, 9
	// of lengths........... 6, 5, 5, 5, 6, 6
	foreach($inputs as $key => $i) {
		$i = str_split($i);

		$overlap1 = findNumOverlap($i, $segments[1]);
		$overlap4 = findNumOverlap($i, $segments[4]);
		$overlap7 = findNumOverlap($i, $segments[7]);
		$overlap8 = findNumOverlap($i, $segments[8]);

		if (count($i) == 6) {
			// 0, 6, 9

			if ($overlap1 == 7 && $overlap4 == 7 && $overlap7 == 7 && $overlap8 == 7) {
				//7777
				$segments[6] = $i;
				unset($inputs[$key]);
			} else if ($overlap1 == 6 && $overlap4 == 6 && $overlap7 == 6 && $overlap8 == 7) {
				//6667
				$segments[9] = $i;
				unset($inputs[$key]);
			} else if ($overlap1 == 6 && $overlap4 == 7 && $overlap7 == 6 && $overlap8 == 7) {
				//6767
				$segments[0] = $i;
				unset($inputs[$key]);
			}
		} else if (count($i) == 5) {
			// 2, 3, 5

			if ($overlap1 == 5 && $overlap4 == 6 && $overlap7 == 5 && $overlap8 == 7) {
				//5657
				$segments[3] = $i;
				unset($inputs[$key]);
			} else if ($overlap1 == 6 && $overlap4 == 7 && $overlap7 == 6 && $overlap8 == 7) {
				//6767
				$segments[2] = $i;
				unset($inputs[$key]);
			} else if ($overlap1 == 6 && $overlap4 == 6 && $overlap7 == 6 && $overlap8 == 7) {
				//6667
				$segments[5] = $i;
				unset($inputs[$key]);
			}
		}
	}

	// sort each segment and resave as string
	foreach($segments as $key => $s) {
		asort($s);
		$segments[$key] = implode($s);
	}

	// build the output based on the matched segments
	$out = '';
	foreach($outputs as $o) {
		$o = str_split($o);
		asort($o);
		$o = implode($o);

		$search = array_search($o, $segments);

		$out .= $search;
	}

	$sum += intval($out);
}

echo '<pre>';
var_dump($sum);
echo '</pre>';
die();

function findNumOverlap($arr1, $arr2) {
	return count(array_unique(array_merge($arr1, $arr2)));
}