<?php

$filename = $argv[1] ?? 'testinput.txt';

$input = file_get_contents($filename);

$lines = array_map('trim', explode("\n", $input));

$line = $lines[0];

$hex = str_split($line);

$bin = '';
foreach($hex as $h) {
	// pad this to the left because the above translates 3 to "11" but we need "0011"
	$bin .= str_pad(base_convert($h, 16, 2), 4, '0', STR_PAD_LEFT);
}

$bin = str_split($bin);





$packet = '';
$subpacket = '';
$type = null;
$version = null;

$mode = 'v'; // t, i, l, a, b, c, literal, padding
$bits = ''; // bits that we're currently gathering/parsing

$prefix = null; // toggling 0/1 to determine the literal prefix
$num = ''; // building the literal in bits
$length = 0; // sub-packet length

// TODO: these should be a arrays to keep track of the level we're currently reading
$subPacketLength = [];
$currSubPacketLength = [];
$checkingSubPackets = [];
$currSubPacketCount = [];
$subPacketCount = [];
$types = [];
$level = 0;

// final return for v1 (sum these)
$versions = [];
$literals = [];

for($i=0; $i<count($bin); $i++) {

	// echo "reading bit $i: ".$bin[$i].PHP_EOL;

	$bits .= $bin[$i];
	$packet .= $bin[$i];

	if ($level > 0) {
		// increase all parent lengths too
		for($l=0; $l<$length; $l++) {
			// not completely sure about this logic. If we have e.g.
			//     length > count > length > literal subpackets
			//     then I'm not sure how to keep track of these counts.
			//     maybe in a single array so we can track the type/levels together?
			//     rather than jumping between 2 separate arrays...
			// only the levels that exist for this heirarchy
			if (isset($currSubPacketLength[$l])) {
				$currSubPacketLength[$l]++;
			// } else {
				// break; // not sure if this should exist or not
			}
		}
		// echo "level $level, current length ".$currSubPacketLength[$level].PHP_EOL;
	}

	if ($mode == 'v') {
		// echo 'mode v'.PHP_EOL;

		// done parsing v
		if (strlen($bits) == 3) {
			$version = getDec($bits);


			// if it turns out we were looking at padded 0s
			if ($version == 0 && isset($bin[$i+1]) && $bin[$i+1] == 0) {
				echo "was padding, not v".PHP_EOL;
				$bits = '';
				$mode = 'v';
				$i++; // skip the next bit too because it's a hex 0
			} else if ($version == 0 && !isset($bin[$i+1])) {
				echo "end of packet padding, not v".PHP_EOL;
			} else {
				// echo "version $version".PHP_EOL;
				echo "version $version".PHP_EOL;
				$versions[] = $version;
				$bits = '';
				$mode = 't';
			}
		}

	} else if ($mode == 't') {
		// echo 'mode t'.PHP_EOL;

		// done parsing t
		if (strlen($bits) == 3) {
			$type = getDec($bits);
			// echo "type $type".PHP_EOL;
			$bits = '';

			if ($type == 4) {
				$mode = 'literal';
				echo "type 4 (literal)".PHP_EOL;
			} else {
				$mode = 'i';
				echo "type $type (operator)".PHP_EOL;
			}
		}

	} else if ($mode == 'literal') {
		// echo 'mode literal'.PHP_EOL;

		// done parsing _this_ literal
		if (strlen($bits) % 5 == 0) {
			// echo 'mod 5...'.PHP_EOL;
			$prefix = $bits[0];
			$num .= substr($bits, -4);

			// done parsing whole literal
			if ($prefix == 0) {
				$num = getDec($num);
				echo "literal complete: $num".PHP_EOL;
				$literals[] = $num;
				// echo "prefix 0, done...................... $num".PHP_EOL;
				// echo $bits.PHP_EOL;

				// check if we're in a sub-packet or not
				if ($level > 0) {
					var_dump('types level');
					var_dump($types[$level]);


					if (isset($currSubPacketCount[$level])) {
						for($l=$level; $l>0; $l--) {
							if (isset($currSubPacketCount[$l])) {
								// TODO: do we also need to increase the size of parent subpacket counts?
								$currSubPacketCount[$l]++;
								echo "subpacket count for level $l: ".$currSubPacketCount[$l].PHP_EOL;
							} else {
								break;
							}
						}
					}


					// var_dump('level');
					// var_dump($level);
					// var_dump($currSubPacketLength);
					// var_dump($subPacketLength);
					// die();
					if (@$currSubPacketLength[$level] == @$subPacketLength[$level]) {
						// while (@$currSubPacketLength[$level] == @$subPacketLength[$level]) {
						echo "subpacket length".PHP_EOL;
						for($l=$length; $l>0; $l--) {
							if (isset($currSubPacketLength[$l])) {
								// done reading the subpacket, back to padding?
								unset($currSubPacketLength[$l]);
								unset($subPacketLength[$l]);
								$level--;
								echo "decreasing level to $level".PHP_EOL;
							} else {
								break; // not sure if this should exist or not
							}
						}

						echo '<pre>';
						var_dump($level);
						var_dump($currSubPacketLength[$level]);
						var_dump($subPacketLength[$level]);
						echo '</pre>';
						die();

						$mode = 'v';
					} else if (@$currSubPacketCount[$level] == @$subPacketCount[$level]) {
						echo "subpacket count".PHP_EOL;
						var_dump($currSubPacketCount);
						die();
						for($l=$length; $l>0; $l--) {
							if (isset($currSubPacketCount[$l])) {
								// count it when it's done parsing
								unset($currSubPacketCount[$l]);
								unset($subPacketCount[$l]);
								$level--;
								echo "decreasing level to $level".PHP_EOL;
							} else {
								break;
							}
						}

						$mode = 'v';
					} else {
						echo "reading more sub-packets".PHP_EOL;
						// continue reading sub-packets
						$mode = 'v';
					}
				} else {
					var_dump('type');
					var_dump($type);

					$mode = 'padding';
				}

				$num = '';
				$bits = '';
			} else if ($prefix == 1) {
				// echo "prefix 1, continuing... $num".PHP_EOL;
				$bits = '';
				continue;
			}
		} else {
			// var_dump('not yet 5 long ...............................');
			// var_dump($bits);
			// die();
		}

	} else if ($mode == 'padding') {
		// echo 'mode padding'.PHP_EOL;

		if (strlen($packet) % 4 == 0) {
			$packet = '';
			$bits = '';
			$mode = 'v';
		}

	} else if ($mode == 'i') {
		// echo 'mode i'.PHP_EOL;

		if ($bits == 0) {
			$length = 15;
			$mode = 'l0';
		} else if ($bits == 1) {
			$length = 11;
			$mode = 'l1';
		}

		$bits = '';

	} else if ($mode == 'l0') { // L if I is 0
		// echo 'mode l0'.PHP_EOL;

		if (strlen($bits) == $length) {
			$level++;

			$types[$level] = $type;

			$subPacketLength[$level] = getDec($bits);
			$checkingSubPackets[$level] = true;
			$bits = '';
			$mode = 'v';

			echo "subpacket bit length: ".$subPacketLength[$level].PHP_EOL;
			echo "increasing level to $level".PHP_EOL;
		}

	} else if ($mode == 'l1') { // L if I is 1
		// echo 'mode l1'.PHP_EOL;

		if (strlen($bits) == $length) {
			$level++;

			$types[$level] = $type;

			$subPacketCount[$level] = getDec($bits);
			$currSubPacketCount[$level] = 0;
			$bits = '';
			$mode = 'v';
			$checkingSubPackets[$level] = true;

			echo "increasing level to $level".PHP_EOL;
			echo "subpacket count: ".$subPacketCount[$level].PHP_EOL;
		}

	} else if ($mode == 'sub') {
		// echo 'mode sub'.PHP_EOL;

		// we have a full subpacket
		if (strlen($bits) == $subPacketLength[$level]) {
			$bits = '';
			$mode = 'v';
		}
	}
}

echo '<pre>';
var_dump($literals);
var_dump($versions);
var_dump(array_sum($versions));
echo '</pre>';
die();

function getDec($bin) {
	return intval(base_convert($bin, 2, 10));
}


/*
first 3 bits of a packet = version
next 3 bits of a packet = type ID

ID of 4 = literal value
	pad the rest of it until the binary is a multiple of 4
	each group of 4 starts with a 1, except the last group starts with a 0
split by 4s:
	1101 0010 1111 1110 0010 1000
split by groups:
	110 100 10111 11110 00101 000
	VVV TTT AAAAA BBBBB CCCCC

			 0111  1110  0101
	0b011111100101 = 0x2021

if T = 4, it's a #
if T is anything else, it's an operator
split by 4s:
	0011 1000 0000 0000 0110 1111 0100 0101 0010 1001 0001 0010 0000 0000
split by groups:
	001 110 0 000000000011011 11010001010 01010010001001000000000
	VVV TTT I LLLLLLLLLLLLLLL AAAAAAAAAAA BBBBBBBBBBBBBBBB

V = packet version 1
T = packet type 6
I = 0, so 15 bits of L
  = 1, so 11 bits of L

split by 4s:
 	1110 1110 0000 0000 1101 0100 0000 1100 1000 0010 0011 0000 0110 0000
 split by groups:
	111 011 1 00000000011 01010000001 10010000010 00110000011 00000
	VVV TTT I LLLLLLLLLLL AAAAAAAAAAA BBBBBBBBBBB CCCCCCCCCCC


*/