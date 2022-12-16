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
  protected $Settings = null;
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
    if(count($argv) > 0){

      // Identify the Defining File
      $this->Reflector = $argv[0];
      unset($argv[0]);

      // Identify the Command File
      if(count($argv) > 0){
        $strCommandName = ucfirst($argv[1] . "Command");
        unset($argv[1]);

        // Identify the Action
        if(count($argv) > 0){
          $strMethodName = $argv[2] . "Action";
          unset($argv[2]);

          // Assemble Command
          if(is_file($this->Path . "/Command/" . $strCommandName . ".php")){

            // Load Command File
            require $this->Path . "/Command/" . $strCommandName . ".php";

            // Create Command
            $CLI = new $strCommandName();
            $CLI->{$strMethodName}(array_values($argv));
          } else {
            $this->output('Not implemented');
          }
        } else {
          $this->output("Missing action");
        }
      } else {
        $this->output("Missing command");
      }
    } else {
      $this->output("Could not identify the defining file");
    }
  }

  protected function output($string) {
    print_r($string . PHP_EOL);
  }

  protected function configure(){

    // Save Root Path
    $this->Path = dirname(\Composer\Factory::getComposerFile());

    // Include main configuration file
    if(is_file($this->Path . "/config/config.json")){

      // Save all settings
    	$this->Settings = json_decode(file_get_contents($this->Path . '/config/config.json'),true);

      //MySQL Configuration Information
      if(isset($this->Settings['sql'])){
        if(!defined("DB_HOST")){ define("DB_HOST", $this->Settings['sql']['host']); }
        if(!defined("DB_USERNAME")){ define("DB_USERNAME", $this->Settings['sql']['username']); }
        if(!defined("DB_PASSWORD")){ define("DB_PASSWORD", $this->Settings['sql']['password']); }
        if(!defined("DB_DATABASE_NAME")){ define("DB_DATABASE_NAME", $this->Settings['sql']['database']); }

        // MySQL Debug
        if(isset($this->Settings['sql']['debug'])){
          $this->Debug = $this->Settings['sql']['debug'];
        }
      }
    }

    // MySQL Debug
    if(!defined("DB_DEBUG")){ define("DB_DEBUG", $this->Debug); }
  }
}
