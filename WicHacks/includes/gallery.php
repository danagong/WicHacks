
<?php // retrieving tags
$secondsql = "SELECT * FROM tags;";
$thetags = exec_sql_query($db, $secondsql, $params = array())->fetchAll();
?>

<form id="searchTags" method="post" action="gallery.php" class="searchform"> Search by:
  <select name="tags">
    <option value="all">All</option>
    <?php foreach ($thetags as $tag) {
      echo ("<option value=\"" . $tag['id'] . "\">" . $tag['tag'] . "</option>");
    } ?>
  </select>
  <button name="search" type="submit">Search</button>
</form>
<div class = "gallerybox">
<?php


if (isset($_POST["search"]) && $_POST['tags'] != "all") {
  $tag_id = $_POST['tags'];
  $sql = "SELECT * FROM images LEFT OUTER JOIN image_tags WHERE image_tags.tag_id = :tag_id AND image_tags.image_id = images.id;";
  $images = exec_sql_query($db, $sql, $params = array(':tag_id' => $tag_id))->fetchAll();
  function print_image_thumb($images)
  {
    echo '<a href="image.php?' . http_build_query(array('id' => $images['image_id'])) . '"><img class="galleryimg" src="/uploads/images/' . $images['image_id'] .'.' .$images['file_ext'] .'" alt="' . htmlspecialchars($images['file_name']) . '"/></a>' . PHP_EOL;
  }


  foreach ($images as $ims) {
    print_image_thumb($ims);
  }
} else {
  $sql = "SELECT * FROM images;";
  $images = exec_sql_query($db, $sql, $params = array())->fetchAll();
  function print_image($images)
  {
    echo '<a href="image.php?' . http_build_query(array('id' => $images['id'])) . '"><img class="galleryimg" src="/uploads/images/' . $images['id'] .'.'. $images['file_ext'] .' " alt="' . htmlspecialchars($images['file_name']) . '"/></a>' . PHP_EOL;
  }
  foreach ($images as $ims) {
    print_image($ims);
  }
}

?>

</div>
