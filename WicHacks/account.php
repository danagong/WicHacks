<?php
// INCLUDE ON EVERY TOP-LEVEL PAGE!
// I REFERENCED LAB 8 CODE FROM 2300 FOR THIS!
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
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <link rel="stylesheet" type="text/css" href="styles/all.css" media="all" />
  <title>My Account</title>
</head>

<body>
<div class="bodycontainer">
<?php include ("includes/header.php");
include ("includes/signout.php");

if (is_user_logged_in()) { ?>
<h2> Your Images </h2>

<strong>All images by Annie Fu</strong>
      <div class="loginform">
        <?php
        $records = exec_sql_query(
          $db,
          "SELECT * FROM images WHERE user_id = :user_id;",
          array(':user_id' => $current_user['id'])
          )->fetchAll();
        if (count($records) > 0) {
          foreach($records as $record){
            echo "<img src=\"uploads/images/" . $record["id"] . "." . $record["file_ext"] . "\" class=\"galleryimg\" alt=\"". $record["file_name"] . "\">" . " -  ". htmlspecialchars($record["description"]) ;
          }
        }

        else {
          echo '<p><strong>You have not uploaded any images yet. What are you waiting for?</strong></p>';
        }

        ?>

      </div>
      <h2>Upload a Crazy Picture!</h2>
      <form id="uploadFile" action="account.php" method="post" enctype="multipart/form-data">
        <div>
          <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo MAX_FILE_SIZE; ?>" />
          <label for="image_file">Your Crazy Picture: </label>
          <input id="image_file" type="file" name="image_file">
        </div>
        <div>
          <label for="image_desc">Description: </label>
          <textarea id="image_desc" name="description" cols="40" rows="5"></textarea>
        </div>
        <div>
          <button name="submitit" type="submit">Upload</button>
        </div>
      </form>
      <?php
      }
      ?>


</div>
</body>
</html>
