<?php
namespace Ijdb\Entity;

use Ninja\DatabaseTable;

class Genre {
	public $id;
	public $name;
	private $booksTable;
	private $bookGenresTable;

	public function __construct(DatabaseTable $booksTable, DatabaseTable $bookGenresTable) {
		$this->booksTable = $booksTable;
		$this->bookGenresTable = $bookGenresTable;
	}

	public function getBooks($limit = null, $offset = null) {
		$bookGenres = $this->bookGenresTable->find('genreId', $this->id, null, $limit, $offset);
		
		$books = [];

		foreach ($bookGenres as $bookGenre) {
			$book =  $this->booksTable->findById($bookGenre->bookId);
			
			if ($book) {
				$books[] = $book;
			}			
		}

		usort($books, [$this, 'sortBooks']);

		return $books;
	}

	public function getNumBooks() {
		return $this->bookGenresTable->total('genreId', $this->id);
	}

	private function sortBooks($a, $b) {
		$aDate = new \DateTime($a->bookdate);
		$bDate = new \DateTime($b->bookdate);

		if ($aDate->getTimestamp() == $bDate->getTimestamp()) {
			return 0;
		}

		return $aDate->getTimestamp() < $bDate->getTimestamp() ? -1 : 1;
	}
}