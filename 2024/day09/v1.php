<?php

$inputPath = str_replace('/aoc-solutions/', '/aoc-inputs/', getcwd()) . '/';
$filename = $inputPath . ($argv[1] ?? 'testinput.txt');

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

const SPACE = '.';

$files = [];
$spaces = [];
$id = 0;
$arr = [];
foreach(str_split($lines[0]) as $key => $l) {
	if ($key % 2 === 0) {
		$files[$id] = [
			'id' => $id,
			'count' => intval($l),
		];

		for($i=0; $i<$files[$id]['count']; $i++) {
			$arr[] = $id;
		}

		$id++;
	} else {
		$spaces[] = intval($l);

		for($i=0; $i<intval($l); $i++) {
			$arr[] = SPACE;
		}
	}
}


// 2333133121414131402 =
// 00...111...2...333.44.5555.6666.777.888899

$i = 0;
$j = count($arr)-1;

while ($i < $j) {
	if ($arr[$i] !== SPACE) {
		$i++;
	} else if ($arr[$j] === SPACE) {
		$j--;
	} else {
		// swap them!
		$temp = $arr[$i];
		$arr[$i] = $arr[$j];
		$arr[$j] = $temp;
	}
}

// trim away the spaces at the end
$arr = array_slice($arr, 0, $i);

$checksum = 0;
foreach($arr as $key => $val) {
	$checksum += $key * $val;
}

echo '<pre>';
var_dump($checksum);
echo '</pre>';
die();
