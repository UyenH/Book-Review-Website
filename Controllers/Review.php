<?php
namespace Ijdb\Controllers;

class Review {
	private $bookTable;
    private $reviewTable; 
	private $userTable;
	private $authentication;
	
	public function __construct(\Ninja\DatabaseTable $bookTable, \Ninja\DatabaseTable $userTable, \Ninja\DatabaseTable $reviewTable, \Ninja\Authentication $authentication) { //6/9/18 JG NEW 1L:
		$this->reviewTable = $reviewTable;
		$this->userTable = $userTable;
		$this->bookTable = $bookTable; 
		$this->authentication = $authentication;
	}
	//UHo new 21l: get all the reviews that has the same user, and list them in your review page
	public function list(){
		$user = $this->authentication->getUser();
		$page = $_GET['page'] ?? 1;

		$offset = ($page-1)*5;
        //UHo new 1l: count the total number of reviews that has the same userId
		$totalReviews = $this->reviewTable->total('userId', $user->id);	
		$reviews = $user->getReviews($offset);

		$title = 'Your Reviews';

		return ['template' => 'userreview.html.php', 
				'title' => $title, 
				'variables' => [
						'totalReviews' => $totalReviews,
						'reviews' => $reviews,
						'User' => $user,
						'currentPage' => $page,
		
					]
				];
	}
	//UHo new 9l: save the review
	public function saveEdit() {
		$user = $this->authentication->getUser();
		$review = $_POST['review'];
		$review['reviewdate'] = new \DateTime();

		$user->addReview($review);
		//UHo new 1l: go to the specific book's review page
		header('location: index.php?book/review?bookId='.$review['bookId']);   	

	}
	//UHo new 20l: edit the review
	public function edit() {
		$user = $this->authentication->getUser();

		if (isset($_GET['id'])) {
			$review = $this->reviewTable->findById($_GET['id']);
			$book = $review->getBooks();
		}
		if (isset($_GET['bookId'])){
			$book = $this->bookTable->findById($_GET['bookId']);
		}
		$title = 'Edit Review';

		return ['template' => 'editreview.html.php',
				'title' => $title,
				'variables' => [
						'review' => $review ?? null,
						'User' => $user,
						'book' => $book
					]
				];
	}
	//UHo new 20l: delete the review
	public function delete() {

		$user = $this->authentication->getUser();

		$review = $this->reviewTable->findById($_POST['id']);
		if($review->bookId != null){
			$bookId = $review->bookId;
		}
		else{
			$bookId = null;
		}
		if ($review->userId != $user->id && !$user->hasPermission(\Ijdb\Entity\User::DELETE_BOOKS) ) {
			return;
		}
		
		$this->reviewTable->delete($_POST['id']);   // 6/13/18 JG delete a row at the parent table
		
        //header('location: /book/list'); 5/25/18 JG DEL 1L:  org
		//UHo new 2l: if book is deleted then go to your review page
		if($bookId == null){
			header('location: index.php?review/list');
		}
		//UHo new 2l: if book is not deleted
		else{
			header('location: index.php?book/review?bookId=' . $bookId);
		}	

	}
}