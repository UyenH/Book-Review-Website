<?php
namespace Ijdb\Entity;
use Ninja\DatabaseTable;

class Review {
	private $usersTable;
	//UHo new 8l: review's attribute
	public $id;
	public $reviewtitle;
	public $reviewtext;
	public $reviewdate;
	public $user;
	public $userId;
	public $bookId;
	public $book;
	public function __construct(DatabaseTable $usersTable, 
	DatabaseTable $bookTable) {
		$this->bookTable = $bookTable;
		$this->usersTable = $usersTable;
	}
	//UHo new 6l: get user function: find user who wrote this review
	public function getUser() {
		if (empty($this->user)) {
			$this->user = $this->usersTable->findById($this->userId);
		}
		return $this->user;
	}
	//UHo new 6l: get book function: find book that the review belong to
	public function getBooks() {
		if (empty($this->book)) {
			$this->book = $this->bookTable->findById($this->bookId);
		}
		return $this->book;
	}
}