<style>
nav ul li#yourreview a:hover{color: #F6D692;}
nav ul li#yourreview{background-color: #FFF7E4;}
/*img{display: inline; float: left; margin: 15px; }*/
.book{margin-left: 30px; display: block; contain: content;}
form{margin-left: 10px;}
label{width: 7em;}
input[type="submit"]{margin-left: 20px;}
textarea{    width: 743px;
    height: 109px;}
#addreviewbutton{margin: 10px auto;
    display: block;
    width: auto;
    }
table{margin-left: 3em;}
h4{ margin-left: 3em;}
@media only screen and (max-width: 600px) {
table{margin: auto;}
table tbody tr {
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
        grid-column: 1;
      }
      .three{
        grid-row: 2;
    grid-column: 1/0;
      }
}
</style>
<h2 style="text-align: center;"><?= $User->username?>'s Review List</h2>
<h4>Total reviews: <?= $totalReviews ?></h4>
<table>
<?php foreach($reviews as $review): ?>
  <tr>
  <td class="one"><h3>
  <?php if ($review->getBooks()->title != null): ?>
      <?=(new \Ninja\Markdown($review->getBooks()->title))->toHtml()?>
  <?php else: ?>
        This book is deleted
 <?php endif; ?>
    </h3>
  <div><?=(new \Ninja\Markdown($review->reviewtext))->toHtml()?></div></td>
  
  <?php if($User): ?>
    <td class="two">
      <?php if ($User->id == $review->userId || $User->hasPermission(\Ijdb\Entity\User::EDIT_REVIEWS)): ?>
      <button><a href="index.php?review/edit?id=<?=$review->id?>">Edit</a></button><!--  6/3/18 JG MOD1L -->
      <?php endif; ?>
  </td>
   <td class="three">
      <?php if ($User->id == $review->userId || $User->hasPermission(\Ijdb\Entity\User::DELETE_REVIEWS)): ?>
      <form action="index.php?review/delete" method="post"> 
        <input type="hidden" name="id" value="<?=$review->id?>">
        <input type="submit" value="Delete">
       </form>
      <?php endif; ?>
    </td></tr>
  <?php endif; ?>
<?php endforeach; ?>
</table>

<?php echo $totalReviews ?'Select page:': ''?> <!--  6/8/18 JG NEW 1L Improve: don't display 'Select page' if no books -->

<?php
   // 7/6/18 JG display page numbers e.g. [1] 2 3
$numPages = ceil($totalReviews/5);

for ($i = 1; $i <= $numPages; $i++):
  if ($i == $currentPage):
?>
  <!-- <a class="currentpage" href="/book/list?page=<//?=$i?><//?=!empty($genreId) ? '&genre=' . $genreId : '' ?>"><//?=$i?></a> JG 6/3/18 org-->
  <a class="currentpage" href="index.php?review/list?page=<?=$i?>"><?=$i?></a> <!--  6/3/18 JG MOD1L -->
<?php else: ?>
  <!-- <a href="/book/list?page=<//?=$i?><//?=!empty($genreId) ? '&genre=' . $genreId : '' ?>"><//?=$i?></a> JG 6/3/18 org-->
  <a href="index.php?review/list?page=<?=$i?>"><?=$i?></a> <!--  6/3/18 JG MOD1L -->
<?php endif; ?>
<?php endfor; ?>