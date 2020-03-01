<?php
// INCLUDE ON EVERY TOP-LEVEL PAGE!

// I REFERENCED LAB 8 CODE FROM 2300 FOR THIS

include("includes/init.php");
$messages = array();
const MAX_FILE_SIZE = 10000000;

if (isset($_POST["submit"]) && is_user_logged_in()) {

  $image_file = $_FILES["image_file"];
  $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);

  if ($_FILES["image_file"]["error"] == UPLOAD_ERR_OK) {
    $file_name = basename($image_file["name"]);
    $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    $user_id = $current_user["id"];
    $sql = "INSERT INTO images (user_id, file_name, file_ext, description) VALUES (:user_id, :file_name, :file_ext, :description)";
    $params = array(
      ':user_id' => $user_id,
      ':file_name' => $file_name,
      ':file_ext' => $file_ext,
      ':description' => $description
    );

    $result = exec_sql_query($db, $sql, $params);
    if ($result) {
      $new_path = "uploads/images/" . ($db->lastInsertId("id")) . "." . $file_ext;
      move_uploaded_file($_FILES["image_file"]["tmp_name"], $new_path);
    }
  }
}

if (isset($_POST["delete"]) && is_user_logged_in()) {
  $deletedimgid = $_POST['delete'];
  $sql = "DELETE FROM images WHERE id= :id";
  $secondtablesql = "DELETE FROM image_tags WHERE image_id= :id";
  $params = array(
    ':id' => $deletedimgid
  );
  $image_path = "uploads/images/" . $deletedimgid. "." . $deletedimgid["file_ext"];
  unlink($image_path);
  $delete = exec_sql_query($db, $sql, $params);
  $delete2 = exec_sql_query($db, $secondtablesql, $params);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <link rel="stylesheet" type="text/css" href="styles/all.css" media="all" />
  <script src= "javascript.js" ></script>
  <title>The Gallery</title>
</head>

<body>
<div class="bodycontainer">
<?php include ("includes/header.php");
 ?>

 <div class = "skeleton">

 <div class="container">
  <div class="content">
    <a href="images/skeleton.png">
      <div class="content-overlay"></div>
      <img class="content-image" src="images/skeleton2.png">
      <div class="content-details fadeIn-bottom">
        <h3 class="content-title">A look inside the human body!</h3>
        <p class="content-text">An exploration of who we are! Our bones, our hearts, and so much more!
        </p>
      </div>
    </a>
  </div>
</div>




</div>
</body>
</html>
