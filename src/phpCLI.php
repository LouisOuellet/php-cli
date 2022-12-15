<?php

//Declaring namespace
namespace LaswitchTech\phpCLI;

//Import Factory class into the global namespace
use Composer\Factory;

//Import ReflectionClass class into the global namespace
use \ReflectionClass;

class phpCLI {

  protected $Path = null;
  protected $Debug = false;
  protected $Reflector = null;
  protected $CLI;

  public function __construct($argv){

    // Configure CLI
    $this->configure();

    // Include all model files
    if(is_dir($this->Path . "/Model")){
      foreach(scandir($this->Path . "/Model/") as $model){
        if(str_contains($model, 'Model.php')){
          require_once $this->Path . "/Model/" . $model;
        }
      }
    }

    // Parse Standard Input
    if(count($argv) > 2){

      // Identify the Defining File
      $this->Reflector = $argv[0];
      unset($argv[0]);

      // Identify the Command File
      $strCommandName = ucfirst($argv[1] . "Command");
      unset($argv[1]);

      // Identify the Action
      $strMethodName = $argv[2] . "Action";
      unset($argv[2]);

      // Assemble Command
      if(is_file($this->Path . "/Command/" . $strCommandName . ".php")){

        // Load Command File
        require $this->Path . "/Command/" . $strCommandName . ".php";

        // Create Command
        $CLI = new $strCommandName();
        $CLI->{$strMethodName}($argv);
      } else {
        $this->error('Could not find Command');
      }
    } else {
      $this->error("Could not identify the Command and/or Action");
    }
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

  protected function error($string) {
    $this->output($this->set($string, 'red'));
  }

  protected function configure(){

    // Save Root Path
    $this->Path = dirname(\Composer\Factory::getComposerFile());
  }
}
