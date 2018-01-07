<?php

include_once __DIR__ . '/opcodes.php';

$binfile = file_get_contents($argv[1]);

$binfile = array_values(unpack('v*', $binfile));

print_r($binfile);