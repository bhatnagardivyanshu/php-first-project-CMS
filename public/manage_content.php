<?php include("../includes/session.php"); ?>
<?php include("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php include("../includes/layouts/header.php"); ?>


<?php 
	find_selected_page();	
 ?>

	<div id="main">
		<div id="navigation">
			<?php echo navigation($current_subject, $current_page); ?>
			<br/>
			<a href="new_subject.php">+ Add a Subject</a>
		</div>
		<div id="page">
			<?php echo message(); ?>
			<?php $errors = errors(); ?>
			<?php form_errors($errors) ?>
			<?php 
			if($current_subject) {
				echo "<h2>Manage Subject</h2>";
				echo $current_subject['menu_name']."<br>";
			} else if ($current_page) {
				echo "<h2>Manage Page</h2>";
				echo $current_page['content'];
			} else {
				echo "<h2>Manage Content</h2>";
				echo "Please select a subject or a page";
			}
			 ?> 
		</div>
	</div>

<?php include("../includes/layouts/footer.php"); ?>