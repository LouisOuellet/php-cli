<?php

//Declaring namespace
namespace LaswitchTech\phpCLI;

//Import Database class into the global namespace
use LaswitchTech\phpDB\Database;

class BaseModel extends Database {

  public function __call($name, $arguments) {
    $this->sendOutput("Not Implemented");
    return false;
  }

  protected function sendOutput($data) {
    echo $data . PHP_EOL;
  }
}
