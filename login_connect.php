<?php
session_start();
include "config.php";

if(isset($_POST['login'])){
    $u_email = $_POST['useremail'];
    $u_pass = $_POST['userpassword'];

    $result = mysqli_query($con,"SELECT * FROM `tbluser` WHERE Email = '$u_email' AND Password = '$u_pass'");

    if(mysqli_num_rows($result)){
        $_SESSION['loggedin'] = true;
        $_SESSION['useremail'] = $u_email;
        echo "
        
        <script>
            alert('Login Success');
            window.location.href = 'index1.php';
        </script>

        ";
    }
    else{
        echo "
        
        <script>
            alert('Incorrect Email/Password');
            window.location.href = 'login.php';
        </script>

        ";
    }
}

?>