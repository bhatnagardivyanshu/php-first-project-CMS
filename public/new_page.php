<?php include("../includes/session.php") ?>
<?php include("../includes/db_connection.php") ?>
<?php require_once("../includes/functions.php") ?>
<?php require_once("../includes/validation_functions.php") ?>
<?php confirm_logged_in(); ?>
<?php $layout_context = "admin" ?>
<?php include("../includes/layouts/header.php") ?>

<?php find_selected_page(); ?>

<?php if(!$current_subject) {
	redirect_to("manage_content.php");  
	} ?>

<?php 
	if(isset($_POST['submit'])) {

		$required_fields = ["menu_name", "position", "visible" ,"content"];
		validate_presences($required_fields);

		$fields_with_max_lengths = ["menu_name" => 30];
		validate_max_lengths($fields_with_max_lengths);

		if(!empty($errors)) {
			$_SESSION['errors'] = $errors;
			redirect_to("new_page.php");
		}

		// $subject_id = $current_subject['id'];
		if(empty($errors)) {
			$menu_name = mysql_prep($_POST['menu_name']);
			$position = (int) $_POST['position'];
			$visible = (int)$_POST['visible'];
			$content = mysql_prep($_POST['content']);


			$query  = "INSERT INTO pages ";
			$query .= "(subject_id, menu_name, position, visible, content) ";
			$query .= "VALUES ({$current_subject['id']}, '{$menu_name}', {$position}, {$visible}, '{$content}');";

			$result = mysqli_query($connection, $query);
			confirm_query($result, $query);

			if($result) {

				$_SESSION['message'] = "Page Added to ";
				$_SESSION['message'].= $current_subject["menu_name"];
				$_SESSION['message'].= " successfully.";
				redirect_to("manage_content.php");
			} else {
				$message = "There was some problem adding the page.";
			}
		}
	}
 ?>

<div id="main">
	<div id="navigation">
		<?php echo navigation($current_subject, $current_page); ?>
	</div>

	<div id="page">
		<?php echo message(); ?>
		<?php $errors = errors(); ?>
		<?php echo form_errors($errors); ?>
		<h2>Create a new Page in '<?php echo $current_subject['menu_name']; ?>'</h2>

		<form action="new_page.php?subject=<?php echo $current_subject['id'] ?>" method="post">
			<p>
				Menu name : 
				<input type="text" name="menu_name" value="">
			</p>
			<p>
				Position :
				<select name="position">
					<?php 
						$page_count = mysqli_num_rows(find_pages_for_subject($current_subject['id'], false));
						for($count = 1; $count <= $page_count+1; $count++) {
							$output  = "<option value = \"{$count}\">";
							$output .= "{$count}</option>";
							echo $output;
						}
					 ?>
				</select>
			</p>
			<p>
				Visible : 
				<input type="radio" name="visible" value="0">No &nbsp;
				<input type="radio" name="visible" value="1">Yes
			</p>
				Content : 
			<p>
				<textarea name="content" cols="50" rows="10"></textarea>
			</p>
			<input type="submit" name="submit" value="Create Page">
		</form>
		<br>
		<a href="manage_content.php?subject=<?php echo $current_subject['id'] ?>">Cancel</a>

	</div><!-- End div for #page -->
</div><!-- End div for #main -->