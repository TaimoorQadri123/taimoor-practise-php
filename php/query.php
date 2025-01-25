<?php
include('dbcon.php');
// session_start();
 $userName = $userEmail = $userPassword = $userConfirmPassword = "" ;
 $userNameErr = $userEmailErr = $userPasswordErr = $userConfirmPasswordErr = "" ;

// register
if(isset($_POST['addUser'])){
    $userName = $_POST['uName'];
    $userEmail = $_POST['uEmail'];
    $userPassword = $_POST['uPassword'];
    $userConfirmPassword = $_POST['uConfirmPassword'];
    if(empty($userName)){
            $userNameErr = "name is required";
    }
    if(empty($userEmail)){
            $userEmailErr = "email is required";          
    }
    else{
        $query = $pdo->prepare("select * from users where email = :userEmail");
        $query->bindParam('userEmail',$userEmail);
        $query->execute();
        $user = $query->fetch(PDO::FETCH_ASSOC);
        if($user){
        $userEmailErr = 'email is already exist';
        }       
    }
    if(empty($userPassword)){
            $userPasswordErr = "password is required" ;
    }
    if(empty($userConfirmPassword)){
                $userConfirmPasswordErr = "confirm password is required";
    }       
    if(empty($userNameErr) && empty($userEmailErr)   && empty($userPasswordErr) && empty($userConfirmPasswordErr) ){
                $query = $pdo->prepare("insert into users (name , email , password) values (:name , :email , :password)");
                $query->bindParam('name',$userName);
                $query->bindParam('email',$userEmail);
                $query->bindParam('password',$userPassword);
                $query->execute();
                echo "<script>alert('regiter successfully');location.assign('regisTer.php')</script>";
    }
}
// login
if(isset($_POST['userLogin'])){
$userEmail = $_POST['uEmail'];
$userPassword = $_POST['uPassword'];
if(empty($userEmail)){
        $userEmailErr = 'email is required';
}
if(empty($userPassword)){
        $userPasswordErr = 'password is required';
}
if(empty($userEmailErr) && empty($userPasswordErr)){
      $query = $pdo->prepare("select * from users where email = :uEmail");
      $query->bindParam('uEmail',$userEmail);
      $query->execute();
      $user = $query->fetch(PDO::FETCH_ASSOC);
      if($user){
                if($userPassword==$user['password']){
                     if($user['role_id']==1){
                                $_SESSION['adminName'] = $user['name'];
                                $_SESSION['adminEmail'] = $user['email'];
                                $_SESSION['adminRoleID'] = $user['role_id'];
                            echo "<script>location.assign('dashmin/index.php')</script>";    
                     }   
                     else if($user['role_id']==2){
                        $_SESSION['userName'] = $user['name'];
                        $_SESSION['userEmail'] = $user['email'];
                        $_SESSION['userRoleID'] = $user['role_id'];
                        echo "<script>location.assign('index.php')</script>";
                     }             
                }
                else{
                        echo "<script>location.assign('login.php?error=invalid password')</script>";         
                }
      }
      else{
        echo "<script>location.assign('login.php?error=user not found')</script>";
      }
}
}

?>