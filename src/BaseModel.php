<?php

//Declaring namespace
namespace LaswitchTech\phpCLI;

//Import Database class into the global namespace
use LaswitchTech\phpDB\Database;

class BaseModel extends Database {

  public function __call($name, $arguments) {
    $this->output("\033[31mNot Implemented\033[39m");
    return false;
  }

  protected function set($string, $color = 'default'){
    if(isset($this->Colors[$color])){
      return $this->Colors[$color] . $string . $this->Colors['default'];
    } else {
      return $string;
    }
  }

  protected function output($string) {
    print_r($string . PHP_EOL);
  }
}
