# Word-to-Number Conversion System

The Word-to-Number Conversion System is a Laravel project that allows users to convert words representing numbers into their numerical form, and vice versa. Additionally, the system can convert the PHP value to USD equivalent of the converted number or word.

## Installation

Follow the steps below to set up and run the project locally.

### Prerequisites

- PHP (>= 7.4)
- Composer (https://getcomposer.org/)
- MySQL (or any other supported database)
- Web server (e.g., Apache, Nginx)

### 1. Clone the repository

git clone https://github.com/pawdgreyt/word-to-number-conversion-vice-versa.git
cd word-to-number-conversion-vice-versa

### 2. Install dependencies

Use Composer to install the project dependencies.

### 3. Set up the environment

Create a copy of the `.env.example` file and rename it to `.env`. Update the configuration options like database connection, app URL, etc., in the `.env` file according to your local setup.

cp .env.example .env

### 4. Generate the application key

Run the following command to generate the application key for your Laravel project.

php artisan key:generate

### 5. Migrate the database

Run the migrations to set up the database schema.

php artisan migrate

### 6. Serve the application

Start the local development server.

php artisan serve


The application should now be accessible at `http://localhost:8000`.

## Usage

Once the project is up and running, you can use the system to perform the following conversions:

1. Convert word to number: Type a word representation of a number (e.g., "one hundred and twenty" or "onehundredandtwenty") in the input field, and the system will convert it to its numerical form (e.g., 120).

2. Convert number to word: Enter a numerical value in the input field, and the system will convert it to its word representation.

3. Convert PHP value to USD: After converting a word or number, the system can also display the equivalent value in USD.

