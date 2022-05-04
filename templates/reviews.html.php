<style>
img#bookimg{display: inline; float: left; margin: 15px; }
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
.content{
    height: 100px;
    overflow: hidden;
  }
.content.showContent{
      height: auto;
  }
td{margin-bottom: 10px; padding-bottom: 20px;}
p{margin-top:10px;}
@media only screen and (max-width: 600px) {
img{float: none;}
table{margin: auto;}
.book{margin: auto;}
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
<div class="book">
<h2><?=(new \Ninja\Markdown($book->title))->toHtml()?></h2>
<img id="bookimg" src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($book->img); ?>" width="200px" />
<div><?=(new \Ninja\Markdown($book->description))->toHtml()?></div>
<p>ISBN: <?=$book->ISBN?></p>
<p>Publication date: <?php
$date = new DateTime($book->publicationdate);
echo $date->format('jS F Y');?></p>
</div>

<button id="addreviewbutton"><a href="index.php?review/edit?bookId=<?=$book->id?>">Write a review</a></button>
<h3 style="text-align: center;">Reviews</h3>
<h4>Total reviews: <?= $totalReviews ?></h4>
<table>
<?php foreach($reviews as $review): ?>
  <tr>
  <td class="one"><h4>User: <?= $review->getUser()->username ?></h4>
  <div style="margin-top: 10px;">Review title: <?=$review->reviewtitle?> </div>
  <div class="content"><?=(new \Ninja\Markdown($review->reviewtext))->toHtml()?>
  </div>
  <a href="javascript:void(0);" class="readbtn">Read More</a><br>
  </td>
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
  <a class="currentpage" href="index.php?book/review?bookId=<?=$book->id?>?page=<?=$i?>"><?=$i?></a> <!--  6/3/18 JG MOD1L -->
<?php else: ?>
  <!-- <a href="/book/list?page=<//?=$i?><//?=!empty($genreId) ? '&genre=' . $genreId : '' ?>"><//?=$i?></a> JG 6/3/18 org-->
  <a href="index.php?book/review?bookId=<?=$book->id?>?page=<?=$i?>"><?=$i?></a> <!--  6/3/18 JG MOD1L -->
<?php endif; ?>
<?php endfor; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
  $(".readbtn").on('click', function(){
    $(".content").toggleClass("showContent");

    //Shorthand if-else statement
    var replaceText = $(".content").hasClass("showContent") ? "Read less" : "Read more";
    $(this).text(replaceText);
  });
</script>