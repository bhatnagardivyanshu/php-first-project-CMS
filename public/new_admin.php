<?php include("../includes/session.php"); ?>
<?php include("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation_functions.php"); ?>

<?php $layout_context = "admin"; ?>
<?php include("../includes/layouts/header.php"); ?>

<?php
	if(isset($_POST['submit'])) { 

		$required_fields = ["username", "password"];
		validate_presences($required_fields);

		$fields_with_max_lengths = ["username" => 30, "password" => 10];
		validate_max_lengths($fields_with_max_lengths);

		if(empty($errors)) {
			$username = mysql_prep($_POST['username']);
			$hashed_password = password_encrypt($_POST['password']);

			$query  = "INSERT INTO admins ";
			$query .= "(username, hashed_password) ";
			$query .= "VALUES ('{$username}', '{$hashed_password}') ";

			$result = mysqli_query($connection, $query);
			confirm_query($result, $query);

			if($result) {
				$_SESSION['message'] = "Admin '";
				$_SESSION['message'] .= $username;
				$_SESSION['message'] .= "' has been CREATED successfully";

				redirect_to("manage_admins.php");
			} else {
				$message = "There was some problem CREATING the Admin. ";
			}

		}	

	}
 ?>

<div id="main">
	<div id="navigation"></div>
	<div id="page">
		<?php echo message(); ?>
		
		<?php echo form_errors($errors); ?>

		<h2>Create Admin</h2>
		<form action="new_admin.php" method="post">
			<p>
				Username:
				<input type="text" name="username" value="">
			</p>
			<p>
				Password:
				<input type="password" name="password" value="">
			</p>
			<input type="submit" name="submit" value="Create Admin">
		</form><br/>
		<a href="manage_admins.php">Cancel</a>
	</div>
</div>

<?php include("../includes/layouts/footer.php"); ?>
