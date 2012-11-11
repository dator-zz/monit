<?php

exec('/usr/bin/top -b -n1', $top);

/*
// Valeur de test
$top = array(
	'top - 16:46:26 up 95 days, 22:17,  1 user,  load average: 0.01, 0.02, 0.05',
	'Tasks: 105 total,   1 running, 104 sleeping,   0 stopped,   0 zombie',
	'Cpu(s):  0.3%us,  0.2%sy,  0.0%ni, 99.3%id,  0.1%wa,  0.0%hi,  0.0%si,  0.0%st',
	'Mem:   2037172k total,   653000k used,  1383568k free,   145400k buffers',
	'Swap:   523260k total,        0k used,   523260k free,   345040k cached'
);*/

$cpu = preg_match('/Cpu\(s\):  (.*)%us,  (.*)%sy,  (.*)%ni, (.*)%id, (.*)%wa,  (.*)%hi,  (.*)%si,  (.*)%st/', $top[2], $cpus);
$mem = preg_match('/Mem:  (.*)k total,   (.*)k used,  (.*)k free/', $top[3], $mems);
$swap = preg_match('/Swap:  (.*)k total,        (.*)k used/', $top[4], $swaps);

$return = array(
	'memory' => array(
		'used' => round(((100 * $mems[2]) / $mems[1]), 2)
	),
	'cpu' => array(
		'used' => round($cpus[1], 2)
	),
	'swap' => array(
		'used' => round(($swap[1] > 0) ? round(((100 * $swap[2]) / $swap[1]), 2) : 0, 2)
	)
);

header('Content-type: application/json');
echo json_encode($return);