<?php

//Declaring namespace
namespace LaswitchTech\CLI;

class phpCLI{
  
  public function __construct($argv){
    $this->CLI = new CLI();
    foreach($argv as $argument){
      if(substr($argument, 0, 2) === '--'){
        if($this->current != null){ array_push($this->CMD,['command' => str_replace('--','',$this->current),'options' => str_replace('-','',$this->options),'data' => $this->data]); }
        $this->current = $argument;
        $this->options = [];
        $this->data = [];
      }
      elseif(substr($argument, 0, 1) === '-'){
        if($this->current != null){ array_push($this->options,$argument); }
      }
      else {
        if($this->current != null){ array_push($this->data,$argument); }
      }
    }
    if($this->current != null){ array_push($this->CMD,['command' => str_replace('--','',$this->current),'options' => str_replace('-','',$this->options),'data' => $this->data]); }
    foreach($this->CMD as $cmd){
      $method = $cmd['command'];
      if(method_exists($this->CLI,$method)){
        $this->CLI->$method($cmd['data'],$cmd['options']);
      }
    }
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
