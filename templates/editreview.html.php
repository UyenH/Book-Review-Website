<style>
img{width: 15em;
    float: left;
    margin-left: 5em;}
label{width: 8em;}
form{margin-left: 5em; margin-top:10em;}
textarea{width: 664px;
    height: 416px;}
</style>
<img src="data:image/jpg;charset=utf8;base64,<?php echo base64_encode($book->img); ?>" width="200px" />
<h2><?= (new \Ninja\Markdown($book->title))->toHtml()?></h2>

<form id="addreview" action="" method="POST">  
    <input type="hidden" name="review[id]" value="<?=$review->id ?? ''?>">
    <input type="hidden" name="review[bookId]" value="<?=$book->id ?? $review->bookId?>">
    <label for="reviewtitle">Review title:</label>
    <input type="text" id='reviewtitle' name="review[reviewtitle]" value=<?=$review->reviewtitle ?? ''?>>
    <label for="reviewtext">Review Text:</label>
    <textarea id="reviewtext" name="review[reviewtext]" rows="3" cols="40"><?=$review->reviewtext?? ''?></textarea>
    <input type="submit" name="submit" value="Save">
</form>