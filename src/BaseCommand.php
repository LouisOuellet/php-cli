<?php

//Declaring namespace
namespace LaswitchTech\phpCLI;

//Import Factory class into the global namespace
use Composer\Factory;

class BaseCommand {

  protected $Path = null;

  public function __construct(){
    $this->Path = dirname(\Composer\Factory::getComposerFile());
  }

  public function __call($name, $arguments) {
    $this->sendOutput("Not Implemented");
    return false;
  }

  protected function sendOutput($data) {
    echo $data . PHP_EOL;
  }

  protected function request($text, $mode = 'single', $max = 5){
    if($mode == 'single'){
      echo $text . ' ';
      $handle = fopen ("php://stdin","r");
      return str_replace("\n",'',fgets($handle));
    } elseif($mode == 'multi'){
      echo $text . " (END)" . PHP_EOL;
      $count = 0;
      $return = '';
      do {
        $line = fgets(STDIN);
        if(in_array(str_replace("\n",'',$line),['END','EXIT','QUIT','EOF',':q',':Q',''])){
          if($max <= 0){ $max = 1; }
          $count = $max;
        }
        else { $return .= $line; $count++; }
      } while ($count < $max || $max <= 0);
      return $return;
    }
  }
}
