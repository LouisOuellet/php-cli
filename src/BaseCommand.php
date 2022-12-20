<?php

//Declaring namespace
namespace LaswitchTech\phpCLI;

//Import Factory class into the global namespace
use Composer\Factory;

class BaseCommand {

  protected $Path = null;
  protected $Colors = [
    "default" => "\033[39m",
    "black" => "\033[30m",
    "red" => "\033[31m",
    "green" => "\033[32m",
    "yellow" => "\033[33m",
    "blue" => "\033[34m",
    "magenta" => "\033[35m",
    "cyan" => "\033[36m",
    "light-gray" => "\033[37m",
    "dark-gray" => "\033[90m",
    "light-red" => "\033[91m",
    "light-green" => "\033[92m",
    "light-yellow" => "\033[93m",
    "light-blue" => "\033[94m",
    "light-magenta" => "\033[95m",
    "light-cyan" => "\033[96m",
    "white" => "\033[97m",
  ];

  public function __construct(){
    $this->Path = dirname(\Composer\Factory::getComposerFile());
  }

  public function __call($name, $arguments) {
    $this->error("Not implemented");
    return false;
  }

  protected function output($string) {
    print_r($string . PHP_EOL);
  }

  protected function set($string, $color = 'default'){
    if(isset($this->Colors[$color])){
      return $this->Colors[$color] . $string . $this->Colors['default'];
    } else {
      return $string;
    }
  }

  protected function error($string) {
    $this->output($this->set($string, 'red'));
  }

  protected function success($string) {
    $this->output($this->set($string, 'green'));
  }

  protected function warning($string) {
    $this->output($this->set($string, 'yellow'));
  }

  protected function info($string) {
    $this->output($this->set($string, 'cyan'));
  }

  protected function input($string, $options = null, $default = null){
    $modes = ['select','text','string'];
    $mode = 'string';
    if($options != null || $options == 0){
      if(is_array($options)){
        $mode = 'select';
      } else if(is_int($options)){
        $mode = 'text';
      } else {
        if(is_string($options)){ $default = $options; }
      }
    }
    $stdin = function(){
      $handle = fopen ("php://stdin","r");
      return str_replace("\n",'',fgets($handle));
    };
    switch($mode){
      case"select":
        $answer = null;
        foreach($options as $key => $value){
          $options[$key] = strtoupper($value);
        }
        while(!in_array(strtoupper($answer),$options)){
          print_r($string . ' (');
          foreach($options as $key => $option){
            if($key > 0){ print_r('/'); }
            print_r($option);
          }
          print_r(')');
          if($default != null){ print_r('['.$default.']'); }
          print_r(': ');
          $answer = $stdin();
          if($default != null && $answer == ""){ $answer = $default; }
        }
        break;
      case"text":
        $answer = '';
        $exits = ['END','EXIT','QUIT','EOF',':Q',''];
        $count = 0;
        $max = 5;
        $print = false;
        if(is_bool($default)){ $print = $default; }
        if(is_int($options)){ $max = $options; }
        if($print){
          print_r($string . ' type (');
          foreach($exits as $key => $exit){
            if($key > 0){ print_r('/'); }
            print_r($exit);
          }
          print_r(') to exit' . PHP_EOL);
        } else {
          print_r($string . PHP_EOL);
        }
        do {
          $line = fgets(STDIN);
          if(in_array(strtoupper(str_replace("\n",'',$line)),$exits)){
            if($max <= 0){ $max = 1; }
            $count = $max;
          } else { $answer .= $line; $count++; }
        } while ($count < $max || $max <= 0);
        break;
      default:
        $answer = null;
        while($answer == null){
          print_r($string . ' ');
          if($default != null){ print_r('['.$default.']'); }
          print_r(': ');
          $answer = $stdin();
          if($default != null && $answer == ""){ $answer = $default; }
          if($answer == ''){ $answer = null; }
        }
        break;
    }
    $answer = trim($answer,"\n");
    if($answer == ''){ $answer = null; }
    return $answer;
  }
}
