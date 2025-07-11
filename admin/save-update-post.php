<?php
// include 'config.php';


// if (empty($_FILES['new-image']['name'])) {
//     $file_name = $_POST['old-image'];
// } else {
//     $errors = array();

//     $file_name = $_FILES['new-image']['name'];
//     $file_size = $_FILES['new-image']['size'];
//     $file_tmp = $_FILES['new-image']['tmp_name'];
//     $file_type = $_FILES['new-image']['type'];
//     $file_ext = strtolower(end(explode('.', $file_name)));
//     $extensions = array("jpeg", "jpg", "png");

//     if (in_array($file_ext, $extensions) === false) {
//         $errors[] = "This extension file not allowed, Please choose a JPG or PNG file.";
//     }

//     if ($file_size > 2097152) {
//         $errors[] = "File size must be 2mb or lower.";
//     }

//     if (empty($errors) == true) {
//         move_uploaded_file($file_tmp, "upload/" . $file_name);
//     } else {
//         print_r($errors);
//         die();
//     }
// }

// $title =  $_POST['post_title'];
// $description =  $_POST['postdesc'];
// $category = $_POST['category'];
// $post_id = $_POST['post_id'];
// $sql2 = "UPDATE post SET title = '{$title}', description = '{$description}', category = {$category}, post_img = '{$file_name}' WHERE post_id = '{$post_id}'";

// $result2 = mysqli_query($conn, $sql2) or die("Query Failed!");

// if ($result2) {
//     header("Location: {$hostname}/admin/post.php");
// }


include 'config.php';

if (empty($_FILES['new-image']['name'])) {
    $file_name = $_POST['old-image'];
} else {
    $errors = array();

    $file_name = $_FILES['new-image']['name'];
    $file_size = $_FILES['new-image']['size'];
    $file_tmp = $_FILES['new-image']['tmp_name'];
    $file_ext = strtolower(end(explode('.', $file_name)));
    $extensions = array("jpeg", "jpg", "png");

    if (in_array($file_ext, $extensions) === false) {
        $errors[] = "This extension file not allowed, Please choose a JPG or PNG file.";
    }

    if ($file_size > 2097152) {
        $errors[] = "File size must be 2mb or lower.";
    }

    if (empty($errors) == true) {
        move_uploaded_file($file_tmp, "upload/" . $file_name);
    } else {
        print_r($errors);
        die();
    }
}

$title =  $_POST['post_title'];
$description =  $_POST['postdesc'];
$new_category = $_POST['category'];
$post_id = $_POST['post_id'];

$sql1 = "SELECT category FROM post WHERE post_id = '{$post_id}'";
$result1 = mysqli_query($conn, $sql1) or die("Query Failed: Select old category");
$row = mysqli_fetch_assoc($result1);
$old_category = $row['category'];

$sql2 = "UPDATE post SET title = '{$title}', description = '{$description}', category = {$new_category}, post_img = '{$file_name}' WHERE post_id = '{$post_id}'";
$result2 = mysqli_query($conn, $sql2) or die("Query Failed: Update post");

if ($old_category != $new_category) {
    $sql3 = "UPDATE category SET post = post - 1 WHERE category_id = {$old_category}";
    mysqli_query($conn, $sql3) or die("Query Failed: Decrease old category post count");

    $sql4 = "UPDATE category SET post = post + 1 WHERE category_id = {$new_category}";
    mysqli_query($conn, $sql4) or die("Query Failed: Increase new category post count");
}

header("Location: {$hostname}/admin/post.php");
