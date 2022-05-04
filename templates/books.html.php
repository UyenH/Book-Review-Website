<style>
img{display: inline-block;}
nav ul li#managebook, nav li#booklist{background-color: #FFF7E4;}
nav ul li#booklist a:hover{color: #F6D692;}
button#addbookbut{contain: content;
    margin: auto;
    width: max-content;
    display: flex;}
form{margin-left: 10%; overflow: unset;}
label{width: auto;}
#formBook{margin-left: 15px; width: 30%;}
h3{text-align: center;}
/*UHo new 34l: layout for mobile display*/
@media only screen and (max-width: 600px){
      /*button#addbookbut{margin-left: 20%;}*/
      #formBook{
        width: 100%;
      }
      label{float: left;}
      form{
        margin: auto;
       width: 100%;
       margin-left: 30%;
      }
      table tbody tr{
        display: grid;
        justify-items: center;
        justify-content: start;
      }
      .one{
        grid-row: 1;
        grid-column: 1/3;
      }
      .two{
        grid-row: 2;
        grid-column: 1/3;
      }
      .three{
        grid-row: 3;
    grid-column: 1;
      }
      .four{
        grid-row: 3;
    grid-column: 2;
      }
    table{padding: 0; width: -webkit-fill-available;}
    }
</style>
<div class="booklist">
  <div id="formBook">
    <p style="text-align: center;">Book Genre</p>
    <em>If you select multiple genres you can find book/books with all those genre</em>
    <p></p>
    <!--UHo NEW 7l: select genre form-->
      <form action="" method="POST">
        <!--UHo NEW 4l: foreach loop to looping through $genres-->
        <?php foreach($genres as $genre): ?>
          <!--UHo NEW 2l: display label and input checkbox for each genre-->
          <input type="checkbox" id="genre" name="genre[<?=$genre->id?>]" value="<?=$genre->id?>">
          <label for="genreId"><?=$genre->name?></label>
        <?php endforeach; ?>
        <input type="submit" value="Find Book">
      </form>
  </div>

  <div class="books">
    <p><?=$totalBooks?> books</p>
      <?php if ($User): ?>
        <!--UHo NEW 1l: if user is logged in and has permission edit book then "add book" button will show up-->
        <?php if($User->hasPermission(\Ijdb\Entity\User::EDIT_BOOKS)):?>
          <a href="index.php?book/edit"><button id="addbookbut">Add Book</button></a>
        <?php endif; ?>
      <?php endif; ?>
    <table>
      <!--UHo NEW 10l: php foreach loop to display EACH book's image, title, author, publication date-->
      <?php foreach($books as $book): ?>
        <tr>
          <td class="one">
            <!--UHo NEW 10l: display book's image-->
            <img src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($book->img); ?>" alt="<?=$book->title ?? ''?>" /> 
          </td>
          <td class="two"><h3><a href="index.php?book/review?bookId=<?=$book->id?>"><?=$book->title?></a></h3>
            <!--UHo NEW 10l: display book's author and publication date-->
            <p>Author: <?= $book->author?></p>
            <p>Publication date: <?php $date = new DateTime($book->publicationdate); echo $date->format('jS F Y');?></p>
          </td>
        <?php if ($User): ?>
          <td class="three">
            <!--UHo NEW 3l: if user is logged in and has permission edit book then display "edit" button-->
            <?php if($User->hasPermission(\Ijdb\Entity\User::EDIT_BOOKS)):?>
              <a href="index.php?book/edit?id=<?=$book->id?>"><button>Edit</button></a>
              <?php endif; ?>
          </td>
          
          <td class="four"> 
            <!--UHo NEW 5l: if user is logged in and has permission delete book then display "delete" button-->
            <?php if($User->hasPermission(\Ijdb\Entity\User::DELETE_BOOKS)): ?>
              <form action="index.php?book/delete" method="post">
                <input type="hidden" name="id" value="<?=$book->id?>">
                <input type="submit" value="Delete">
              </form>
            <?php endif; ?>
          </td>
        </tr>
        <?php endif; ?>
      <?php endforeach; ?>
    </table>
  </div>
</div>

<?php echo $totalBooks ?'Select page:': ''?> 
<?php
  $numPages = ceil($totalBooks/10);
  for ($i = 1; $i <= $numPages; $i++):
    if ($i == $currentPage):
?>
    <a class="currentpage" href="index.php?book/list?page=<?=$i?><?=!empty($genreId) ? '&genre=' . $genreId : '' ?>"><?=$i?></a> 
<?php else: ?>
  <a href="index.php?book/list?page=<?=$i?><?=!empty($genreId) ? '&genre=' . $genreId : '' ?>"><?=$i?></a> 
<?php endif; ?>
<?php endfor; ?>

