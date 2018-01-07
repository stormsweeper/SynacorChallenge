<?php

$opcodes = array (
  0 => 
  array (
    'name' => 'halt',
    'nargs' => 0,
    'desc' => 'stop execution and terminate the program',
  ),
  1 => 
  array (
    'name' => 'set',
    'nargs' => 2,
    'desc' => 'set register <a> to the value of <b>',
  ),
  2 => 
  array (
    'name' => 'push',
    'nargs' => 1,
    'desc' => 'push <a> onto the stack',
  ),
  3 => 
  array (
    'name' => 'pop',
    'nargs' => 1,
    'desc' => 'remove the top element from the stack and write it into <a>; empty stack = error',
  ),
  4 => 
  array (
    'name' => 'eq',
    'nargs' => 3,
    'desc' => 'set <a> to 1 if <b> is equal to <c>; set it to 0 otherwise',
  ),
  5 => 
  array (
    'name' => 'gt',
    'nargs' => 3,
    'desc' => 'set <a> to 1 if <b> is greater than <c>; set it to 0 otherwise',
  ),
  6 => 
  array (
    'name' => 'jmp',
    'nargs' => 1,
    'desc' => 'jump to <a>',
  ),
  7 => 
  array (
    'name' => 'jt',
    'nargs' => 2,
    'desc' => 'if <a> is nonzero, jump to <b>',
  ),
  8 => 
  array (
    'name' => 'jf',
    'nargs' => 2,
    'desc' => 'if <a> is zero, jump to <b>',
  ),
  9 => 
  array (
    'name' => 'add',
    'nargs' => 3,
    'desc' => 'assign into <a> the sum of <b> and <c> (modulo 32768)',
  ),
  10 => 
  array (
    'name' => 'mult',
    'nargs' => 3,
    'desc' => 'store into <a> the product of <b> and <c> (modulo 32768)',
  ),
  11 => 
  array (
    'name' => 'mod',
    'nargs' => 3,
    'desc' => 'store into <a> the remainder of <b> divided by <c>',
  ),
  12 => 
  array (
    'name' => 'and',
    'nargs' => 3,
    'desc' => 'stores into <a> the bitwise and of <b> and <c>',
  ),
  13 => 
  array (
    'name' => 'or',
    'nargs' => 3,
    'desc' => 'stores into <a> the bitwise or of <b> and <c>',
  ),
  14 => 
  array (
    'name' => 'not',
    'nargs' => 2,
    'desc' => 'stores 15-bit bitwise inverse of <b> in <a>',
  ),
  15 => 
  array (
    'name' => 'rmem',
    'nargs' => 2,
    'desc' => 'read memory at address <b> and write it to <a>',
  ),
  16 => 
  array (
    'name' => 'wmem',
    'nargs' => 2,
    'desc' => 'write the value from <b> into memory at address <a>',
  ),
  17 => 
  array (
    'name' => 'call',
    'nargs' => 1,
    'desc' => 'write the address of the next instruction to the stack and jump to <a>',
  ),
  18 => 
  array (
    'name' => 'ret',
    'nargs' => 0,
    'desc' => 'remove the top element from the stack and jump to it; empty stack = halt',
  ),
  19 => 
  array (
    'name' => 'out',
    'nargs' => 1,
    'desc' => 'write the character represented by ascii code <a> to the terminal',
  ),
  20 => 
  array (
    'name' => 'in',
    'nargs' => 1,
    'desc' => 'read a character from the terminal and write its ascii code to <a>; it can be assumed that once input starts, it will continue until a newline is encountered; this means that you can safely read whole lines from the keyboard and trust that they will be fully read',
  ),
  21 => 
  array (
    'name' => 'noop',
    'nargs' => 0,
    'desc' => 'no operation',
  ),
);