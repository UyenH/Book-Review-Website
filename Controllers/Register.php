<?php
namespace Ijdb\Controllers;
use \Ninja\DatabaseTable;

class Register {
	private $usersTable;

	public function __construct(DatabaseTable $usersTable) {
		$this->usersTable = $usersTable;
	}

	public function registrationForm() {
		return ['template' => 'register.html.php', 
				'title' => 'Register an account'];
	}


	public function success() {
		return ['template' => 'registersuccess.html.php', 
			    'title' => 'Registration Successful'];
	}

	public function registerUser() {
		$user = $_POST['user'];

		//Assume the data is valid to begin with
		$valid = true;
		$errors = [];

		//But if any of the fields have been left blank, set $valid to false
		if (empty($user['username'])) {
			$valid = false;
			$errors[] = 'user name cannot be blank';
		}

		if (empty($user['email'])) {
			$valid = false;
			$errors[] = 'Email cannot be blank';
		}
		else if (filter_var($user['email'], FILTER_VALIDATE_EMAIL) == false) {
			$valid = false;
			$errors[] = 'Invalid email address';
		}
		else { //if the email is not blank and valid:
			//convert the email to lowercase
			$user['email'] = strtolower($user['email']);

			//search for the lowercase version of `$user['email']`
			if (count($this->usersTable->find('email', $user['email'])) > 0) {
				$valid = false;
				$errors[] = 'That email address is already registered';
			}
		}


		if (empty($user['password'])) {
			$valid = false;
			$errors[] = 'Password cannot be blank';
		}

		//If $valid is still true, no fields were blank and the data can be added
		if ($valid == true) {
			//Hash the password before saving it in the database
			$user['password'] = password_hash($user['password'], PASSWORD_DEFAULT);

			//When submitted, the $user variable now contains a lowercase value for email
			//and a hashed password
			$this->usersTable->save($user);

			//header('Location: /user/success'); 5/25/18 JG DEL1L  org
            header('Location: index.php?user/success'); //5/25/18 JG NEW1L  

		}
		else {
			//If the data is not valid, show the form again
			return ['template' => 'register.html.php', 
				    'title' => 'Register an account',
				    'variables' => [
				    	'errors' => $errors,
				    	'user' => $user
				    ]
				   ]; 
		}
	}

	public function list() {
		$users = $this->usersTable->findAll();

		return ['template' => 'userlist.html.php',
				'title' => 'User List',
				'variables' => [
						'users' => $users
					]
				];
	}

	public function permissions() {

		$user = $this->usersTable->findById($_GET['id']);

		$reflected = new \ReflectionClass('\Ijdb\Entity\User');
		$constants = $reflected->getConstants();

		return ['template' => 'permissions.html.php',
				'title' => 'Edit Permissions',
				'variables' => [
						'User' => $user,
						'permissions' => $constants
					]
				];	
	}

	public function savePermissions() {
		$user = [
			'id' => $_GET['id'],
			'permissions' => array_sum($_POST['permissions'] ?? [])
		];

		$this->usersTable->save($user);

		//header('location: /user/list');  6/3/18 JG DEL1L  org
		header('location: index.php?user/list');    // 6/3/18 JG MOD1L
	}
}