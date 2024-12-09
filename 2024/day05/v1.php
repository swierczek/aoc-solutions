<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

$rules = [];
$updates = [];

foreach($lines as $l) {
	if (stripos($l, '|') !== false) {
		$split = explode('|', $l);
		if (!isset($rules[$split[0]])) {
			$rules[$split[0]] = [];
		}

		$rules[$split[0]][] = $split[1];
	} else if (stripos($l, ',') !== false) {
		$updates[] = explode(',', $l);
	}
}

$valid = [];
$invalid = [];

foreach($updates as $u) {
	$good = true;

	foreach($u as $index => $page) {
		// $page is 75
		// $rules[$page] is [29, 53, 47, 61, 13]
		// so check each of these to ensure they're at a later index than $index

		if (!isset($rules[$page])) {
			continue;
		}

		foreach($rules[$page] as $laterPage) {
			// later page doesn't exist in the array, so ignore the rule
			if (!in_array($laterPage, $u)) {
				continue;
			}

			if (array_search($laterPage, $u) < $index) {
				$good = false;
				$invalid[] = $u;
				break;
			}
		}

		if (!$good) {
			break;
		}
	}

	if ($good) {
		// $valid[] = $u;
		$valid[] = $u[floor(count($u)/2)];
	}
}

echo '<pre>';
var_dump(array_sum($valid));
echo '</pre>';
die();
