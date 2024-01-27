<?php
session_start();
include('connect.php');

// Check if the user is logged in
if(!isset($_SESSION['id'])) {
    echo '<script>
    alert("You are not logged in.");
    window.location="../partials/login.php";
    </script>';
    exit;
}

$uid = $_SESSION['id'];

// Check if the user has already voted
$get_user_vote_status = mysqli_query($con, "SELECT status FROM `userdata` WHERE id='$uid'");
$user_vote_status = mysqli_fetch_assoc($get_user_vote_status);

if($user_vote_status['status'] == 1) {
    echo '<script>
    alert("You have already voted.");
    window.location="../partials/dashboard.php";
    </script>';
    exit;
}

// If the user has not voted yet, proceed with voting
if(isset($_POST['groupvotes']) && isset($_POST['groupid'])) {
    $votes = $_POST['groupvotes'];
    $totalvotes = $votes + 1;

    $gid = $_POST['groupid'];

    // Update the vote count for the group
    $updatevotes = mysqli_query($con, "UPDATE `userdata` SET vote='$totalvotes' WHERE id='$gid'");

    // Update the user status to indicate that they have voted
    $updatestatus = mysqli_query($con, "UPDATE `userdata` SET status=1 WHERE id='$uid'");

    if($updatevotes && $updatestatus){
        $getgroups = mysqli_query($con, "SELECT username,photo,vote,id FROM `userdata` WHERE standard='group'");

        $groups = mysqli_fetch_all($getgroups, MYSQLI_ASSOC);
        $_SESSION['groups'] = $groups;
        $_SESSION['status'] = 1;

        echo '<script>
        alert("Signature collected Successful");
        window.location="../partials/dashboard.php";
        </script>';
        exit;
    } else {
        echo '<script>
        alert("Technical Error !! Please try again later.");
        window.location="../partials/dashboard.php";
        </script>';
        exit;
    }
} else {
    echo '<script>
    alert("Invalid request. Please try again.");
    window.location="../partials/dashboard.php";
    </script>';
    exit;
}
?>
