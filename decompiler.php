<?php

include_once __DIR__ . '/opcodes.php';

$binfile = fopen($argv[1], 'r');



function nextWord() {
    global $binfile;
    $word = fread($binfile, 2);
    if (!$word) {
        return false;
    }
    return unpack('v', $word)[1];
}

function nextInstruction() {
    global $opcodes;
    $next = nextWord();
    if ($next === false) {
        return false;
    }
    if ($next > 21) {
        return '??? ' . $next;
    }
    $op = $opcodes[$next];
    $args = [];
    for ($i = 0; $i < $op['nargs']; $i++) {
        $args[] = nextWord();
    }
    return $op['name'] . ' ' . implode(' ', $args);
}

while (($inst = nextInstruction()) !== false) {
    echo "$inst\n";
}