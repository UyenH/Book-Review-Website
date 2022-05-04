<?php
namespace Ijdb\Controllers;
use \Ninja\DatabaseTable;
use \Ninja\Authentication;

class Book {
	private $usersTable;
	private $booksTable;
	private $genresTable;
	private $bookGenresTable;
	private $reviewTable;
	private $authentication;

	public function __construct(DatabaseTable $booksTable, DatabaseTable $usersTable, DatabaseTable $genresTable, 
	     DatabaseTable $bookGenresTable, DatabaseTable $reviewTable, Authentication $authentication) { //6/12/18 JG MOD 1L: added $bookGenres table to manipulate it e.g. delete a child table
    	$this->booksTable = $booksTable;
		$this->usersTable = $usersTable;
		$this->genresTable = $genresTable;
		$this->bookGenresTable = $bookGenresTable; //6/12/18 JG NEW 1L: in order to use $this->bookGenresTable->deleteWhere()
		$this->authentication = $authentication;
		//UHo ADD 1l: add the review table
		$this->reviewTable = $reviewTable;
	}

	public function list() {

		$page = $_GET['page'] ?? 1;

		$offset = ($page-1)*10;

		if (isset($_GET['genre'])) {
			$genre = $this->genresTable->findById($_GET['genre']);
			$books = $genre->getBooks(10, $offset);
			$totalBooks = $genre->getNumBooks();
		}
		//UHO ADD 30l: if genre form is submitted and send through $_POST
		if (isset($_POST['genre'])) {
			$genres = $_POST['genre'];
			//UHo new 3l: Get books for each genre
			foreach($genres as $genre){
				$Genre[$genre] = $this->genresTable->findById($genre);
			    $book[$genre] = $Genre[$genre]->getBooks(10, $offset);
			}
			//UHo new 5l: get all books' ids from all the genres and store them in an array
			$booksId = [];
			foreach($book as $genre){
				foreach($genre as $bookID){
					array_push($booksId,$bookID->id);
				}
			}
			//UHo new 1l: count the values(book id) in array booksId
			$booksId = array_count_values($booksId);
			$books = [];
			foreach($booksId as $book => $numBook){
				if($numBook >= count($genres)){ //if the id value equal to number of genres then this/these books has all the required genre
					$books[] = $this->booksTable->findById($book);
				}
			}
			//UHo new 2l: if the $books array is empty then set $totalBooks to 0
			if(empty($books)){
				$totalBooks = "0";
			}
			//UHo new 2l: or else $totalBooks will be equal number of elements in $books array
			else{
				$totalBooks = count($books);
			}
		}
		else {
			$books = $this->booksTable->findAll('publicationdate DESC', 10, $offset);
			$totalBooks = $this->booksTable->total();
		}		

		$title = 'Book list';

		$user = $this->authentication->getUser();

		return ['template' => 'books.html.php', 
				'title' => $title, 
				'variables' => [
						'totalBooks' => $totalBooks,
						'books' => $books,
						'User' => $user,
						'genres' => $this->genresTable->findAll(),
						'currentPage' => $page,
						'genreId' => $_GET['genre'] ?? null
					]
				];
	}
	//UHo new 26l: list all reviews that has the same bookId
	public function review(){
		$page = $_GET['page'] ?? 1;

		$offset = ($page-1)*5;
	
		if (isset($_GET['bookId'])) {
			$book = $this->booksTable->findById($_GET['bookId']);
			//UHo new 1l: get all the reviews for this book
			$reviews = $book->getReviews($offset);
			//UHo new 1l: get total number of reviews
			$totalReviews = $book->getNumReviews();
		}
		$title = 'Review ';
		$user = $this->authentication->getUser();
		return ['template' => 'reviews.html.php', 
				'title' => $title, 
				'variables' => [
					'reviews' => $reviews,
					'totalReviews' => $totalReviews,
						'currentPage' => $page,
						'User' => $user,
						'book'=> $book,
						'bookId' => $_GET['bookId'] ?? null
					]
				];
	}
	public function home() {
		$title = 'Book Review';
		
		return ['template' => 'home.html.php', 'title' => $title];
	}
	//UHo new 3l: about us page
    public function aboutus(){
		$title = 'About Us';
		
		return ['template' => 'aboutus.html.php', 'title' => $title];
	}
	//UHo new 3l: contact us page
	public function contactus(){
		$title = 'Contact Us';
		
		return ['template' => 'contactus.html.php', 'title' => $title];
	}
	//delete book
	public function delete() {

		$user = $this->authentication->getUser();

		$book = $this->booksTable->findById($_POST['id']);
        //UHo new 1l: find all the reviews that has the same bookId
		$review = $this->reviewTable->find('bookId', $_POST['id']);

		if ($book->userId != $user->id && !$user->hasPermission(\Ijdb\Entity\User::DELETE_BOOKS) ) {
			return;
		}
		//UHo new 3l: Looping through each reviews and update the bookId
		foreach($review as $r){
			$this->reviewTable->updateCol('bookId',null, $r->id);
		}
        $this->bookGenresTable->deleteWhere('bookid', $_POST['id']); 
		$this->booksTable->delete($_POST['id']);   
		
        header('location: index.php?book/list');  	

	}

	public function saveEdit() {
		$user = $this->authentication->getUser();
		$book = $_POST['book'];
		//$image = $_FILES['book']['tmp_name'];
		//UHo new 3l: image was sent through $_FILES, if its tmp_name was not empty then get the image
		if($_FILES['book']['tmp_name'] != ''){
			$book['img'] = file_get_contents($_FILES['book']['tmp_name']);
		}
		//UHo mod 1l: change jokedate to publicationdate
		$book['publicationdate'] = new \DateTime($book['publicationdate']);

		$bookEntity = $user->addBook($book);

		$bookEntity->clearGenres();

		foreach ($_POST['genre'] as $genreId) {
			$bookEntity->addgenre($genreId);
		}
		//header('location: /book/list'); 5/25/18 JG DEL 1L:  org
		header('location: index.php?book/list');  //5/25/18 JG NEW 1L  	

	}

	public function edit() {
		$user = $this->authentication->getUser();
		$genres = $this->genresTable->findAll();

		if (isset($_GET['id'])) {
			$book = $this->booksTable->findById($_GET['id']);
		}

		$title = 'Edit book';

		return ['template' => 'editbook.html.php',
				'title' => $title,
				'variables' => [
						'book' => $book ?? null,
						'User' => $user,
						'genres' => $genres
					]
				];
	}
	
}