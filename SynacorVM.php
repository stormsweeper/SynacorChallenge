<?php

class SynacorVM
{
    const MAX_INT = 2 ** 15;
    const OPCODES = ["halt","set","push","pop","eq","gt","jmp","jt","jf","add","mult","mod","and","or","not","rmem",
                     "wmem","call","ret","out","in","noop"];

    private $memory = '';
    private $stack = [];
    private $registers = [];
    private $cursor = 0;

    function __construct(string $bin)
    {
        $this->memory = str_pad($bin, self::MAX_INT * 2, "\x00\x00");
    }

    function readRegister(int $register)
    {
        return $this->registers[$register] ?? 0;
    }

    function setRegister(int $register, int $value) {
        $this->registers[$register] = $value;
    }

    function readMemory(int $loc) {
        //echo "read mem {$loc}\n";
        $word = substr($this->memory, $loc * 2, 2);
        return unpack('v', $word)[1];
    }

    function writeMemory(int $loc, int $value) {
        $word = pack('v', $value);
        $this->memory[$loc * 2] = $word[0];
        $this->memory[$loc * 2 + 1] = $word[1];
    }

    function coerceValue(int $value) {
        if ($value < self::MAX_INT) {
            return $value;
        } elseif ($value < self::MAX_INT + 8) {
            $register = $value - self::MAX_INT;
            return $this->readRegister($register);
        }
        throw new RuntimeException("Value is not a literal or register: {$value}");
    }

    function nextInstruction() {
        $cmd = $this->readMemory($this->cursor++);
        if ($cmd > 21) {
            throw new RuntimeException("Not a valid cmd: {$cmd}");
        }
        $this->{self::OPCODES[$cmd]}();
    }

    function nextArg() {
        return $this->coerceValue($this->readMemory($this->cursor++));
    }

    function run() {
        while (true) {
            $this->nextInstruction();
        }
    }

    function halt() {
        exit();
    }

    function set() {
        $a = $this->nextArg();
        $b = $this->nextArg();
        $this->setRegister($a, $b);
    }    

    function push() {
        $a = $this->nextArg();
        array_push($this->stack, $a);
    }

    function jmp() {
        $this->cursor = $this->nextArg();
    }

    function jt() {
        $a = $this->nextArg();
        $b = $this->nextArg();
        if ($a > 0) {
            $this->cursor = $b;
        }
    }

    function out() {
        $ascii = $this->nextArg();
        echo chr($ascii);
    }

    function noop() {
        
    }

}