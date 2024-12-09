<?php

ini_set('memory_limit','2048M');

define('UNKNOWN', '?');
define('NORMAL', '.');
define('DAMAGED', '#');

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

$springs = [];
foreach($lines as $l) {
	$split = explode(' ', $l);
	$data = [
		'input' => $split[0],
		'arr' => str_split($split[0]),
		'counts' => explode(',', $split[1]),
	];
	$data['input_length'] = count($data['arr']);
	$data['total_damaged'] = array_sum($data['counts']);
	$data['num_unknown'] = substr_count($data['input'], UNKNOWN);
	$data['num_known'] = substr_count($data['input'], DAMAGED);

	// build regex using counts
	// #{1}\.+#{1}\.#{3}
	$parts = [];
	foreach($data['counts'] as $c) {
		$parts[] = '#{'.$c.'}';
	}
	$data['pattern'] = implode('\.+', $parts);

	$springs[] = $data;
}

// $max = max(array_column($springs, 'input_length'));

foreach($springs as $key => $s) {
	// ???.###
	// has 3 ?
	// so generate a binary map from 0 to 2^3-1 with . and #
	// then write each of those back to the respective ?
	// then regex match the new string to see if it matches
	$map = getBinaryMap($s['num_unknown'], $s['total_damaged'] - $s['num_known']);

	$matches = 0;
	foreach($map as $m) {
		$chars = str_split($m);

		$string = '';
		$mapIndex = 0;
		foreach($s['arr'] as $inputChar) {
			if ($inputChar === UNKNOWN) {
				$string .= $chars[$mapIndex++];
			} else {
				$string .= $inputChar;
			}
		}

		$matched = preg_match('/\.*' . $s['pattern'] . '\.*/', $string);

		if ($matched) {
			$matches++;
		}
	}

	$springs[$key]['matches'] = $matches;
}

echo '<pre>';
var_dump(array_sum(array_column($springs, 'matches')));
echo '</pre>';
die();

// TODO add caching?
function getBinaryMap($max, $total)
{
	// generate binary numbers from 0 - max length
	$map = [];
	for ($i=0; $i<2**$max; $i++) {
		$bin = decbin($i);

		$new = str_pad(str_replace(['0', '1'], [NORMAL, DAMAGED], $bin), $max, NORMAL, STR_PAD_LEFT);
		$count = substr_count($new, DAMAGED);

		// we know how many are missing, so ignore any without that amount
		if ($count === $total) {
			$map[] = $new;
		}
	}

	return $map;
}
