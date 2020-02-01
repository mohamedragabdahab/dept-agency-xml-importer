# XML Importer Coding Challenge

This is a project based on [Symfony4](https://symfony.com/doc/current/index.html#gsc.tab=0), but to do this challenge you don't need to be familiar with the framework.  

The actual code you are going to write can be framework-agnostic. 

The goal of this application is to import the contents of a XML file to a database using [Doctrine ORM](https://www.doctrine-project.org/projects/orm.html).

The entity models are already defined, as well as a class to perform saving, see more at the Hints section.

## Requirements

- PHP >=7.2
- MySQL/PostgreSQL Database
- Terminal
- [Composer](https://getcomposer.org/)

## Installation

- Put the code anywhere where you can run PHP (at least 7.2)
- A webserver is not required, as this only requires the commandline.
- Run `$ composer install` to install the vendors
- Install a MySQL or PostgresSQL Server (or use an existing if you already have)
- Configure the connection string to the database in `.env` (e.g. `DATABASE_URL=mysql://root:root@127.0.0.1:3306/xml_import`)
- Run `$ bin/console doctrine:database:create` to install the database
- Run `$ bin/console doctrine:schema:update --force` to create the database schema

## Usage

In the terminal, run `$ bin/console import data/products.xml` to run the import command.

## Hints

To start with the challenge, navigate to the class `App\Command\ImportCommand`.

### Symfony 4 Dependency Injection

As this project is based on Symfony4, Classes are being auto-wired and -configured. 

This means you don't have to worry about instantiating or injection here, just write your code and Symfony should wire everything together.

### Doctrine ORM

The entities are already defined, you don't need to set these up:

- `App\Entity\Product`
- `App\Entity\ProductVariant`

Also, a class for creating and saving the entities already exists as well:

- `App\Import\Writer\ProductWriter`

It's handling the interaction with the actual Doctrine ORM, so you don't have to worry about the Entity Manager, instantiating the entity classes and passing them will be enough.


## Tasks

## Task 1 - Importer

- Import the file `data/products.xml`
- A Product must represent the abstract container for the product in a certain color
- A ProductVariant must be related to the correct Product and contain a specific size
- SKUs for Product and ProductVariant entities have to be unique
- The provided data in the XML file is not consistent, find a solution for this
- In the end you need to have `5` products and `13` variants

## Task 2 - Size Mapper

Sometimes, sizes come in country specific measurements instead of generic sizes (S, M, L)

- Use the mapping table below to normalise the sizes
- If a size matches both largest and smallest measurement of a size, set the mapped size to both (e.g. S/M)
- If a size can not be matched, use the original size 

| Size | inches (US) | cm (DE) |
| ------ | ------ | ------ |
| S | 36-38 | 91-96 |
| M | 38-40 | 96-101 |
| L | 40-42 | 101-106 |

## Bonus

- Write Unit Tests for your classes
- Run the provided Functional test to verify the ProductImporter is actually working

Testing setup:

```
$ APP_ENV=test bin/console doctrine:database:create --if-not-exists
$ APP_ENV=test bin/console doctrine:schema:update --force
```

Executing tests and analyzer tools:

```
$ vendor/bin/phpunit
$ vendor/bin/phpstan analyze src -l 1
$ vendor/bin/phpcs --standard=ruleset.xml
```
