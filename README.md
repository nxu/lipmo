LiPMO
=====

## What is LiPMO?
LiPMO is exactly what its name suggests. It is the abbrevation of **Li**ghtweight **P**HP - **M**ySQL **O**RM. 

## Features
* Automatic mapping of MySQL tables to PHP classes
* Insert
* Update
* Delete
* Select by id
* Select all
* Select with condition(s)
* Paging support for selects (start and limit values)
LiPMO uses PDO parametrized queries which improves the security and performance of the MySQL queries.

## Non-features
The part "lightweight" is meant very seriously. LiPMO features only the minimum of what an object relational mapper needs, nothing fancy. Therefore it needs no special installation or configuration - and it's extremely easy to use.
The biggest feature of many huge ORM Frameworks that LiPMO does not support is the mapping of table relations.

## Server side requirements
LiPMO can be installed on most free webhosts. The requirements are the following:
* PHP 5.1 with [PDO](http://www.php.net/manual/en/intro.pdo.php) enabled
* MySQL

## Installation
1. Create your database and your tables. 
2. Make sure **all** of your tables contain a UNIQUE field __id__. Most of the time this will be the PK anyways.
3. Get the LiPMO directory.
4. Edit the Config.php file. Believe me, you don't need more explanation.
5. Create your entity classes (see below) and save them to the LiPMO directory.
6. Make sure there is nothing but the LiPMO engine and entity files in the LiPMO directory.

## Creating the entity classes
Entity classes are not created automatically, you have to create them by yourself. You don't have to worry, it only takes a few minutes and it is actually very simple. You can check the provided example "Sample.php" (you can actually delete it from your project).

Let's assume you have a table called __user__ with 4 fields: id, username, password, email. Let's create an entity class for it:

```PHP
<?php
	// The superclass has to be included
	require_once 'DBEntity.php';
	
	class User extends DBEntity {
		
		// Set the actual name of the table in the constructor
		public function __construct() {
			$this->tableName = "user";
		}
	
		// Implement the properties.
		// Note that you don't need the id as it has been already implemented by the superclass DBEntity.
		// Also, MySQL is case-insensitive.
		public $username;
		public $password;
		public $email;
	}
?>
```

Yup, that's it. Your User entity is ready to be used.

## Usage
### Include
Whenever you use LiPMO you have to include it in your file:
```PHP
require_once('LiPMO/IncludeList.php');
```

### Database operations
Note that you __always__ need to instantiate the entity classes, there are no static methods.

#### Insert
```PHP
// Id must not be set.
$user = new User();
$user->username = 'admin';
$user->password = 'Admin1234';
$user->email    = 'email@address.com';
$userId = $user->insert();
```
**Return value:** The id of the created entity, false in case of an error.

#### Update
```PHP
// Id is used to identify the entity in the database
$user = new User();
$user->id = 1;
$user->username = newUsername;
$success = $user->update();
```
**Return value:** True on success, otherwise false.

#### Delete
```PHP
// Id is used to identify the entity in the database
$user = new User();
$user->id = 1;
$success = $user->delete();
```
**Return value:** True on success, otherwise false.

#### Select by id
```PHP
// Again, you need an instance
$user = new User();
$user = $user->selectById(4);
```
**Return value:** Selected entity or null.

#### Select all
```PHP
// Again, you need an instance
$user = new User();
$users = $user->selectAll();

// Alternatively, you can select the elements 21-40 as follows:
$users = $user->selectAll(1, 20);
```
**Return value:** Array of entities, single entity if there is only one found, or null.

#### Select with criteria(s)
```PHP
// Again, you need an instance
$user = new User();

// Example: select users who have a GMail address
//          and have the word 'admin' in their username
// This example uses positional parameters (?) but you can name them.
// In that case, you need to provide key-value pairs in the array.
// Paging (starting index / selection limit) still works.
$users = $user->select('email LIKE ? AND username LIKE ?', array('gmail.com', 'admin'));
```
**Return value:** Array of entities, single entity if there is only one found, or null.

## Support and feedback
Feel free to contact me on GitHub or in email (nXu@nXu.hu).
Also, any feedback, suggestion and reports of bugs or vulnerabilities are welcome (I recommend using the Issue page in the last two cases).

## License
LiPMO is licensed under the MIT License. See LICENSE for details.
