<?php include("../includes/session.php"); ?>
<?php include("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation_functions.php"); ?>

<?php 
	if(isset($_POST['submit'])) {
		//Process the form.
		$menu_name = mysql_prep($_POST['menu_name']);
		$position = (int) $_POST['position'];
		$visible = (int) $_POST['visible'];  

		//Validations
		$required_fields = ["menu_name", "position", "visible"];
		validate_presences($required_fields);

		$fields_with_max_lengths = ["menu_name"=>30];
		validate_max_lengths($fields_with_max_lengths);

		if(!empty($errors)) {
			$_SESSION['errors'] = $errors;
			redirect_to("new_subject.php");
		}

		$query  = "INSERT INTO subjects ";
		$query .= "( menu_name, position, visible ) ";
		$query .= " VALUES ( '{$menu_name}', {$position}, {$visible});";

		$result = mysqli_query($connection, $query);
		confirm_query($result, $query);

		if($result) {
			$_SESSION['message'] = "Subject added successfully";
			redirect_to("manage_content.php");
		} else {
			$_SESSION['message'] = "There was some problem adding the subject";
			redirect_to("new_subject.php");
		}

		//Escape all strings
		$menu_name = mysqli_real_escape_string($connection, $menu_name);
		$query  = "INSERT INTO subjects ";
		$query .= "(menu_name, position, visible) ";
		$query .= "VALUES ('{menu_name}, {$position}, {visible}')";

		$result = mysqli_query($connection, $query);
		confirm_query($result, $query);

	}	
	else {
		// This is probably a GET request.
		redirect_to("new_subject.php");
	}
?>

<?php 
	if(isset($conection)) {
		mysqli_close($conection);
	}
 ?>