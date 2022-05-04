<h2>User List</h2>
<style>
table{margin:auto;}
td{padding-bottom: 25px;
    padding-right: 20px;}
th{text-align: left;}
nav ul li#userList{background-color: #FFF7E4;}
nav ul li#userList a:hover{color: #F6D692;}
table tbody tr td button{margin:auto; width: auto; font-size: auto;}
</style>
<table>
	<thead>
		<th>Name</th>
		<th>Email</th>
		<th>Edit</th>
	</thead>

	<tbody>
		<?php foreach ($users as $user): ?>
		<tr>
			<td><?=$user->username;?></td>		
			<td><?=$user->email;?></td>
			<!-- <t d><a href="/user/permissions?id=<?=$user->id;?>">Edit Permissions</a></td> JG 6/3/18 DEL1L org -->
			<td><button><a href="index.php?user/permissions?id=<?=$user->id;?>">Edit Permissions</a></button></td> <!-- JG 6/3/18 MOD1L -->
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>


