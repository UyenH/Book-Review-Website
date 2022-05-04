<!doctype html>
<style>
	nav ul li#about{border-left: 2px solid black;}
	@media only screen and (max-width: 600px){
		nav ul li#about{border-top: 2px solid black; border-left: none;}
	}
</style>
<html>
	<head>
		<meta charset="utf-8">
		<!-- 5/25/18 JG DEL1L: <link rel="stylesheet" href="/books.css"> -->
		<link rel="stylesheet" href="bookreview.css"> <!--5/25/18 JG NEW1L: otherwise it will not display layout -->
		<title><?=$title?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
	</head>
	<body>
		<header>
			
		<img src="../public/logo.png" alt="Book Review Logo">
			<h1>Book Review</h1></div>
			<div class='button'>
			<?php if ($loggedIn): ?>   
				<button><a href="index.php?logout">Log out</a></button>
			<?php else: ?>
				<button><a href="index.php?user/register">Register Account</a></button>
				<button><a href="index.php?login">Log in</a></button>
			<?php endif; ?>
			
		</header>
		<nav>
		<ul>
		<!--5/25/18 JG NEW8L: replacement adapter -->
	            <li id="home"><a href="index.php">Home</a></li>	  
				<?php if ($loggedIn): ?> 
					<!--UHo NEW 1l: if user is logged in "your reviews" nav bar will show-->
					<li id="yourreview"><a href="index.php?review/list">Your reviews</a></li>
					<!--UHo NEW 5l: if user is logged in and has permission edit book or delete book then "manage book" dropdown will show up-->
					<?php if ($user->hasPermission(\Ijdb\Entity\User::EDIT_BOOKS)):?>
						<li id="managebook">Manage Book <span>&#8675;</span>
						<ul id="dropdown">
						<li id="booklist"><a href="index.php?book/list">Book List</a></li>
						<!--UHo NEW 3l: if user is logged in and has permission edit book then "add book" nav will show up-->
						<li id="addBook"><a href="index.php?book/edit">Add Book</a></li></ul></li>
					<?php else: ?><!--UHo NEW 5l: if user is logged in and doesn't have permission edit book or delete book then "book list" will show up-->
					<li id="booklist"><a href="index.php?book/list">Book List</a></li>
					<?php endif; ?>
					<!--UHo NEW 1l: if user is logged in and has permission edit genre then genre list will show up-->
					<?php if ($user->hasPermission(\Ijdb\Entity\User::EDIT_GENRES) || $user->hasPermission(\Ijdb\Entity\User::ADD_GENRES)):?>
					<li id="managegenre">Manage genre <span>&#8675;</span>
					<ul id="dropdown">
					<li id="genreList"><a href="index.php?genre/list">Genre List</a>
					<?php if ($user->hasPermission(\Ijdb\Entity\User::ADD_GENRES)):?>
					<li><a href="index.php?genre/edit">Add Genre</a></li>
					<?php endif; ?>
					</ul></li>
					<?php endif; ?>
					<!--UHo NEW 1l: if user is logged in and has permission edit user access then user list will show up-->
					<?php if ($user->hasPermission(\Ijdb\Entity\User::EDIT_USER_ACCESS)):?>
					<li id="userList">
					<a href="index.php?user/list">Users List</a></li>
					<?php endif; ?></li>
				<?php endif; ?>
				<?php if (!$loggedIn): ?> 
					<li id="booklist"><a href="index.php?book/list">Book List</a></li>
				<?php endif; ?>
				<li id="about"><a href="index.php?aboutus">About Us</a></li>
				<li id="contact"><a href="index.php?contactus">Contact Us</a></li></ul>
		</ul>
	</nav>

	<main>
	<?=$output?>
	</main>

	<footer>
	&copy; Book Review 2021
	<!--<a href="index.php">Home</a> 
    <a href="index.php?book/list">Book List</a>
	<a href="index.php?aboutus">About Us</a>
	<a href="index.php?contactus">Contact Us</a>
	<a href="index.php?user/register">Register Account</a>
			<?php if ($loggedIn): ?>   
				<a href="index.php?logout">Log out</a>
			<?php else: ?>
				<a href="index.php?login">Log in</a>
			<?php endif; ?>-->
			<a href="index.php">Home</a> 
				<?php if ($loggedIn): ?> 
					<!--UHo NEW 1l: if user is logged in and has permission edit book then 'add book' page will show up-->
					<a href="index.php?review/list">Your reviews</a>
					<!--UHo NEW 6l: if user is logged in and has permission edit book or delete book then "book list" text base nav will show up-->
					<?php if ($user->hasPermission(\Ijdb\Entity\User::EDIT_BOOKS) || $user->hasPermission(\Ijdb\Entity\User::DELETE_BOOKS)):?>
						<a href="index.php?book/list">Book List</a>
						<!--UHo NEW 3l: if user is logged in and has permission edit book then "add book" nav will show up-->
						<?php if ($user->hasPermission(\Ijdb\Entity\User::EDIT_BOOKS)): ?>
                			<a href="index.php?book/edit">Add Book</a>
						<?php endif; ?>
					<?php else: ?><!--UHo NEW 1l: if user is logged in and doesn't have permission edit book or delete book then "book list" will show up-->
						<a href="index.php?book/list">Book List</a>
					<?php endif; ?>
					<!--UHo NEW 1l: if user is logged in and has permission edit genre then genre list will show up-->
					<?php if ($user->hasPermission(\Ijdb\Entity\User::EDIT_GENRES) || $user->hasPermission(\Ijdb\Entity\User::ADD_GENRES)):?>
					<a href="index.php?genre/list">Genre List</a>
					<?php if ($user->hasPermission(\Ijdb\Entity\User::ADD_GENRES)):?>
					<a href="index.php?genre/edit">Add Genre</a>
					<?php endif; ?>
					<?php endif; ?>
					<!--UHo NEW 1l: if user is logged in and has permission edit user access then user list will show up-->
					<?php if ($user->hasPermission(\Ijdb\Entity\User::EDIT_USER_ACCESS)):?>
					<a href="index.php?user/list">Users List</a>
					<?php endif; ?>
				<?php endif; ?>
				<?php if (!$loggedIn): ?> 
					<a href="index.php?book/list">Book List</a>
				<?php endif; ?>
				<a href="index.php?aboutus">About Us</a>
				<a href="index.php?contactus">Contact Us</a>
	</footer>
	</body>
</html>