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

    function debugCommand()
    {
        
        $cmdline = trim(readline('DEVHAX: '));
        $cmdline = explode(' ', $cmdline);
        $cmd = array_shift($cmdline);
        switch ($cmd) {
            case 'dumpreg':
                echo json_encode($this->registers);
                break;

            case 'setreg':
                list ($reg, $value) = $cmdline;
                $this->setRegister($reg, $value);
                break;

            case 'memread':
                echo $this->readMemory($cmdline[0]);
                break;

            case 'memwrite':
                echo $this->writeMemory($cmdline[0], $cmdline[1]);
                break;

            case 'cursor':
                echo '' . $this->cursor;
                break;

            case 'dumpstack':
                echo json_encode($this->stack);
                break;

            case 'pushstack':
                echo '' . array_push($this->stack, intval($cmdline[0]));
                break;

            case 'popstack':
                echo '' . array_pop($this->stack);
                break;
        }
    }

    function readRegister(int $register)
    {
        return $this->registers[$register] ?? 0;
    }

    function setRegister(int $register, int $value) {
        $this->registers[$register] = $value;
    }

    function readMemory(int $loc) {
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
        //echo self::OPCODES[$cmd] . "\n";
        $this->{'op_' . self::OPCODES[$cmd]}();
    }

    function nextArg() {
        return $this->coerceValue($this->readMemory($this->cursor++));
    }

    function nextRegister() {
        $reg = $this->readMemory($this->cursor++);
        if ($reg < self::MAX_INT || $reg >= self::MAX_INT + 8) {
            throw new RuntimeException("Value is not a register: {$reg}");
        }
        return $reg - self::MAX_INT;
    }

    function run() {
        while (true) {
            //echo "\nCURSOR {$this->cursor}\n";
            $this->nextInstruction();
        }
    }

    function op_halt() {
        exit();
    }

    function op_set() {
        $a = $this->nextRegister();
        $b = $this->nextArg();
        $this->setRegister($a, $b);
    }    

    function op_push() {
        $a = $this->nextArg();
        array_push($this->stack, $a);
    }

    function op_pop() {
        $a = $this->nextRegister();
        if (!$this->stack) {
            throw new RuntimeException("Can't pop empty stack");
        }
        $this->setRegister($a, array_pop($this->stack));
    }

    function op_eq() {
        $a = $this->nextRegister();
        $b = $this->nextArg();
        $c = $this->nextArg();
        $val = $b === $c ? 1 : 0;
        $this->setRegister($a, $val);
    }

    function op_gt() {
        $a = $this->nextRegister();
        $b = $this->nextArg();
        $c = $this->nextArg();
        $val = $b > $c ? 1 : 0;
        $this->setRegister($a, $val);
    }

    function op_jmp() {
        $this->cursor = $this->nextArg();;
    }

    function op_jt() {
        $a = $this->nextArg();
        $b = $this->nextArg();
        if ($a > 0) {
            $this->cursor = $b;
        }
    }

    function op_jf() {
        $a = $this->nextArg();
        $b = $this->nextArg();
        if ($a === 0) {
            $this->cursor = $b;
        }
    }

    function op_add() {
        $a = $this->nextRegister();
        $b = $this->nextArg();
        $c = $this->nextArg();
        $val = ($b + $c) % self::MAX_INT;
        $this->setRegister($a, $val);
    }

    function op_mult() {
        $a = $this->nextRegister();
        $b = $this->nextArg();
        $c = $this->nextArg();
        $val = ($b * $c) % self::MAX_INT;
        $this->setRegister($a, $val);
    }

    function op_mod() {
        $a = $this->nextRegister();
        $b = $this->nextArg();
        $c = $this->nextArg();
        $val = $b % $c;
        $this->setRegister($a, $val);
    }

    function op_and() {
        $a = $this->nextRegister();
        $b = $this->nextArg();
        $c = $this->nextArg();
        $val = ($b & $c + self::MAX_INT) % self::MAX_INT;
        $this->setRegister($a, $val);
    }

    function op_or() {
        $a = $this->nextRegister();
        $b = $this->nextArg();
        $c = $this->nextArg();
        $val = $b | $c;
        $this->setRegister($a, $val);
    }

    function op_not() {
        $a = $this->nextRegister();
        $b = $this->nextArg();
        $val = (~ $b + self::MAX_INT) % self::MAX_INT;
        $this->setRegister($a, $val);
    }

    function op_rmem() {
        $a = $this->nextRegister();
        $b = $this->nextArg();
        $val = $this->readMemory($b);
        $this->setRegister($a, $val);
    }

    function op_wmem() {
        $a = $this->nextArg();
        $b = $this->nextArg();
        $this->writeMemory($a, $b);
    }

    function op_call() {
        $a = $this->nextArg();
        array_push($this->stack, $this->cursor);
        $this->cursor = $a;
    }

    function op_ret() {
        if (!$this->stack) {
            throw new RuntimeException("Can't ret from empty stack");
        }
        
        $this->cursor = array_pop($this->stack);
    }

    function op_out() {
        $ascii = $this->nextArg();
        echo chr($ascii);
    }

    function op_in() {
        $a = $this->nextRegister();
        $input = ord(fgetc(STDIN));
        // hack debug mode
        if ($input === 27) {
            // do debug
            $this->debugCommand();

            // give a newline
            $input = 10;
        }
        $this->setRegister($a, $input);
    }

    function op_noop() {
        
    }

}