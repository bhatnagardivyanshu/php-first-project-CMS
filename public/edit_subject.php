<?php include("../includes/session.php"); ?>
<?php include("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?> 
<?php confirm_logged_in(); ?>
<?php require_once("../includes/validation_functions.php"); ?>

<?php find_selected_page(); ?>
 <!-- sets 
	$current_subject & $current_page using $_GET[]
  -->
<?php $layout_context = "admin" ?>
<?php include("../includes/layouts/header.php"); ?>

<?php 
	if(!$current_subject) {
		redirect_to("manage_content.php");
	} 
?>

<?php 
	if(isset($_POST['submit'])) {

		//Validations
		$required_fields = ["menu_name", "position", "visible"];
		validate_presences($required_fields);

		$fields_with_max_lengths = ["menu_name"=>30];
		validate_max_lengths($fields_with_max_lengths);

		if(empty($errors)) {
			//Perform Update

			$id = $current_subject['id'];
			$menu_name = mysql_prep($_POST['menu_name']);
			$position = (int) $_POST['position'];
			$visible = (int) $_POST['visible'];  

			$query  = "UPDATE subjects SET ";
			$query .= "menu_name='{$menu_name}', ";
			$query .= "position='{$position}', ";
			$query .= "visible='{$visible}' ";
			$query .= "WHERE id={$id} ";
			$query .= "LIMIT 1";

			$result = mysqli_query($connection, $query);
			confirm_query($result, $query);

			if($result && mysqli_affected_rows($connection) >= 0) {
				$_SESSION['message'] = "Subject updated successfully";
				redirect_to("manage_content.php");
			} else {
				$message = "There was some problem updating the subject";
			}
		}
	} 
	else {
		// This is probably a GET request.

	}// end: if(isset($_POST['submit']))
?>


	<div id="main">
		<div id="navigation">
			<?php echo navigation($current_subject, $current_page); ?>
		</div>
		<div id="page">
		<?php 
			//message is a variable set if updation fails, if succeeded, the message is passed in SESSION 
			if(!empty($message)) {
				echo "<div class='message'>" . htmlentities($message) . "</div>";
			}
		?>
		<?php echo form_errors($errors); ?>
		
		<h2>Edit Subject <?php echo htmlentities($current_subject['menu_name']) ?></h2>
			<form action="edit_subject.php?subject=<?php echo urlencode($current_subject['id']) ?>" method="post">
				<p>Menu name:
					<input type="text" name="menu_name" value="<?php echo htmlentities($current_subject['menu_name']) ?>"/>
				</p>
				<p>Position:
					<select name="position">
						<?php 
							$subject_count = mysqli_num_rows(find_all_subjects());
							echo "{$subject_count}";
							for($count = 1; $count <= $subject_count; $count++) {
								echo "<option value=\"{$count}\" ";
								if ($count == $current_subject['position']) {
									echo "selected";
								}
								echo ">{$count}</option>";
							}
						 ?>
					</select>
				</p>
				<p>Visible:
					<input type="radio" name="visible" value="0" <?php if($current_subject['visible'] == 0 ) echo "checked"; ?> >No &nbsp;
					<input type="radio" name="visible" value="1" <?php if($current_subject['visible'] == 1 ) echo "checked"; ?> >Yes
				</p> 
				<input type="submit" name="submit" value="Edit Subject">
			</form>     
			<br/>
			<a href="manage_content.php">Cancel</a>&nbsp; &nbsp;
			<a href="delete_subject.php?subject=<?php echo urldecode($current_subject['id']) ?>" onclick="return confirm('Are you sure?')">Delete Subject</a>
		</div>
	</div>

<?php include("../includes/layouts/footer.php"); ?>