<?php

include_once("connect.php");

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];
    $statement = $conn->prepare("DELETE FROM user WHERE User_ID = ?");
    $statement->execute([$user_id]);

    header("Location: list.php");
    exit;
} else {
    echo "Invalid request!";
    exit;
}

?>
