<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

foreach($lines as $l) {

}

echo '<pre>';
var_dump('');
echo '</pre>';
die();