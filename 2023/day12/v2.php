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
	$input = $split[0];
	$counts = $split[1];

	$newInput = array_fill(0, 5, $input);
	$newCounts = implode(',', array_fill(0, 5, $counts));

	$data = [
		'input' => implode(UNKNOWN, $newInput),
		'counts' => explode(',', $newCounts),
	];

	$data['arr'] = str_split($data['input']);
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

foreach($springs as $key => $s) {
	// ???.###
	// has 3 ?
	// so generate a binary map from 0 to 2^3 with . and #
	// then write each of those back to the respective ?
	// then regex match the new string to see if it matches
	// echo '<pre>';
	// var_dump($s);
	// echo '</pre>';
	// die();
	$temp2 = $s['total_damaged'] - $s['num_known'];
	echo "generating map for {$s['num_unknown']} with $temp2 total\n";
	$map = getMap($s['num_unknown'], $s['total_damaged'] - $s['num_known']);

	$temp = count($map);
	echo "checking $temp rows\n";
	$matches = 0;
	foreach($map as $m) {
		// $chars = str_split($m);

		$string = '';
		$mapIndex = 0;
		foreach($s['arr'] as $inputChar) {
			if ($inputChar === UNKNOWN) {
				$string .= $m[$mapIndex++];
			} else {
				$string .= $inputChar;
			}
		}

		$matched = preg_match('/\.*' . $s['pattern'] . '\.*/', $string);

		if ($matched) {
			$matches++;
		}
	}

	echo "line $key matched $matches times\n";

	$springs[$key]['matches'] = $matches;
}

echo '<pre>';
var_dump(array_sum(array_column($springs, 'matches')));
echo '</pre>';
die();

// TODO add caching?
function getMap($length, $numDamaged)
{
	// echo '<pre>';
	// var_dump($length);
	// var_dump($numDamaged);
	// var_dump(permuteUnique(['#', '#', '.']));
	// echo '</pre>';
	// die();

	// generate string like #####...
	$string = str_pad(str_repeat(DAMAGED, $numDamaged), $length, NORMAL);
	// create all unique permutations of that string
	$map = permuteUnique(str_split($string));

	return $map;

	// generate binary numbers from 0 - max length
	// $map = [];
	// for ($i=0; $i<2**$max; $i++) {
	// 	$bin = decbin($i);

	// 	$new = str_pad(str_replace(['0', '1'], [NORMAL, DAMAGED], $bin), $max, NORMAL, STR_PAD_LEFT);
	// 	$count = substr_count($new, DAMAGED);

	// 	// we know how many are missing, so ignore any without that amount
	// 	if ($count === $total) {
	// 		$map[] = $new;
	// 	}
	// }

	// return $map;
}

// https://stackoverflow.com/questions/18935813/efficiently-calculating-unique-permutations-in-a-set
function permuteUnique($items) {
    // sort($items); // string generation sorts it by default
    $size = count($items);
    $return = [];
    while (true) {
        $return[] = $items;
        $invAt = $size - 2;
        for (;;$invAt--) {
            if ($invAt < 0) {
                break 2;
            }
            if ($items[$invAt] < $items[$invAt + 1]) {
                break;
            }
        }
        $swap1Num = $items[$invAt];
        $inv2At = $size - 1;
        while ($swap1Num >= $items[$inv2At]) {
            $inv2At--;
        }
        $items[$invAt] = $items[$inv2At];
        $items[$inv2At] = $swap1Num;
        $reverse1 = $invAt + 1;
        $reverse2 = $size - 1;
        while ($reverse1 < $reverse2) {
            $temp = $items[$reverse1];
            $items[$reverse1] = $items[$reverse2];
            $items[$reverse2] = $temp;
            $reverse1++;
            $reverse2--;
        }
    }
    return $return;
}