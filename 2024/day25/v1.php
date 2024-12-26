<?php

$inputPath = str_replace('/aoc-solutions/', '/aoc-inputs/', getcwd()) . '/';
$filename = $inputPath . ($argv[1] ?? 'testinput.txt');

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

const EMPTY_ARRAY = [0, 0, 0, 0, 0];
const MODE_NEW = 'new';
const MODE_KEY = 'key';
const MODE_LOCK = 'lock';
const PIN = '#';
const NL = "\n";

$keys = [];
$locks = [];

$mode = MODE_NEW;
$lock = EMPTY_ARRAY;
$key = EMPTY_ARRAY;
foreach($lines as $l) {
	if (!$l) {
		if ($mode === MODE_KEY) {
			$keys[] = implode(',', $key);
		} else if ($mode === MODE_LOCK) {
			$locks[] = implode(',', $lock);
		}

		$key = EMPTY_ARRAY;
		$lock = EMPTY_ARRAY;

		$mode = MODE_NEW;
	} else if ($mode === MODE_NEW) {
		if ($l === '#####') {
			$mode = MODE_LOCK;
		} else {
			$mode = MODE_KEY;
		}
	} else if ($mode === MODE_LOCK) {
		$str = str_split($l);
		foreach($str as $i => $s) {
			if ($s === PIN) {
				$lock[$i]++;
			}
		}
	} else if ($mode === MODE_KEY) {
		$str = str_split($l);
		foreach($str as $i => $s) {
			if ($s === PIN) {
				$key[$i]++;
			}
		}
	}
}

$matches = 0;
foreach($locks as $lock) {
	$l = array_map('intval', explode(',', $lock));

	foreach($keys as $key) {
		$k = array_map('intval', explode(',', $key));
		// echo "trying lock $lock with key $key" . NL;
		$match = true;
		for($i=0; $i<5; $i++) {
			// echo "comparing " . $l[$i] . " + " . $k[$i] . NL;
			if ($k[$i] + $l[$i] > 6) {
				// echo "no match - failed at column $i\n";
				$match = false;
				break;
			}
		}

		if ($match) {
			// echo "they match!\n";
			$matches++;
		}
	}
}

echo '<pre>';
var_dump($matches);
echo '</pre>';
die();
