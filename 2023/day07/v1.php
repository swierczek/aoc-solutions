<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

define('TYPE_FIVE', 'five');
define('TYPE_FOUR', 'four');
define('TYPE_FULL', 'full');
define('TYPE_THREE', 'three');
define('TYPE_PAIRS', 'pairs');
define('TYPE_PAIR', 'pair');
define('TYPE_HIGH', 'high');

$typeOrder = [
	TYPE_HIGH,
	TYPE_PAIR,
	TYPE_PAIRS,
	TYPE_THREE,
	TYPE_FULL,
	TYPE_FOUR,
	TYPE_FIVE,
];

$cardOrder = [
	2,
	3,
	4,
	5,
	6,
	7,
	8,
	9,
	'T',
	'J',
	'Q',
	'K',
	'A',
];

$typeOrder = array_flip($typeOrder);
$cardOrder = array_flip($cardOrder);

$hands = [];
foreach($lines as $l) {
	$split = explode(' ', $l);

	$hands[] = [
		'cards' => $split[0],
		'bid' => $split[1],
		'type' => getHandType($split[0]),
	];
}

usort($hands, function($a, $b) use ($typeOrder, $cardOrder) {
	$typeA = $a['type'];
	$typeB = $b['type'];

	if ($typeA !== $typeB) {
		return $typeOrder[$typeA] <=> $typeOrder[$typeB];
	} else {
		$cardsA = $a['cards'];
		$cardsB = $b['cards'];

		// compare each card
		for ($i=0; $i<strlen($cardsA); $i++) {
			if ($cardsA[$i] === $cardsB[$i]) {
				continue;
			}

			$cardA = $cardsA[$i];
			$cardB = $cardsB[$i];

			return $cardOrder[$cardA] <=> $cardOrder[$cardB];
		}
	}
});

$winnings = 0;
foreach($hands as $rank => $hand) {
	$winnings += ($rank + 1) * $hand['bid'];
}

echo '<pre>';
var_dump($winnings);
echo '</pre>';
die();

function getHandType($hand) {
	$cards = str_split($hand);
	$counts = array_count_values($cards);
	$countCounts = array_count_values($counts);

	if (@$countCounts[5]) {
		return TYPE_FIVE;
	} else if (@$countCounts[4]) {
		return TYPE_FOUR;
	} else if (@$countCounts[3] && @$countCounts[2]) {
		return TYPE_FULL;
	} else if (@$countCounts[3]) {
		return TYPE_THREE;
	} else if (@$countCounts[2] === 2) {
		return TYPE_PAIRS;
	} else if (@$countCounts[2]) {
		return TYPE_PAIR;
	} else {
		return TYPE_HIGH;
	}
}