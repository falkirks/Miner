<?php
namespace miner;

class Output{
    public static function indent($str, $count = 1){
        return str_repeat("     ", $count) . $str;
    }
}