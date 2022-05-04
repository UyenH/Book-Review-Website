<style>
nav ul li#managegenre, nav ul li ul li#genreList{background-color: #FFF7E4;}
nav ul li#genrelist a:hover{color: #F6D692;}
table{margin:auto;}
td{ padding-right: 20px;}
th{text-align: left;}
table tbody tr td button{margin:auto; width: auto; font-size: auto;}
form{margin:auto; overflow: initial;}
input[type="submit"]{margin-bottom: 0;}
@media only screen and (max-width: 600px){
  form{
    overflow: initial;
  }
}
</style>
<h2>Genres</h2>

<table>
<tbody>
<?php if ($user): ?>
  <!--UHo new 3l: if user is logged in and has permission add genre then "Add genre" button will show up-->
  <?php if($user->hasPermission(\Ijdb\Entity\User::ADD_GENRES)): ?>
    <tr><td colspan="3" style="text-align: center;"><button><a href="index.php?genre/edit">Add Genre</a></button></td></tr>
  <?php endif; ?>
<?php foreach($genres as $genre): ?>
  <tr>
    <td>
      <p><?=htmlspecialchars($genre->name, ENT_QUOTES, 'UTF-8')?>
      <!-- <a href="/genre/edit?id=<?=$genre->id?>">Edit</a> JG 6/3/18 org--></p></td>
    <td><button><a href="index.php?genre/edit?id=<?=$genre->id?>">Edit</a></button> <!-- 6/3/18 JG MOD1L --> </td>
 <!--UHo MOD 3l: if user is logged in and has permission remove genre then "delete" button will show up-->
  <?php if($user->hasPermission(\Ijdb\Entity\User::REMOVE_GENRES)): ?>
  <td><form action="index.php?genre/delete" method="post"> <!-- 6/3/18 JG MOD1L -->
    <input type="hidden" name="id" value="<?=$genre->id?>">
    <input type="submit" value="Delete">
  </form> </td>
  <?php endif; ?>
</tr>
<?php endforeach; ?>
<?php endif; ?>
</tbody>
</table>