<?php

// because who has time to manually write this array?

$regex = "#([a-z]+): \d+([a-z\s]+)?\\n  ([^\\n]+)#s";

preg_match_all($regex, file_get_contents('arch-spec'), $spec, PREG_SET_ORDER);

$spec = array_map(
    function($op) {
        return [
            'name' => $op[1],
            'nargs' => strlen($op[2]) / 2,
            'desc' => $op[3],
        ];
    },
    $spec
);

var_export($spec);