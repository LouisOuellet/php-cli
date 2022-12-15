![GitHub repo logo](/dist/img/logo.png)

# phpCLI
![License](https://img.shields.io/github/license/LouisOuellet/php-cli?style=for-the-badge)
![GitHub repo size](https://img.shields.io/github/repo-size/LouisOuellet/php-cli?style=for-the-badge&logo=github)
![GitHub top language](https://img.shields.io/github/languages/top/LouisOuellet/php-cli?style=for-the-badge)
![Version](https://img.shields.io/github/v/release/LouisOuellet/php-cli?label=Version&style=for-the-badge)

## Features
 - Command Line Interface

## Why you might need it
If you are looking for an easy start for your PHP CLI. Then this PHP Class is for you.

## Can I use this?
Sure!

## License
This software is distributed under the [GNU General Public License v3.0](https://www.gnu.org/licenses/gpl-3.0.en.html) license. Please read [LICENSE](LICENSE) for information on the software availability and distribution.

## Requirements
* PHP >= 8.0
* MySQL or MariaDB (Optional)

## Security
Please disclose any vulnerabilities found responsibly – report security issues to the maintainers privately.

## Installation
Using Composer:
```sh
composer require laswitchtech/php-cli
```

## How do I use it?

### Skeleton
Let's start with the skeleton of your CLI project directory.

```sh
├── api
├── config
│   └── config.json
├── Command
│   └── HelloCommand.php
└── Model
    └── HelloModel.php
```

* cli: The cli file is the entry-point of our application. It will initiate the Command being called in our application.
* config/config.json: The config file holds the configuration information of our CLI. Mainly, it will hold the database credentials. But you could use it to store other configurations.
* Command/: This directory will contain all of your Commands.
* Command/HelloCommand.php: the Hello Command file which holds the necessary application code to entertain CLI calls. Mainly the methods that can be called.
* Model/: This directory will contain all of your models.
* Model/HelloModel.php: the Hello model file which implements the necessary methods to interact with a table in the MySQL database.

### Models
Model files implements the necessary methods to interact with a table in the MySQL database. These model files needs to extend the Database class in order to access the database.

#### Naming convention
The name of your model file should start with a capital character and be followed by ```Model.php```.  If not, the bootstrap will not load it.
The class name in your Model files should match the name of the model file.

#### Example
```php

//Import BaseModel class into the global namespace
use LaswitchTech\phpCLI\BaseModel;

class HelloModel extends BaseModel {
  public function getHellos($limit) {
    return $this->select("SELECT * FROM Hellos ORDER BY id ASC LIMIT ?", ["i", $limit]);
  }
}
```

### Commands
Command files holds the necessary application code to entertain CLI calls. Mainly the methods that can be called. These Command files needs to extend the BaseCommand class in order to access the basic methods.

#### Naming convention
The name of your Command file should start with a capital character and be followed by ```Command.php```.  If not, the bootstrap will not load it. The class name in your Command files should match the name of the command file.

Finally, callable methods need to end with ```Action```.

#### Example
```php

//Import BaseCommand class into the global namespace
use LaswitchTech\phpCLI\BaseCommand;

class HelloCommand extends BaseCommand {

  public function worldAction($argv) {
    if(count($argv) > 0){
      foreach($argv as $name){
        $this->sendOutput('Hello ' . $name . '!');
      }
    } else {
      $this->sendOutput('Hello World!');
    }
  }
}
```

### Configurations
The config file holds the configuration information of our CLI. Mainly, it will hold the database credentials. But you could use it to store other configurations. The configuration file must be stored in config/config.php. As this file is already being loaded in the bootstrap.

#### Example

```json
{
    "sql": {
        "host": "localhost",
        "database": "demo",
        "username": "demo",
        "password": "demo"
    }
}
```

### CLI
The cli file is the entry-point of our application. It will initiate the Command being called in our application. The file itself can be named any way you want.

#### Example

```php
session_start();

//Import phpCLI class into the global namespace
//These must be at the top of your script, not inside a function
use LaswitchTech\phpCLI\phpCLI;

//Load Composer's autoloader
require 'vendor/autoload.php';

// Interpret Standard Input
if(defined('STDIN') && !empty($argv[1])){

  // Start Command
  new phpCLI($argv);
}
```

### Calling the CLI
Once you have setup your first Command and model, you can start calling your Command-Line.

#### Example

##### GET
```sh
GET /api.php/Hello/list?limit=2 HTTP/1.1
Authorization: Bearer cGFzczE=
Host: phpCLI.local
Hello-Agent: HTTPie
```

##### Output
```bash
php cli hello world
```
