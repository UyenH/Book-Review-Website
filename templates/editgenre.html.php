<style>
	/*UHo new 1l: when user is on this page then the nav bar color background will be changed to let them know which page they are on*/
	nav ul li#managegenre, nav ul li ul li#genreList{background-color: #FFF7E4;}
	nav ul li#genreList a:hover{color: #F6D692;}
</style>
<form action="" method="post">
	<input type="hidden" name="genre[id]" value="<?=$genre->id ?? ''?>">
	<label for="genrename">Enter genre name:</label>
	<input type="text" id="genrename" name="genre[name]" value="<?=$genre->name ?? ''?>" />
	<input type="submit" name="submit" value="Save">
</form>
