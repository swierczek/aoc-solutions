<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

const DELIM = '/';
const MAX_FOLDER_SIZE = 100000;

$files = [];
$path = [];

foreach($lines as $line) {
	if (stripos($line, '$ cd') === 0) {
		$param = str_replace('$ cd ', '', $line);

		if ($param == '..') {
			array_pop($path); // unset the last one
		} else if ($param == DELIM) {
			$path = [];
		} else {
			$path[] = $param;
		}

	} else if (stripos($line, '$ ls') === 0) {
		// ignore it, the next lines will be files/folders
	} else {
		$strPath = DELIM . ($path ? implode(DELIM, $path) . DELIM : '');

		if (stripos($line, 'dir') === 0) {
			// nothing to do here
		} else {
			$fileInfo = explode(' ', $line);

			$files[$strPath.$fileInfo[1]] = $fileInfo[0];
		}
	}
}

// file tree created, now traverse each file path backwards and update the size of the folders
$folders = [];
foreach($files as $path => $size) {
	$pathParts = explode(DELIM, $path);

	// unset the file name
	array_pop($pathParts);

	while (count($pathParts) > 0) {
		$tempPath = implode(DELIM, $pathParts);

		if (!isset($folders[$tempPath])) {
			$folders[$tempPath] = 0;
		}

		$folders[$tempPath] += $size;

		array_pop($pathParts);
	}
}

// clean up the root directory
$folders['/'] = $folders[''];
unset($folders['']);



$sum = 0;
foreach($folders as $size) {
	if ($size <= MAX_FOLDER_SIZE) {
		$sum += $size;
	}
}

echo '<pre>';
var_dump($sum);
echo '</pre>';
die();
