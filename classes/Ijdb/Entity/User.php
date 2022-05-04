<?php
namespace Ijdb\Entity;

class User {

	const EDIT_BOOKS = 1;
	const DELETE_BOOKS = 2;
	const ADD_GENRES = 4;
	const EDIT_GENRES = 8;
	const REMOVE_GENRES = 16;
	const EDIT_USER_ACCESS = 32;
	//UHo new 2l: permission value for edit and delete reviews
    const EDIT_REVIEWS = 64;
	const DELETE_REVIEWS = 128;
	public $id;
	public $username;
	public $email;
	public $password;
	
	private $booksTable;
	private $reviewTable;

	public function __construct(\Ninja\DatabaseTable $bookTable, \Ninja\DatabaseTable $reviewTable) {
		$this->booksTable = $bookTable;
		$this->reviewTable = $reviewTable;
	}

	public function getBooks() {
		return $this->booksTable->find('userId', $this->id);
	}
	//UHo new 2l: get the reviews that has the same user ID
	public function getReviews($offset){
		return $this->reviewTable->find('userId', $this->id, "reviewdate DESC", 10, $offset);
	}

	public function addBook($book) {
		$book['userId'] = $this->id;

		return $this->booksTable->save($book);
	}
	//UHo new 3l: add the review to review table
	public function addReview($review) {
		$review['userId'] = $this->id;
		return $this->reviewTable->save($review);
	}

	public function hasPermission($permission) {
		return $this->permissions & $permission;  
	}
}