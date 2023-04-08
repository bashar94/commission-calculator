# Project Name

## Requirements
This project requires the following dependencies to be installed:

- PHP 7
- PHPUnit test 6.5
- vlucas/phpdotenv (4.3) package


## Installation
- Clone the project from the repository.
- Navigate to the root directory of the project.
- Run the following command to install all required dependencies: ```composer install```

## Usage
To use the project, follow the instructions below:

- Copy the ```.env.example``` file and rename it to ```.env```.
- Update the ```.env``` file with the values for ```API_URL``` variable.
- Navigate to the ```'src'``` directory using the following command: ```cd src```
- Execute the script by running the following command: ```php script.php "filename.csv"```
  For example, if your CSV file is named input.csv, you would run the following command:
  ```php script.php input.csv```


## Testing
To run PHPUnit tests for the project, follow the instructions below:

- Navigate to the root directory of the project.
- Run the following command:```phpunit tests```