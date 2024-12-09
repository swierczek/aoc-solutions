<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

$rules = [];
$updates = [];

foreach($lines as $l) {
	if (stripos($l, '|') !== false) {
		$split = explode('|', $l);
		$rules[$split[0]][$split[1]] = true;
	} else if (stripos($l, ',') !== false) {
		$updates[] = explode(',', $l);
	}
}

$invalid = [];

foreach($updates as $u) {
	$good = checkIfValid($u);

	if (!$good) {
		$invalid[] = $u;
	}
}

foreach($invalid as $u) {
	usort($u, function($a, $b) use ($rules) {
		return isset($rules[$a][$b]) && $rules[$a][$b] ? 1 : -1;
	});

	$fixed[] = $u[floor(count($u)/2)];
}

echo '<pre>';
var_dump(array_sum($fixed));
echo '</pre>';
die();

function checkIfValid($update) {
	global $rules;
	$good = true;

	foreach($update as $index => $page) {
		// $page is 75
		// $rules[$page] is [29, 53, 47, 61, 13]
		// so check each of these to ensure they're at a later index than $index

		if (!isset($rules[$page])) {
			continue;
		}

		foreach($rules[$page] as $laterPage => $whatever) {
			// later page doesn't exist in the array, so ignore the rule
			if (!in_array($laterPage, $update)) {
				continue;
			}

			if (array_search($laterPage, $update) < $index) {
				$good = false;
				break;
			}
		}

		if (!$good) {
			break;
		}
	}

	return $good;
}