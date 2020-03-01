

<?php
if (is_user_logged_in()) {

      $tologout = htmlspecialchars($_SERVER['PHP_SELF']) . '?' . http_build_query(array('logout' => ''));
      echo '<div class="logout"><a id="lsogout" href="' . $tologout . '">Sign Out: ' . htmlspecialchars($current_user['username']) . '</a></div>';

} else { ?>
        <p class="loginformat">Got an account? Log in!</p>
        <?php include("includes/loginlogout.php");
      }
      ?>
