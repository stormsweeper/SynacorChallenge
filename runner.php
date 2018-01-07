<?php

require_once __DIR__ . '/SynacorVM.php';

$vm = new SynacorVM(file_get_contents($argv[1]));
$vm->run();