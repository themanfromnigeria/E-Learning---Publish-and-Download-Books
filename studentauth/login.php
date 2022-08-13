<?php 
session_start();

if (isset($_POST['username']) && 
	isset($_POST['password'])) {
    
    # Database Connection File
	include "../admin/db_conn.php";
    
    # Validation helper function
	include "func-validation.php";
	
	
	//    Get data from POST request 
	//    and store them in var
	

	$username = $_POST['username'];
	$password = $_POST['password'];

	# simple form validation

	$text = "username";
	$location = "../index.php";
	$ms = "error";
    is_empty($username, $text, $location, $ms, "");

    $text = "password";
	$location = "../index.php";
	$ms = "error";
    is_empty($password, $text, $location, $ms, "");

    # search for the username
    $sql = "SELECT * FROM users 
            WHERE username=?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$username]);

    # if the username is exist
    if ($stmt->rowCount() === 1) {
    	$user = $stmt->fetch();

    	$user_id = $user['id'];
    	$user_email = $user['username'];
    	$user_password = $user['password'];
    	if ($username === $user_email) {
    		if (password_verify($password, $user_password)) {
    			$_SESSION['users_id'] = $user_id;
    			$_SESSION['users_email'] = $user_email;
    			header("Location: ../home.php");
    		}else {
    			# Error message
    	        $em = "Incorrect User name or password";
    	        header("Location: ../index.php?error=$em");
    		}
    	}else {
    		# Error message
    	    $em = "Incorrect User name or password";
    	    header("Location: ../index.php?error=$em");
    	}
    }else{
    	# Error message
    	$em = "Incorrect User name or password";
    	header("Location: ../index.php?error=$em");
    }

}else {
	# Redirect to "../login.php"
	header("Location: ../index.php");
}