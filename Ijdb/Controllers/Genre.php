<?php
namespace Ijdb\Controllers;
use \Ninja\DatabaseTable;
use \Ninja\Authentication;

class Genre {
	private $genreTable;
    private $bookGenreTable; 
	private $userTable;

	public function __construct(DatabaseTable $genreTable, DatabaseTable $bookgenreTable, Authentication $authentication) { //6/9/18 JG NEW 1L:
		$this->genreTable = $genreTable;
		$this->authentication = $authentication;
		$this->bookGenreTable = $bookgenreTable; 
	}

	public function edit() {

		if (isset($_GET['id'])) {
			$genre = $this->genreTable->findById($_GET['id']);
		}

		$title = 'Edit Genre';

		return ['template' => 'editgenre.html.php',
				'title' => $title,
				'variables' => [
					'genre' => $genre ?? null
				]
		];
	}

	public function saveEdit() {
		$genre = $_POST['genre'];

		$this->genreTable->save($genre);

		// header('location: /genre/list'); 6/7/18 JG DEL 1L: org
		header('location: index.php?genre/list');  // 6/7/18 JG NEW 1L: 
	}

	public function list() {
		$genres = $this->genreTable->findAll();
		$user = $this->authentication->getUser();
		$title = 'book genre';

		return ['template' => 'genre.html.php', 
			'title' => $title, 
			'variables' => [
			    'genres' => $genres,
				'user' => $user
			  ]
		];
	}
	
	
	public function delete() {
		$this->bookGenreTable->delete($_POST['id']); // 6/9/18 JG NEW 1L: first DELETE all rows with $_POST['id'] related to a child table
		$this->genreTable->delete($_POST['id']);  // 6/9/18 JG Delete a row at the parent table

		// header('location: /genre/list'); 6/7/18 JG DEL 1L: org
		header('location: index.php?genre/list');  // 6/7/18 JG NEW 1L: 
	}
}