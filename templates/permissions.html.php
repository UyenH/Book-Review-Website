<style>
	nav ul li#userList{background-color: #FFF7E4;}
</style>
<h2>Edit <?=$User->username?>'s Permissions</h2>

<form action="" method="post">
	
	<?php foreach ($permissions as $name => $value): ?>
	<div>
		<input name="permissions[]" type="checkbox" value="<?=$value?>" <?php if ($User->hasPermission($value)) echo 'checked'; ?> />
		<label><?=ucwords(strtolower(str_replace('_', ' ', $name)))?>
	</div>
	<?php endforeach; ?>

	<input type="submit" value="Submit" />
</form>