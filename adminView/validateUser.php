<?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require "../dbConnections/pdo.php";

    $errors = [];
    $data = [];
    $status = [];


    if (isset($_POST['submit'])) {

    
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $confirm_password = $_POST['confirmpass'];
            if (empty($username) or $username=="") {
                $errors["username"] = "Name is required!";
            }else{
                try{
                    $select_query = "SELECT * FROM user where `username`=:username";
                    $select_stmt = $db->prepare($select_query);
                    $select_stmt->bindParam(':username', $username);
                    $result = $select_stmt->execute(); 
                    $row = $select_stmt->fetchAll(PDO::FETCH_OBJ);
        
                    if(sizeof($row) != 0){
                        $errors["username"] = "Username already exists!";   
                    }
            
                }catch (PDOException $e) {
                    echo 'Connection failed: ' . $e->getMessage();
                }
            }
            
            if(empty($email) or $email==""){
                $errors["email"] ="Email is required!";
            }elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
                    $errors["email"] = "Invalid Email!";
                
            }else{
                    try{
                        $select_query = "SELECT * FROM user where `email`=:email";
                        $select_stmt = $db->prepare($select_query);
                        $select_stmt->bindParam(':email', $email);
                        $result = $select_stmt->execute(); 
                        $row = $select_stmt->fetchAll(PDO::FETCH_OBJ);
                
                        if(sizeof($row) != 0){
                            $errors["email"] = "Email is already exists!";   
                        }
                
                    }catch (PDOException $e) {
                        echo 'Connection failed: ' . $e->getMessage();
                    }
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
            $room = $_POST['room'];
            $ext = $_POST['ext'];
            $image = $_FILES['image'];


            try {
                $image_name = $image['name'];
                $tmp_name = $image['tmp_name'];
                $image_size = $image['size'];
                $img_ext = pathinfo($image_name)["extension"];
                $extensions = ['png', 'jpg', 'jepg'];

                if(empty($image_name) or $image_name==""){

                    $errors['image'] = "Image is required!";

                }elseif(!in_array($img_ext, $extensions)){

                    $errors['image'] = "Invalid image type!";

                }else{
                    move_uploaded_file($tmp_name, "../images/".$image_name);
                }
                
            }catch (Exception $e) {
                    echo $e->getMessage();
            }

            if (empty($room ) or $room=="") {
                $errors["room"] = "Room no. is required!";
            }
        
            if (empty($ext ) or $ext=="") {
                $errors["ext"] = "Ext. is required!";
                }else{
                    try{
                        $select_query = "SELECT * FROM user where `ext`=:ext";
                        $select_stmt = $db->prepare($select_query);
                        $select_stmt->bindParam(':ext', $ext);
                        $result = $select_stmt->execute(); 
                        $row = $select_stmt->fetchAll(PDO::FETCH_OBJ);
                        
                        if(sizeof($row) != 0){
                            $errors["ext"] = "Ext. is already exists!";   
                        }
                
                    }catch (PDOException $e) {
                        echo 'Connection failed: ' . $e->getMessage();
                    }
            }

            if(sizeof($errors)>0){
                $status['fail'] = "Can't Add The User, Check The Error!";   
                $status = json_encode($status);
                $errors = json_encode($errors);
                $data = json_encode($_POST);
                header("Location:./adduser.php?errors={$errors}&data={$data}&status={$status}");
            }
        
      }
    

  if(sizeof($errors)==0){

        $hashed_password = password_hash($password,PASSWORD_DEFAULT);
        try {

            $insert_query = "insert into user(`username`, `password`, `email`, `room`,`profile_pic`, `ext`) values(:username, :userpassword, :useremail, :room, :userimage, :ext)";
            $insert_stmt = $db->prepare($insert_query );
            $insert_stmt->bindParam(":username",$username);
            $insert_stmt->bindParam(":userpassword",$hashed_password);
            $insert_stmt->bindParam(":useremail",$email);
            $insert_stmt->bindParam(":room",$room);
            $insert_stmt->bindParam(":userimage",$image_name);
            $insert_stmt->bindParam(":ext",$ext);
            $insert_stmt->execute();

            header("Location:./list_users.php");
        }catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    } 

?>