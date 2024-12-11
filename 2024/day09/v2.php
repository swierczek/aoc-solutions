<?php

$inputPath = str_replace('/aoc-solutions/', '/aoc-inputs/', getcwd()) . '/';
$filename = $inputPath . ($argv[1] ?? 'testinput.txt');

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

const SPACE = '.';

$files = [];
$spaces = [];
$fileId = -1;
$spaceId = -1;
foreach(str_split($lines[0]) as $key => $l) {
	if ($key % 2 === 0) {
		$fileId++;

		$files[$fileId] = [
			'id' => $fileId,
			'size' => intval($l),
		];
	} else {
		$spaceId++;

		$spaces[$spaceId] = [
			'id' => $spaceId,
			'size' => intval($l),
			'files' => [],
		];
	}
}

// combine the files and spaces into a single array
$arr = generateArray($files, $spaces);

// iterate over spaces and move files
// backwards for files, forwards for spaces
for($j=count($arr)-1; $j>=0; $j--) {
	if ($arr[$j] === SPACE) {
		continue;
	}

	// current file
	$fileId = $arr[$j];

	// get the file size
	$tempJ = $j;
	while (isset($arr[$tempJ]) && $fileId == $arr[$tempJ]) {
		$tempJ--;
	}
	$fileSize = $j - $tempJ;

	for($i=0; $i<count($arr); $i++) {
		if ($arr[$i] !== SPACE) {
			continue;
		}

		// don't move the file later!
		if ($i > $j) {
			$j -= $fileSize - 1;
			break;
		}

		// get the space size
		$tempI = $i;
		while (isset($arr[$tempI]) && $arr[$tempI] === SPACE) {
			$tempI++;
		}
		$spaceSize = $tempI - $i;

		if ($spaceSize >= $fileSize) {
			// swap spaces

			// $j is the end of the file range
			for($k=0; $k<$fileSize; $k++) {
				$arr[$j-$k] = SPACE;
			}

			// $i is the beginning of the space range
			for($k=0; $k<$fileSize; $k++) {
				$arr[$i+$k] = $fileId;
			}

			// move to the end of the next file
			$j -= $fileSize - 1;

			break;

		} else {
			// increment to the end of the spaces and continue
			$i += $spaceSize-1;

			// if no spaces found, shift to the next file
			if ($i + $spaceSize >= count($arr)) {
				$j -= $fileSize - 1;
			}

			continue;
		}
	}
}

echo '<pre>';
var_dump(calculateChecksum($arr));
echo '</pre>';
die();

function generateArray(array $files, array $spaces) {
	$arr = [];
	$maxId = max(max(array_keys($spaces)), max(array_keys($files)));
	for($i=0; $i<=$maxId; $i++) {
		if (isset($files[$i])) {
			$file = $files[$i];
			array_push($arr, ...array_fill(0, $file['size'], $file['id']));
		}

		if (isset($spaces[$i])) {
			foreach($spaces[$i]['files'] as $f) {
				array_push($arr, ...array_fill(0, $file['size'], $file['id']));
			}

			// $str .= str_repeat(SPACE, $spaces[$i]['size']);
			array_push($arr, ...array_fill(0, $spaces[$i]['size'], SPACE));
		}
	}

	return $arr;
}

function calculateChecksum(array $arr) {
	$checksum = 0;
	foreach($arr as $key => $val) {
		$checksum += intval($key) * intval($val);
	}

	return $checksum;
}
