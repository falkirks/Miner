<?php
namespace miner;

class ComposerDetector{
    private static $command;
    public static function detect(){
        if(strpos(`composer`, "not found") === false){
            ComposerDetector::$command = "composer";
            return true;
        }
        if(strpos(`php ~/composer.phar`, "Could not open input file") === false){
            ComposerDetector::$command = "php ~/composer.phar";
            return true;
        }

    }
    public static function getCommand(){
        return ComposerDetector::$command;
    }
}