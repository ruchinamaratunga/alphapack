<?php

if (isset($_POST["login-submit"])){
	
	require "dbh.php";
	
	$username = $_POST["uid"];
	$password = $_POST["password"];
	
	if(empty($username)|| empty($password)){

		header("Location: ../login.php?error=emptyFields_&uid=".$username);
		exit();

	}
	
	else{
		
		$sql = "SELECT * from users WHERE uname = ? OR email  =?";
		$stmt = mysqli_stmt_init($conn);
		
		if (! mysqli_stmt_prepare($stmt, $sql)){
			header("Location: ../login.php?error=sqlerror");
			exit();
		} else{
			
			mysqli_stmt_bind_param($stmt, "ss", $username, $username);
			mysqli_stmt_execute($stmt);
			$results = mysqli_stmt_get_result($stmt);
			if ($row = mysqli_fetch_assoc($results)){
				
				$passCheck = password_verify($password,$row["password"]);
				if ($passCheck == false){
					header("Location: ../login.php?error=noUser");
					exit();
				}
				else if($passCheck==true){
					session_start();
					
					$_SESSION['uid']= $row['id'];
					$_SESSION['user_name']= $row['uname'];
					$_SESSION['uemail']= $row['email'];
					
					header("Location: ../index.php");
					exit();
				}
				else{
					header("Location: ../login.php?error=noUser");
					exit();
				}
			}
			else{
				header("Location: ../login.php?error=nouser");
				exit();
			}
		}
	}
		
}

else{
	header("Location: ../login.php");
	exit();
}