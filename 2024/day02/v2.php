<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_filter(array_map('trim', explode("\n", $input)));

const UP = 'up';
const DOWN = 'down';


$safe = [];
$unsafe = [];

foreach($lines as $l) {
	if (!$l) {
		continue;
	}

	$isSafe = isLineSafe($l);

	if ($isSafe) {
		$safe[] = $l;
	} else {
		// redo by removing each element
		$nums = array_values(array_filter(explode(" ", $l)));

		for($i=0; $i<count($nums); $i++) {
			$temp = $nums;
			unset($temp[$i]);
			$temp = array_values($temp);

			$tempLine = implode(' ', $temp);

			$isSafe = isLineSafe($tempLine);

			if ($isSafe) {
				$safe[] = $tempLine;
				break;
			}
		}

	}
}

echo '<pre>';
var_dump(count($safe));
echo '</pre>';
die();

function isLineSafe($l): bool
{
	$nums = array_values(array_filter(explode(" ", $l)));

	$direction = $nums[0] < $nums[1] ? UP : DOWN;

	foreach($nums as $key => $n) {
		if ($key === 0) {
			continue;
		}

		$diff = $nums[$key] - $nums[$key-1];

		$isDiffValid = 1 <= abs($diff) && abs($diff) <= 3;

		if (!$isDiffValid) {
			return false;
		}

		if ($direction === UP && $diff < 0) {
			return false;
		} else if ($direction === DOWN && $diff > 0) {
			return false;
		}
	}

	return true;
}