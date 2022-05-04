<style>
form{width: 500px;
    margin: 0 auto;}
nav ul li#managebook, nav ul li ul li#addBook{background-color: #FFF7E4;}
nav ul li#addBook a:hover{color: #F6D692;}
img{width: 100px;display: block;
    margin-left: 15em;
    margin-bottom: 15px;
}
/*UHo new 5l: css for mobile display*/
@media only screen and (max-width: 600px) {
    form{
        width: auto;
    }
    img{margin: 0; float: left;}
}
</style>
<?php if($User): ?>
<!--UHo MOD 28l: if user is logged in and has permission edit book then the form will show up-->
<?php if ($User->hasPermission(\Ijdb\Entity\User::EDIT_BOOKS)): ?>
<form action="" method="post" enctype="multipart/form-data" > <!--enctype="multipart/form-data"-->
	<input type="hidden" name="book[id]" value="<?=$book->id ?? ''?>">
    <label for="booktitle">Book title:</label>
    <input type="text" id='booktitle' name="book[title]" value="<?=$book->title ?? ''?>">
    <label for="bookimg">Book image:</label>
    <input type="file" id="bookimg" name="book">
    <img src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($book->img); ?>" alt="<?=$book->title ?? ''?>"/>
    <label for="booktext">Book Description:</label>
    <textarea id="booktext" name="book[description]" rows="3" cols="40"><?=$book->description ?? ''?></textarea>
    <label for="bookauthor">Book author:</label>
    <input type="text" id='booktitle' name="book[author]" value="<?=$book->author?? ''?>">
    <label for="bookISBN">Book ISBN:</label>
    <input type="text" id='ISBN' name="book[ISBN]" value="<?=$book->ISBN?? ''?>">
    <label for="publication">Book publication date:</label>
    <input type="date" id="publicationdate" name="book[publicationdate]" value=<?=$book->publicationdate?? ''?>>
    <p>Select genres for this book:</p>
    <!--genre checkbox form-->
    <?php foreach ($genres as $genre): ?>
    <?php if ($book && $book->hasgenre($genre->id)): ?>
    <input type="checkbox" checked name="genre[]" value="<?=$genre->id?>" /> 
    <?php else: ?>
    <input type="checkbox" name="genre[]" value="<?=$genre->id?>" /> 
    <?php endif; ?>
    <label><?=$genre->name?></label>
    <?php endforeach; ?>

    <input type="submit" name="submit" value="Save">
</form>
<?php else: ?>
<p>You don't have permission to add/edit book.</p>
<?php endif; ?>
<?php endif; ?>