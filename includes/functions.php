<?php 

	//To check if the query was successfully fired.
	function confirm_query($result, $query) {
		if(!$result) {
			die($query ." not executed!");
		}
	}

	//Show errors in the form
	function form_errors($errors) {
		$output = "";
		if(!empty($errors)) {
			$output = "<div class='error'>";
			$output .= "Please fix the following errors:";
			$output .= "<ul>";
			foreach ($errors as $key => $error) {
				
				$output .= "<li>{$error}</li>";
			}
			$output .= "</ul>";
			$output .= "</div>";

			return $output;
		}
	}

	//Firing a query to find all the subjects in the DB
	function find_all_subjects() {
		global $connection;

		$query  = "SELECT * FROM ";
		$query .= "subjects WHERE ";
		$query .= "visible = 1 ";
		$query .= "ORDER BY position ASC";

		$result = mysqli_query($connection, $query);
		confirm_query($result, $query);

		return $result;
	}

	//Firing a query to find pages under a particular subject.
	function find_pages_for_subject($subject_id) {
		global $connection;

		$query  = "SELECT * FROM ";
		$query .= "pages WHERE visible = 1 " ; 
		$query .= "AND subject_id = $subject_id " ;
		$query .= "ORDER BY position ASC"; 

		$result = mysqli_query($connection, $query);
		confirm_query($result, $query);
		
		return $result;
	}

	// This function requires subject_array and $page_array or null.

	function find_subject_by_id($subject_id) {
		global $connection;

		$subject_id = mysqli_real_escape_string($connection, $subject_id);

		$query  = "SELECT * FROM ";
		$query .= "subjects WHERE ";
		$query .= "id = {$subject_id} ";
		$query .= "LIMIT 1";

		$result = mysqli_query($connection, $query);
		confirm_query($result, $query);
		// Since we will have only one row in the result, why not return the row itself.
		if($subject = mysqli_fetch_assoc($result))
			return $subject;
		else {
			return null;
		}
	}

	function find_page_by_id($page_id) {
		global $connection;

		$page_id = mysqli_real_escape_string($connection, $page_id);
		$query  = "SELECT * FROM ";
		$query .= "pages WHERE ";
		$query .= " id = {$page_id}";
		$query .= " LIMIT 1";

		$result = mysqli_query($connection, $query);
		confirm_query($result, $query);

		$page = mysqli_fetch_assoc($result);
		return $page;
	}

	function navigation($subject_array, $page_array) {

		$output = "<ul class=\"subjects\">";
		$subject_set = find_all_subjects();
		while($subject = mysqli_fetch_assoc($subject_set)) { 
			$output .= "<li";
			if($subject_array &&  $subject['id'] == $subject_array['id']) {
				$output .= " class=\"selected\" ";
				}
			$output .=  ">";
				$output .= "<a href=\"manage_content.php?subject=";
				$output .= urlencode($subject['id']);
				$output .= "\">";
				$output .=  $subject['menu_name'];
				$output .= "</a>";
				$page_set = find_pages_for_subject($subject['id']);
					$output .= 	"<ul class=\"pages\">";
						while($page = mysqli_fetch_assoc($page_set)){
						$output .= "<li";
						if($page_array && $page['id'] == $page_array['id']) {
							$output .= " class=\"selected\"";
						}
						$output .= ">";
							$output .= "<a href=\"manage_content.php?page=";
							$output .= urlencode($page['id']);
							$output .= "\">";
							$output .= $page['menu_name'];
							$output .= "</a></li>";
						} 
			mysqli_free_result($page_set);
			$output .= "</ul></li>";
		} 
		mysqli_free_result($subject_set);
		$output .= "</ul>";

		return $output;
	}

	function find_selected_page() {
		global $current_subject, $current_page;

		if(isset($_GET['subject'])) {
			$current_subject = find_subject_by_id($_GET['subject']);
			$current_page = null;
		} else if (isset($_GET['page'])) {
			$current_page = find_page_by_id($_GET['page']);
			$current_subject = null;
		} else {
			$current_page = null;
			$current_subject = null;
		}
	}

	function redirect_to($location) {
		header("Location: ".$location);
		exit;
	}

	function mysql_prep($string) {
		global $connection;

		$escaped_string = mysqli_real_escape_string($connection, $string);
		return $escaped_string;
	}
 ?>