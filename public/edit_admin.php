<?php include("../includes/session.php"); ?>
<?php include("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>

<?php confirm_logged_in(); ?>

<?php require_once("../includes/validation_functions.php"); ?>

<?php $layout_context = "admin" ?>
<?php require_once("../includes/layouts/header.php"); ?>

<?php 
	$admin_id = $_GET['admin'];
	$current_admin = find_admin_by_id($admin_id); 
	if(!$current_admin) {
		redirect_to("manage_admins.php");
	}
?>

<?php 
		if(isset($_POST['submit'])) {
			$required_fields = ["username", "password"];
		validate_presences($required_fields);

		$fields_with_max_lengths = ["username" => 30, "password" => 10];
		validate_max_lengths($fields_with_max_lengths);

		if(empty($errors)) {
			$username = mysql_prep($_POST['username']);
			$hashed_password = password_encrypt($_POST['password']);

			$query  = "UPDATE admins SET ";
			$query .= "username = '{$username}', hashed_password = '{$hashed_password}' ";
			$query .= "WHERE id={$current_admin['id']} ";
			$query .= "LIMIT 1";

			$result = mysqli_query($connection, $query);
			confirm_query($result, $query);

			if($result) {
				$_SESSION['message'] = "Admin '";
				$_SESSION['message'] .= $username;
				$_SESSION['message'] .= "' has been UPDATED successfully";

				redirect_to("manage_admins.php");
			} else {
				$message = "There was some problem UPDATING the Admin. ";
			}

		}
		}	
 ?>

<div id="main">
	<div id="navigation"></div>
	<div id="page">
		<?php echo message(); ?>
		<?php $errors = errors(); ?>
		<?php echo form_errors($errors); ?>

		<h2>Edit Admin</h2>
		<form action="edit_admin.php?admin=<?php echo $current_admin['id'] ?>" method="post">
			<p>
				Username:
				<input type="text" name="username" value="<?php echo $current_admin['username'] ?>">
			</p>
			<p>
				Password:
				<input type="password" name="password" value="">
			</p>
			<input type="submit" name="submit" value="Edit Admin">
		</form><br/>
		<a href="manage_admins.php">Cancel</a>
	</div>
</div>