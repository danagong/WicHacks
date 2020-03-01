<?php
// INCLUDE ON EVERY TOP-LEVEL PAGE!
include("includes/init.php");
$messages = array();
const MAX_FILE_SIZE = 10000000;

if (isset($_POST["submitit"]) && is_user_logged_in()) {

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
  $delete_id = $_POST['delete'];
  $sql = "DELETE FROM images WHERE id= :id";
  $params = array(
    ':id' => $delete_id
  );
  $image_path = "uploads/images/" . $delete_id . ".jpg";
  unlink($image_path);
  $delete = exec_sql_query($db, $sql, $params);
}

?>

  <!-- TODO: This should be your main page for your site. -->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <link rel="stylesheet" type="text/css" href="styles/all.css"
  media="all" />
  <script src= "javascript.js" ></script>
  <title>Home</title>
</head>

<body>
<div class="bodycontainer">
<?php include ("includes/header.php");?>
  <h1 class="titleheader"> Welcome!</h1>
<p class="homeparagraph">

<strong> Feel free to explore our environments, and sign in to track your progress!</strong>
</p>

<img class = "globe" src = "images/globe.png">
<span></span>
<div class= "goals">
  Our Goals
</div>

</div>
<div class = "information">
  <article> Empathy
    <div class = "info">Our educational environments address diverse learning preferences that feature integrated and thematic curriculum.</div>
  </article>

  <article> Interactivity
  <div class= "info"> How can we encorporate messages of empathy, respect, and engagement in active tools of learning?  The internet and technology opens the world and beyond to creativity and imagination
</div>
  </article>

  <article> Accessibility</article>
  <article> Awareness</article>
  <article> Creativity</article>
  <article> Experience</article>
</body>
</div>
</html>
