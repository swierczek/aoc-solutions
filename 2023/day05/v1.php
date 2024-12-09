<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

$seeds = [];

$seedSoil = [];
$soilFertilizer = [];
$fertilizerWater = [];
$waterLight = [];
$lightTemperature = [];
$temperatureHumidity = [];
$humidityLocation = [];

$currMap = [];
foreach($lines as $num => $l) {
	if (trim($l) === '') {
		continue;
	} else if ($num === 0) {
		$seeds = explode(' ', str_replace('seeds: ', '', $l));
	} else if (stripos($l, ' map:') !== false) {
		$mapType = explode('-', str_replace(' map:', '', $l));
		$destination = $mapType[0];
		$source = ucwords($mapType[2]);

		$currMap = $destination.$source;
	} else {
		$nums = explode(' ', $l);
		$destination = $nums[0];
		$source = $nums[1];
		$range = $nums[2];

		$$currMap[] = [
			'source' => [
				'min' => intval($source),
				'max' => intval($source + $range - 1),
			],
			'destination' => [
				'min' => intval($destination),
				'max' => intval($destination + $range - 1),
			],
		];
	}
}

$locations = [];
foreach($seeds as $index => $seed) {
	// now go through each range to find the next mapping
	$seed = mapToNext($seed, $seedSoil);
	$seed = mapToNext($seed, $soilFertilizer);
	$seed = mapToNext($seed, $fertilizerWater);
	$seed = mapToNext($seed, $waterLight);
	$seed = mapToNext($seed, $lightTemperature);
	$seed = mapToNext($seed, $temperatureHumidity);
	$seed = mapToNext($seed, $humidityLocation);

	$locations[$index] = $seed;
}

echo '<pre>';
var_dump(min($locations));
echo '</pre>';
die();

function mapToNext($seed, $ranges): int
{
	foreach($ranges as $r) {
		$src = $r['source'];
		$dest = $r['destination'];

		if ($seed <= $src['max'] && $seed >= $src['min']) {
			//calculate the new mapping
			$diff = $seed - $src['min'];
			return $diff + $dest['min'];
		}
	}

	// if no range found, then the seed is the same number
	return $seed;
}