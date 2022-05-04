<?php
namespace Ijdb;

class IjdbRoutes implements \Ninja\Routes {
	private $usersTable;
	private $booksTable;
	private $genresTable;
	private $bookGenresTable;
	private $authentication;
	//UHo new 1l: add new attribute called "reviewTable"
	private $reviewTable;

	public function __construct() {
		include __DIR__ . '/../../includes/DatabaseConnection.php';

		$this->booksTable = new \Ninja\DatabaseTable($pdo, 'book', 'id', '\Ijdb\Entity\Book', [&$this->usersTable, &$this->bookGenresTable, &$this->reviewTable]);
 		$this->usersTable = new \Ninja\DatabaseTable($pdo, 'user', 'id', '\Ijdb\Entity\User', [&$this->booksTable, &$this->reviewTable]);
 		$this->genresTable = new \Ninja\DatabaseTable($pdo, 'genre', 'id', '\Ijdb\Entity\Genre', [&$this->booksTable, &$this->bookGenresTable]);
 		$this->bookGenresTable = new \Ninja\DatabaseTable($pdo, 'genreBook', 'genreId');
		$this->authentication = new \Ninja\Authentication($this->usersTable, 'email', 'password');
		//UHo new 1l: add the review table
		$this->reviewTable = new \Ninja\DatabaseTable($pdo, 'review', 'id', '\Ijdb\Entity\Review',[&$this->usersTable, &$this->booksTable]);
	}

	public function getRoutes(): array {
		$bookController = new \Ijdb\Controllers\Book($this->booksTable, $this->usersTable, $this->genresTable,
            $this->bookGenresTable, $this->reviewTable, $this->authentication); // 6/12/18 JG MOD 1L: added $bookGenresTable to manipulate it e.g. delete a child table
		$userController = new \Ijdb\Controllers\Register($this->usersTable);
		$loginController = new \Ijdb\Controllers\Login($this->authentication);
		//$genreController = new \Ijdb\Controllers\Category($this->genresTable); // 6/12/18 JG DEL 1L
        $genreController = new \Ijdb\Controllers\Genre($this->genresTable, $this->bookGenresTable, $this->authentication);  // 6/12/18 JG NEW 1L 2 arguments for deletion the child table
		//UHo new 1l: create review controller to manipulate the reviews
		$reviewController = new \Ijdb\Controllers\Review($this->booksTable, $this->usersTable, $this->reviewTable, $this->authentication);//UHo New 1l: review controller

		$routes = [
			'user/register' => [
				'GET' => [
					'controller' => $userController,
					'action' => 'registrationForm'
				],
				'POST' => [
					'controller' => $userController,
					'action' => 'registerUser'
				]
			],
			'user/success' => [
				'GET' => [
					'controller' => $userController,
					'action' => 'success'
				]
			],
			'user/permissions' => [
				'GET' => [
					'controller' => $userController,
					'action' => 'permissions'
				],
				'POST' => [
					'controller' => $userController,
					'action' => 'savePermissions'
				],
				'login' => true,
				'permissions' => \Ijdb\Entity\User::EDIT_USER_ACCESS  // JG required
			],
			'user/list' => [
				'GET' => [
					'controller' => $userController,
					'action' => 'list'
				],
				'login' => true,
				'permissions' => \Ijdb\Entity\User::EDIT_USER_ACCESS
			],
			'book/edit' => [
				'POST' => [
					'controller' => $bookController,
					'action' => 'saveEdit'
				],
				'GET' => [
					'controller' => $bookController,
					'action' => 'edit'
				],
				'login' => true,
				'permissions'  => \Ijdb\Entity\User::EDIT_BOOKS //UHo new 1l: only for users have permission edit book to edit book
			],
			'book/delete' => [
				'POST' => [
					'controller' => $bookController,
					'action' => 'delete'
				],
				'login' => true,
				'permissions'  => \Ijdb\Entity\User::DELETE_BOOKS //UHo new 1l: only for users have permission delete book to delete book
			],
			'book/list' => [
				'GET' => [
					'controller' => $bookController,
					'action' => 'list'
				],
				//UHo new 3l: POST route for "book genre" form
				'POST' => [
					'controller' => $bookController,
					'action' => 'list'
				]
			],
			//UHo new 4l: route for listing book's reviews
			'book/review' => [
				'GET' => [
					'controller' => $bookController,
					'action' => 'review'
					]
			],
			//UHo new 6l: routes for listing user's reviews
			'review/list' => [
				'GET' => [
					'controller' => $reviewController,
					'action' => 'list'
				],
				'login' => true
			],
			//UHo new 9l: route for edit user's review
			'review/edit'=>[
				'POST' => [
					'controller' => $reviewController,
					'action' => 'saveEdit'
				],
				'GET' => [
					'controller' => $reviewController,
					'action' => 'edit'
				],
				'login' => true,
			],
			//UHo new 8l: route for delete user's reviews
			'review/delete' => [
				'POST' => [
					'controller' => $reviewController,
					'action' => 'delete'
				],
				'login' => true,
				//'permissions' => \Ijdb\Entity\User::DELETE_REVIEWS
			],
			'login/error' => [
				'GET' => [
					'controller' => $loginController,
					'action' => 'error'
				]
			],
			'login/permissionserror' => [
				'GET' => [
					'controller' => $loginController,
					'action' => 'permissionsError'
				]
			],
			'login/success' => [
				'GET' => [
					'controller' => $loginController,
					'action' => 'success'
				]
			],
			'logout' => [
				'GET' => [
					'controller' => $loginController,
					'action' => 'logout'
				]
			],
			'login' => [
				'GET' => [
					'controller' => $loginController,
					'action' => 'loginForm'
				],
				'POST' => [
					'controller' => $loginController,
					'action' => 'processLogin'
				]
			],
			'genre/edit' => [
				'POST' => [
					'controller' => $genreController,
					'action' => 'saveEdit'
				],
				'GET' => [
					'controller' => $genreController,
					'action' => 'edit'
				],
				'login' => true,
				'permissions' => \Ijdb\Entity\User::EDIT_GENRES
			],
			'genre/delete' => [
				'POST' => [
					'controller' => $genreController,
					'action' => 'delete'
				],
				'login' => true,
				'permissions' => \Ijdb\Entity\User::REMOVE_GENRES
			],
			'genre/list' => [
				'GET' => [
					'controller' => $genreController,
					'action' => 'list'
				],
				'login' => true,
				'permissions' => \Ijdb\Entity\User::EDIT_GENRES
			],
			'' => [
				'GET' => [
					'controller' => $bookController,
					'action' => 'home'
				]
				],
			//UHo new 6l: route for about us page
			'aboutus'=>[
				'GET'=>[
					'controller' => $bookController,
					'action' => 'aboutus'
				]
				],
			//UHo new 6l:route for contact us page
			'contactus'=>[
				'GET'=>[
					'controller' => $bookController,
					'action' => 'contactus'
				]
			]
		];

		return $routes;
	}

	public function getAuthentication(): \Ninja\Authentication {   
		return $this->authentication;
	}

	public function checkPermission($permission): bool {  // p.591
		$user = $this->authentication->getUser();

		if ($user && $user->hasPermission($permission)) {
			return true;
		} else {
			return false;
		}
	}

}