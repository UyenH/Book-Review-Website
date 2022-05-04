<?php
namespace Ijdb\Entity;

class Book {
	public $id;
	public $userId;
	public $publicationdate;
	public $description;
	public $img;
	public $ISBN;
	//public $author;
	private $usersTable;
	private $user;
	private $bookGenresTable;
	private $reviewTable;
	private $review;

	public function __construct(\Ninja\DatabaseTable $usersTable, 
		\Ninja\DatabaseTable $bookGenresTable, \Ninja\DatabaseTable $reviewTable) {
		$this->usersTable = $usersTable;
		$this->bookGenresTable = $bookGenresTable;
		$this->reviewTable = $reviewTable;
	}
	//UHo DEL 5l: delete function getUser()
	/*public function getUser() {
		if (empty($this->user)) {
			$this->user = $this->usersTable->findById($this->userId);
		}
		return $this->user;
	}*/
	//UHo new 2l: get reviews function: find all the review in the review table by bookId 
	public function getReviews($offset){
		return $this->reviewTable->find('bookId', $this->id, "reviewdate DESC", 10, $offset);
	}
	//UHo new 2l: count the total number of reviews in review table by bookId
	public function getNumReviews(){
		return $this->reviewTable->total('bookId', $this->id);
	}
	public function addGenre($genreId) {
		$bookCat = ['bookId' => $this->id, 'genreId' => $genreId];

		$this->bookGenresTable->save($bookCat);
	}
	

	public function hasGenre($genreId) {
		$bookGenres = $this->bookGenresTable->find('bookId', $this->id);

		foreach ($bookGenres as $bookGenre) {
			if ($bookGenre->genreId == $genreId) {
				return true;
			}
		}
	}

	public function clearGenres() {
		$this->bookGenresTable->deleteWhere('bookId', $this->id);
	}
}