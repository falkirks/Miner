<?php
require_once __DIR__ . "/vendor/autoload.php";

$climate = new \League\CLImate\CLImate();
$climate->br()->flank("Preparing");
if(\miner\ComposerDetector::detect()){
    $climate->out(\miner\Output::indent("Detected composer at <white>" . \miner\ComposerDetector::getCommand() . "</white>"));
    $climate->br()->flank("Executing");
    $composer = new \miner\Composer(\miner\ComposerDetector::getCommand(), $climate);
    $composer->execute(array_slice($argv, 1));
    while($composer->getLine());
    $climate->br()->flank("Porting Infrastructure");
    if(is_dir("vendor")){
        $iterator = new RecursiveDirectoryIterator("vendor");
        $progress = $climate->progress(iterator_count(new RecursiveIteratorIterator($iterator)));
        searchDirectory($iterator, $progress, $climate);
    }
    else{
        $climate->comment(\miner\Output::indent("Nothing to port"));
    }
    $climate->br()->flank("Cleaning Infrastructure (beta)");

    $climate->br();
}
else{
    $climate->red()->underline(\miner\Output::indent("Error finding composer"));
}
function searchDirectory(RecursiveDirectoryIterator $iterator, \League\CLImate\TerminalObject\Dynamic\Progress $progress, \League\CLImate\CLImate $climate){
    foreach($iterator as $file){
        $file = explodePath($file);
        try{
            $progress->advance();
        }
        catch(Exception $e){

        }
        if($iterator->hasChildren()){
            searchDirectory($iterator->getChildren(), $progress, $climate);
        }
        else{
            if($file[count($file)-1] === "composer.json"){
                $autoloaders = json_decode(file_get_contents(implode("/", $file)), true)["autoload"];
                foreach(new RecursiveIteratorIterator(new RecursiveArrayIterator($autoloaders)) as $name => $item){
                    if(!is_array($item)){
                        $to = "src/" . str_replace("\\", "/", $name);
                        @mkdir($to, 0775, true);
                        file_put_contents($to . "/.miner", time());
                        copyDirectory(implode("/", array_slice($file, 0, -1)) . "/" . $item, $to, $progress);
                    }
                }
            }
        }
        usleep(2000);
    }
}
function copyDirectory($from, $to, \League\CLImate\TerminalObject\Dynamic\Progress $progress){
    foreach (
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($from, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST) as $item
    ) {
        //$progress->advance();
        if ($item->isDir()) {
            @mkdir($to . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
        } else {
            copy($item, $to . DIRECTORY_SEPARATOR . $iterator->getSubPathName());
        }
    }
}
function explodePath($path){
    return explode('/', $path);
}