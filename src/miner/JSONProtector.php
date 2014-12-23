<?php
namespace miner;

class JSONProtector{
    private $wasProtected;
    private $file;
    public function __construct($file){
        $this->file = $file;
        $this->wasProtected = false;
    }
    public function protect(){
        if($this->wasProtected) {
            if (!is_file($this->file)) return;
            $data = file_get_contents($this->file);
            if ($data{0} !== "#") {
                $data = "#miner\n" . $data;
            }
            file_put_contents($this->file, $data);
        }
    }
    public function unprotect(){
        if(!is_file($this->file)) return;
        $data = file_get_contents($this->file);
        if($data{0} === "#"){
            $this->wasProtected = true;
            $data = substr($data, strpos($data, "\n")+1);;
        }
        file_put_contents($this->file, $data);
    }
}