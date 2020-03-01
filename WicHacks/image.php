<?php
include("includes/init.php");
// Find image by ID
if (isset($_GET['id'])) {
  $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
  $sql = "SELECT * FROM images WHERE id = :id;";
  $params = array(
    ':id' => $id
  );
  $result = exec_sql_query($db, $sql, $params);
  if ($result) {
    // The query was successful, let's get the records.
    $images = $result->fetchAll();
    if (count($images) > 0) {
      $image = $images[0];
    }
  }
}

// Add tags to photo
if (isset($_POST['add'])) {
  if ($_POST['new_tag'] != "") {
    $tag = ucfirst($_POST['new_tag']);
    $tag = filter_var($tag, FILTER_SANITIZE_STRING);
    $firstsql = "SELECT * FROM tags;";
    $tags_array2 = exec_sql_query($db, $firstsql, $params = array())->fetchAll();
    $alreadytag = false;
    $secondsql = "SELECT tags.tag FROM tags LEFT OUTER JOIN image_tags WHERE image_tags.image_id = :id AND image_tags.tag_id = tags.id;";
    $tags_array = exec_sql_query($db, $secondsql, $params = array(':id' => $id))->fetchAll();
    foreach ($tags_array as $teg) {
      if ($tag == $teg['tag']) {
        $alreadytag = true;
      }
    }
    // the photo already has this tag
    if ($alreadytag) {
      //do nothing
    } else {
      $alreadytag_table = false;
      foreach ($tags_array2 as $teg) {
        if ($tag == $teg['tag']) {
          $alreadytag_table = true;
        }
      }
      // tag is already in the table
      if ($alreadytag_table) {
        $teggy = "SELECT * FROM tags WHERE tag=:tag;";
        $tag_records = exec_sql_query($db, $teggy, $params = array(':tag' => $tag))->fetchAll();
        $tag_records = $tag_records[0];
      }
      //tag is not already in table
      else {
        $sql_init = "INSERT INTO tags(tag) VALUES (:tag);";
        $query = exec_sql_query($db, $sql_init, $params = array(':tag' => $tag));
        $sql_final = "SELECT * FROM tags WHERE tag=:tag;";
        $tag_records = exec_sql_query($db, $sql_final, $params = array(':tag' => $tag))->fetchAll();
        $tag_records = $tag_records[0];
      }
      // add tag to image_tags
      if ($tag_records) {
        $sql_insert = "INSERT INTO image_tags(tag_id,image_id) VALUES (:tag_id, :image_id);";
        $query2 = exec_sql_query($db, $sql_insert, $params = array(':tag_id' => $tag_records['id'], ':image_id' => $id))->fetchAll();
      }
    }
  }
}

// Delete tags if it's the user who uploaded the image
if (isset($_REQUEST['deletetag'])) {
  $tagtodelete = $_REQUEST['tags'];
  $sql = "DELETE FROM image_tags WHERE image_id=:image_id AND tag_id = :tag_id;";
  $query = exec_sql_query($db, $sql, $params = array(':image_id' => $id, ':tag_id' => $tagtodelete));
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="styles/all.css" type="text/css" rel="stylesheet" />
  <title>Home</title>
</head>

<body>
  <?php include("includes/header.php"); ?>
  <div id="content-wrap">
    <form id="goback" action="gallery.php" method="post">
      <div>
        <button id="return" name="return" type="submit">Go Back </button>
      </div>
    </form>

    <figure>
    <?php echo '<img src="uploads/images/' . $image['id'].'.' . $image['file_ext'] .
      '" alt=" ' . htmlspecialchars($image['file_name']) . '" class="galleryimg" />'; ?>
    </figure>

    <blockquote>
      <p>Description: <?php echo htmlspecialchars($image['description']); ?></p>
      <?php
      $fourthsql = "SELECT tags.tag FROM tags LEFT OUTER JOIN image_tags WHERE image_tags.image_id = :id AND image_tags.tag_id = tags.id;";
      $tags_array_image = exec_sql_query($db, $fourthsql, $params = array(':id' => $id))->fetchAll();
      echo ("Tags: ");
      foreach ($tags_array_image as $t) {
        echo $t['tag'] . " , ";
      }
      ?>
    </blockquote>
    <div id="addTags">
      <form id="addNewTags" method="post" action="<?php echo "image.php?id=" . $image['id'] ?>">Add a New Tag:
        <input type='text' placeholder='Add a Tag' name='new_tag'>
        <button name="add" type="submit">Add the Tag</button>
      </form>
    </div>
    <?php if ($current_user['id'] == $image['user_id']) { ?>
      <div>
        <form id="deleteTags" action="<?php echo "image.php?id=" . $image['id'] ?>" method="post">
          Delete A Tag:
          <select name="tags">
            <?php
            $sql5 = "SELECT * FROM tags LEFT OUTER JOIN image_tags WHERE image_tags.image_id = :id AND image_tags.tag_id = tags.id;";
            $tags_array = exec_sql_query($db, $sql5, $params = array(':id' => $id))->fetchAll();
            foreach ($tags_array as $teg) {
              echo ("<option value=\"" . $teg['tag_id'] . "\">" . $teg['tag'] . "</option>");
            } ?>
          </select>
          <button name="deletetag" type="submit">Delete the Tag</button>

        </form>
      </div>
      <div>
        <form id="deleteImage" action="gallery.php" method="post">
          <div>
            <button id="delete" name="delete" type="delete" value=<?php echo $image['id']
                                                                  ?>>Delete the Picture</button>
          </div>
        </form>
      </div>
    <?php } ?>


<strong>All images by Annie Fu</strong>
  </div>
</body>

</html>
