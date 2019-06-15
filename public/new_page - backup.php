<?php include("../includes/session.php"); ?>
<?php include("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php require_once("../includes/validation_functions.php"); ?>

<?php find_selected_page(); //returns $current_subject/$current_page using $_GET[]?>

<?php include("../includes/layouts/header.php"); ?>

<?php 
	if(!$current_subject) {
		redirect_to("manage_content.php");
	}
 ?>

<?php 
	if(isset($_POST['submit'])) {

		// validations
		$required_fields = ["menu_name", "position", "visible", "content"];
		validate_presences($required_fields); //adds missing values to errors[]

		$fields_with_max_lengths = ["menu_name" => 30];
		validate_max_lengths($fields_with_max_lengths); //adds values with illegal lengths to errors[]

		if(empty($errors)) {
			$menu_name = mysql_prep($_POST['menu_name']);
			$position = (int) $_POST['position'];
			$visible = (int) $_POST['visible'];
			$content = htmlentities($_POST['content']);

			$query  = "UPDATE pages SET ";
			$query .= "menu_name = '{$menu_name}', ";
			$query .= "position = {$position}, ";
			$query .= "visible = {$visible}, ";
			$query .= "content = '{$content}' ";
			$query .= "WHERE id = {$current_page['id']} ";
			$query .= "LIMIT 1";

			$result = mysqli_query($connection, $query);
			confirm_query($result, $query);

			if($result && mysqli_affected_rows($connection) >= 0) {
				$_SESSION['message'] = "Page updated successfully";
				redirect_to("manage_content.php");
			} else {
				$message = "There was some problem updating the page";
			}

		}

	} 
 ?>

<div id="main">
	<div id="navigation">
		<?php echo navigation($current_subject, $current_page); ?>
	</div>
	<div id="page">
	
		<?php
			if(!empty($message)) {
				echo "<div class='message'>" . htmlentities($message) . "</div>";
			}
		 ?>

		 <?php echo form_errors($errors); ?>
		 
		<h2>Create New Page Under <?php echo ($current_subject['menu_name']) ?></h2>	

		<form action="create_page.php?page=<?php echo $current_subject['id'] ?>" method="post">
			<p>
				Menu name:
				<input type="text" name="menu_name" value="">
			</p>
			<p>
				Position:
				<select name="position">
					<?php 
						$page_count = mysqli_num_rows(find_pages_for_subject($current_page['subject_id']));
						for($count = 1; $count <= $page_count; $count++) {
							$output  = "<option";
							if( $current_page['position'] == $count ) {
								$output .= " selected";
							}
							$output .= " value = '{$count}'>{$count}";
							$output .= "</option>";
							echo $output;			
						}
					 ?>
				</select>
			</p>
			<p>
				Visible:
				<input type="radio" name="visible" value="0" <?php if($current_page['visible'] == 0) echo "checked"; ?> >No &nbsp;
				<input type="radio" name="visible" value="1" <?php if($current_page['visible'] == 1) echo "checked"; ?>>Yes
			</p>
			<p>
				<textarea name="content" rows="10" cols="50">
					<?php echo $current_page["content"]; ?>
				</textarea>
			</p>
			<input type="submit" name="submit" value="Edit Page">
		</form><br/>
		<a href="manage_content.php?subject=<?php echo $current_page['id']; ?>">Cancel</a>
		&nbsp;
		<a href="delete_page.php?page=<?php echo $current_page['id']; ?>"></a>
	</div>
</div>

<?php include("..includes/layouts/footer.php") ?>