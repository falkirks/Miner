<?php
namespace miner;

use League\CLImate\CLImate;

class Composer{
    private $command;
    private $climate;
    private $pipes;
    private $handle;
    public function __construct($command, CLImate $climate){
        $this->command = $command;
        $this->climate = $climate;
    }
    public function execute(array $args){
        $args = implode(" ", $args);
        $cmd = $this->command . " " . $args;
        $this->climate->comment(Output::indent("Executing: " . $cmd));
        $this->handle = proc_open($cmd, [
            0 => ["pipe", "r"],
            1 => ["pipe", "w"],
            2 => ["pipe", "a"]
        ], $this->pipes);
        stream_set_blocking($this->pipes[1],0);
    }
    public function getLine(){
        $line = fgets($this->pipes[1]);
        if($line !== false){
            $this->climate->info(Output::indent(substr($line, 0, -1), 2));
        }
        if(!proc_get_status($this->handle)["running"]){
            proc_close($this->handle);
            return false;
        }
        else{
            return true;
        }
    }
}