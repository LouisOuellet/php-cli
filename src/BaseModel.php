<?php

//Declaring namespace
namespace LaswitchTech\phpCLI;

//Import Database class into the global namespace
use LaswitchTech\phpDB\Database;

class BaseModel extends Database {

  public function __call($name, $arguments) {
    $this->output("Not Implemented");
    return false;
  }

  protected function output($string) {
    print_r($string . PHP_EOL);
  }
}
