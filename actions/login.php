<?php
include('connect.php');
session_start();

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the required POST keys exist
if(isset($_POST['username'], $_POST['mobile'], $_POST['password'], $_POST['std'])){
    $username = mysqli_real_escape_string($con, $_POST['username']);
    $mobile = mysqli_real_escape_string($con, $_POST['mobile']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $std = mysqli_real_escape_string($con, $_POST['std']);

    // Use prepared statement to prevent SQL injection
    $stmt = mysqli_prepare($con, "SELECT * FROM `userdata` WHERE username = ? AND mobile = ? AND password = ? AND standard = ?");
    mysqli_stmt_bind_param($stmt, "ssss", $username, $mobile, $password, $std);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if ($result) {
        if(mysqli_num_rows($result) > 0){
            $sql = "SELECT username, photo, vote, id FROM `userdata` WHERE standard = 'group'";
            $resultgroup = mysqli_query($con, $sql);

            if($resultgroup && mysqli_num_rows($resultgroup) > 0){
                $groups = mysqli_fetch_all($resultgroup, MYSQLI_ASSOC);
                $_SESSION['groups'] = $groups;
            }

            $data = mysqli_fetch_array($result);
            $_SESSION["id"] = $data['id'];
            $_SESSION["status"] = $data['status'];
            $_SESSION["data"] = $data;

            echo '<script>
                window.location= "../partials/dashboard.php";
                </script>';
                
        } else {
            echo '<script>
                alert("Invalid credentials");
                window.location= "../";
                </script>';
        }
    } else {
        // Handle database query error
        echo 'Error: ' . mysqli_error($con);
    }

    mysqli_close($con);
} else {
    echo '<script>
        alert("Invalid form submission");
        </script>';
}
?>
