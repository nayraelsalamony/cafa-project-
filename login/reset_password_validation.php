<?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require "../dbConnections/pdo.php";

    $errors = [];
    $status = [];

    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirmpass'];

    if (empty($username) or $username=="") {
        $errors["username"] = "Username is required!";
    }
    
    if (empty($password) or $password=="") {
        $errors["password"] = "Password is required!";
    }elseif(strlen($password)<8){
        $errors["password"] = "Password must be greater than 8 characters!";
    
    }
    if (empty($confirm_password ) or $confirm_password=="") {
        $errors["confirmpass"] = "Confirm your password!";
    }
    if($password != $confirm_password ){
        $errors["confirmpass"] = "Not matched password!";
    }

    try{
        $select_query = "SELECT * FROM user where `username`=:username";
        $select_stmt = $db->prepare($select_query);
        $select_stmt->bindParam(':username', $username);
        $result = $select_stmt->execute(); 
        $row = $select_stmt->fetchAll(PDO::FETCH_OBJ);

        foreach ($row as $user) {
           $user_username = $user->username;
        }

        

        if($username != $user_username){
            $errors["username"] = "User not found!";
        }

        if(sizeof($errors)>0){
            $status['fail'] = "Can't Update Password!";   
            $errors = json_encode($errors);
            $status = json_encode($status);
            header("Location:./forget_password.php?errors={$errors}&status={$status}");
        }

        if(sizeof($errors)==0){

            $hashed_password = password_hash($password,PASSWORD_DEFAULT);

            $update_query = 'UPDATE user SET  `password` = :password WHERE `username` = :username';
            $update_stmt= $db->prepare($update_query);
            $update_stmt->bindParam(':username', $username);
            $update_stmt->bindParam(':password', $hashed_password);
            $res = $update_stmt->execute();
            if($res){
                $status['success'] = "Password Updated Successfully!";
                $status = json_encode($status);
                header("Location:./login.php?status={$status}");
            }
        }
    

    }catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
    }
