<?php

if (isset($_POST["signup-submit"])){
	
	require "dbh.php";
	
	$username = $_POST["uid"];
	$uemail = $_POST["email"];
	$password = $_POST["password"];
	$re_password = $_POST["re-password"];
	
	if(empty($username) || empty($uemail) || empty($password) || empty($re_password)){
		
		header("Location: ../signup.php?error=emptyFields_&uid=".$username."_&email=".$uemail);
		exit();
	}
	
	elseif(!preg_match("/^[a-zA-z0-9]*$/", $username)){
		header("Location: ../signup.php?error=invalidUserName_&email=".$uemail);
		exit();
		
	}
	
	elseif($password !== $re_password){
		header("Location: ../signup.php?error=passwordError_&uid=".$username."_&email=".$uemail);
		exit();
		
	}
	
	else{
		
		$sql = "SELECT uname FROM users WHERE uname=?";
		$stmt = mysqli_stmt_init($conn);
		
		if(! mysqli_stmt_prepare($stmt, $sql)){
			header("Location: ../signup.php?error=sqlError");
			exit();
			
		} else{
			mysqli_stmt_bind_param($stmt, "s", $username);
			mysqli_stmt_execute($stmt);
			mysqli_stmt_store_result($stmt);
			$resultCheck = mysqli_stmt_num_rows($stmt);
			if($resultCheck > 0){
				header("Location: ../signup.php?error=userNameTaken_&email=".$uemail);
				exit();
				
			}
			else{
				
				$sql = "INSERT INTO users(uname, email, password) VALUES (?, ? ,?) ";
				$stmt = mysqli_stmt_init($conn);
				
				if(! mysqli_stmt_prepare($stmt, $sql)){
					header("Location: ../signup.php?error=sqlError");
					exit();
			
				}
				else{
					$hPassword = password_hash($password, PASSWORD_DEFAULT);
					
					mysqli_stmt_bind_param($stmt, "sss", $username, $uemail,$hPassword);
					mysqli_stmt_execute($stmt);
					mysqli_stmt_store_result($stmt);
					header("Location: ../login.php");
					exit();
				}
				
				
			}
		}
	}
		
mysqli_stmt_close($stmt);
mysqli_close($conn);		
	
		
}


else{
	echo("PLEASE LEAVE!");
	header("Location: ../signup.php.php");
	exit();
}