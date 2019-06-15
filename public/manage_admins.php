<?php include("../includes/session.php"); ?>
<?php include("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php confirm_logged_in(); ?>

<?php $layout_context = "admin"; ?>
<?php include("../includes/layouts/header.php"); ?>

<div id="main">
	<div id="navigation">
		<br>
		<a href="admin.php">&laquo; Main Menu</a><br>
	</div>
	<div id="page">
	<?php echo message(); ?>
	<?php $errors = errors(); ?>
	<?php echo form_errors($errors); ?>
		<h2>Manage Admins</h2>
		<table>
			<thead>
				<tr>
					<th><b>Username</b></th>
					<th><b>Actions</b></th>
				</tr>
			</thead>
			<tbody>
				<?php 
					$admins_set = find_all_admins();
					while($admin = mysqli_fetch_assoc($admins_set)) {
						?>
						<tr>
							<td><?php echo htmlentities($admin['username']); ?></td>
							<td><a href="edit_admin.php?admin=<?php echo $admin['id'] ?>">Edit</a>&nbsp;
								<a href="delete_admin.php?admin=<?php echo $admin['id'] ?>" onclick=" return confirm('Are you sure you want to delete the admin?')" >Delete</a>
							</td>
						</tr>
						<?php } ?>
			</tbody>
		</table>
		<a href="new_admin.php">+Add a new admin</a>
	</div>
</div>

<?php include("../includes/layouts/footer.php"); ?>
