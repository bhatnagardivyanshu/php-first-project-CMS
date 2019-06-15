<?php include("../includes/session.php"); ?>
<?php include("../includes/db_connection.php"); ?>
<?php require_once("../includes/functions.php"); ?>

<?php confirm_logged_in(); ?>
<?php $layout_context = "admin" ?>
<?php include("../includes/layouts/header.php"); ?>


<?php 
	find_selected_page();	
 ?>

	<div id="main">
		<div id="navigation">
		<br>
		<a href="admin.php">&laquo; Main Menu</a><br>
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
				echo "Menu name: ".htmlentities($current_subject['menu_name'])."<br/>";
				echo "Position : ".$current_subject['position']."<br/>";
				echo "Visible : " ;
				echo $current_subject['visible'] == 1 ? 'Yes<br/></br>' : 'No</br></br>' ;
				echo "<a href=\"edit_subject.php?subject={$current_subject['id']}\">Edit Subject</a>";
				echo "<br><br><hr/>";
				echo "<h3>Pages in this Subject</h3>";
				$pages_for_this_subject = find_pages_for_subject($current_subject['id'], false);?> 
				<ul>
				<?php
					while($page = mysqli_fetch_assoc($pages_for_this_subject)) {
						echo "<a href=\"manage_content.php?page=";
						echo $page['id'];
						echo "\"><li>";
						echo htmlentities($page['menu_name']);
						echo "</li></a>";
					} ?>
				</ul>
				<br><br>
				<a href="new_page.php?subject=<?php echo $current_subject['id']; ?>">+Add a new page to this subject</a>

			<?php 
			} 
			else if ($current_page) {
				echo "<h2>Manage Page</h2>";
				echo 'Menu name : '.htmlentities($current_page['menu_name'])."<br/>";
				echo "Position : ".$current_page['position']."<br/>";
				echo "Visible : " ;
				echo $current_page['visible'] == 1 ? 'Yes<br/>' : 'No</br>' ;
				echo "<div class='content'>Content : ". nl2br(htmlentities($current_page['content']))."</div></br></br>";
				echo "<a href='edit_page.php?page={$current_page["id"]}'>Edit Page</a>";
			} else {
				echo "<h2>Manage Content</h2>";
				echo "Please select a subject or a page";
			}
			 ?> 
		</div>
	</div>

<?php include("../includes/layouts/footer.php"); ?>