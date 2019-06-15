<?php 

	//To check if the query was successfully fired.
	function confirm_query($result, $query) {
		if(!$result) {
			die($query ." not executed!");
		}
	}

	//Show errors in the form
	function form_errors($errors) {
		global $highlight;

		$output = "";
		if(!empty($errors)) {
			$output = "<div class='error'>";
			$output .= "Please fix the following errors:";
			$output .= "<ul>";
			foreach ($errors as $key => $error) {
				$highlight[] = $key;
				$output .= "<li>";
				$output .= htmlentities($error);
				$output .= "</li>";
			}
			$output .= "</ul>";
			$output .= "</div>";

			return $output;
		}
	}

	//Firing a query to find all the subjects in the DB
	function find_all_subjects($public=true) {
		global $connection;

		$query  = "SELECT * FROM subjects ";
		if($public){
			$query .= "WHERE visible = 1 ";
		}
		$query .= "ORDER BY position ASC";

		$result = mysqli_query($connection, $query);
		confirm_query($result, $query);

		return $result;
	}

	//Firing a query to find pages under a particular subject.
	function find_pages_for_subject($subject_id, $public=true) {
		global $connection;

		$query  = "SELECT * FROM pages ";
		$query .= "WHERE subject_id = $subject_id " ;
		if($public){
			$query .= "AND visible = 1 " ; 
		}
		$query .= "ORDER BY position ASC"; 

		$result = mysqli_query($connection, $query);
		confirm_query($result, $query);
		
		return $result;
	}

	// This function requires subject_array and $page_array or null.

	function find_subject_by_id($subject_id, $public=true) {
		global $connection;

		$subject_id = mysqli_real_escape_string($connection, $subject_id);

		$query  = "SELECT * FROM ";
		$query .= "subjects WHERE ";
		$query .= "id = {$subject_id} ";
		if ($public) {
			$query .= "AND visible = 1 ";
		}
		$query .= "LIMIT 1";

		$result = mysqli_query($connection, $query);
		confirm_query($result, $query);
		// Since we will have only one row in the result, why not return the 1st and the only row itself.
		if($subject = mysqli_fetch_assoc($result))
			return $subject;
		else {
			return null; 
		}
	}

	function find_page_by_id($page_id, $public=true) {
		global $connection;

		$page_id = mysqli_real_escape_string($connection, $page_id);
		$query  = "SELECT * FROM ";
		$query .= "pages WHERE ";
		$query .= " id = {$page_id} ";
		if ($public) {
			$query .= "AND visible = 1 ";
		}
		$query .= " LIMIT 1";

		$result = mysqli_query($connection, $query);
		confirm_query($result, $query);

		$page = mysqli_fetch_assoc($result);
		return $page;
	}

	function navigation($subject_array, $page_array) {

		$output = "<ul class=\"subjects\">";
		$subject_set = find_all_subjects(false);
		while($subject = mysqli_fetch_assoc($subject_set)) { 
			$output .= "<li";
			if($subject_array &&  $subject['id'] == $subject_array['id']) {
				$output .= " class=\"selected\" ";
				}
			$output .=  ">";
				$output .= "<a href=\"manage_content.php?subject=";
				$output .= urlencode($subject['id']);
				$output .= "\">";
				$output .=  htmlentities($subject['menu_name']);
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
							$output .= htmlentities($page['menu_name']);
							$output .= "</a></li>";
						} 
			mysqli_free_result($page_set);
			$output .= "</ul></li>";
		} 
		mysqli_free_result($subject_set);
		$output .= "</ul>";

		return $output;
	}

	function public_navigation($subject_array, $page_array) {

		$output = "<ul class=\"subjects\">";
		$subject_set = find_all_subjects();
		while($subject = mysqli_fetch_assoc($subject_set)) { 

			$output .= "<li";
			if($subject_array &&  $subject['id'] == $subject_array['id']) {
				$output .= " class=\"selected\" ";
			}
			$output .=  ">";
			$output .= "<a href=\"index.php?subject=";
			$output .= urlencode($subject['id']);
			$output .= "\">";
			$output .=  htmlentities($subject['menu_name']);
			$output .= "</a>";
			if($subject_array['id'] == $subject['id'] || $page_array['subject_id'] == $subject['id']) {

				$page_set = find_pages_for_subject($subject['id'], false);
				$output .= 	"<ul class=\"pages\">";
				while($page = mysqli_fetch_assoc($page_set)){
				$output .= "<li";
					if($page_array && $page['id'] == $page_array['id']) {
						$output .= " class=\"selected\"";
					}
					$output .= ">";
					$output .= "<a href=\"index.php?page=";
					$output .= urlencode($page['id']);
					$output .= "\">";
					$output .= htmlentities($page['menu_name']);
					$output .= "</a></li>";
				} 
				$output .= "</ul>";
				mysqli_free_result($page_set);
			}
				$output .="</li>"; //end of the subject li
		} 
		mysqli_free_result($subject_set);
		$output .= "</ul>";

		return $output;
	}

	function find_default_page_for_subject_selected($subject_id) {
		 $page_set = find_pages_for_subject($subject_id);
		 if($first_page = mysqli_fetch_assoc($page_set)) {
		 	return $first_page;
		 } else {
		 	return null;
		 }
	}

	function find_selected_page($public=false) {
		global $current_subject, $current_page;

		if(isset($_GET['subject'])) {
			$current_subject = find_subject_by_id($_GET['subject'],$public);
			if($current_subject && $public) {
				$current_page = find_default_page_for_subject_selected($current_subject['id']);
			}
			else {
				$current_page = null;	
			}
		} else if (isset($_GET['page'])) {
			$current_page = find_page_by_id($_GET['page'], $public);
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

	function find_all_admins() {
		global $connection;

		$query  = "SELECT * FROM ";
		$query .= "admins ORDER BY username ASC";
		// $query .= "ORDER BY ALPHABETS ASC";

		$result = mysqli_query($connection, $query);
		confirm_query($result, $query);

		return $result;
	}

	function find_admin_by_id($admin_id) {
		global $connection;

		$admin_id = mysqli_real_escape_string($connection, $admin_id);
		$query  = "SELECT * FROM ";
		$query .= "admins WHERE";
		$query .= " id = $admin_id";
		$query .= " LIMIT 1";

		$result = mysqli_query($connection, $query);
		confirm_query($result, $query);

		if($current_admin = mysqli_fetch_assoc($result)) {
			return $current_admin;
		} else {
			return null;
		}
	}

	function find_admin_by_username($username) {
		global $connection;

		$admin_username = mysqli_real_escape_string($connection, $username);
		$query  = "SELECT * FROM ";
		$query .= "admins WHERE";
		$query .= " username = '$username'";
		$query .= " LIMIT 1";

		$result = mysqli_query($connection, $query);
		confirm_query($result, $query);

		if($current_admin = mysqli_fetch_assoc($result)) {
			return $current_admin;
		} else {
			return null;
		}
	}

	function password_encrypt($password) {
		$hash_format = "$2y$10$"; // Tells PHP to use blowfish with a 'cost' of 10
		$salt_length = 22;
		$salt = generate_salt($salt_length);
		$format_and_salt = $hash_format.$salt;
		$hash = crypt($password, $format_and_salt);
		return $hash;	
	}

	function generate_salt($length) {
		$unique_random_string = md5(uniqid(mt_rand(), true));
		$base64_string = base64_encode($unique_random_string);
		$modified_base64_string = str_replace("+", ".", $base64_string);
		$salt = substr($modified_base64_string, 0, $length);
		
		return $salt; 
	}

	function password_check($password, $existing_hash) {
		$hash = crypt($password, $existing_hash);
		if($hash == $existing_hash) {
			return true;
		} else {
			return false;
		}
	}

	function attempt_login($username, $password) {
		$admin = find_admin_by_username($username);
	 	if($admin) {
	 		if(password_check($password, $admin["hashed_password"])) {
	 			return $admin;
	 		} else {
	 			return false;
	 		}
 		} else {
	 			return false;
		}
	}			

	function logged_in() {
		return isset($_SESSION['admin_id']); 
	}

	function confirm_logged_in() {
		if(!logged_in() ) {
			redirect_to("login.php");
		}
	}

 ?> 