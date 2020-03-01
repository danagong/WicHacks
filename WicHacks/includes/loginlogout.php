<?php
 // Heads up!! I referenced Lecture 17's Code from INFO 2300 for this!!!


 // THOUGH SIGNOUT IS ON ANOTHER TEMPLATE, I NAMED THIS FILE FOR MY OWN CONVENIENCE WHEN BUILDING THE SITE



?>

<?php
foreach ($session_messages as $message) {
  echo "<p><strong>" . htmlspecialchars($message) . "</strong></p>\n";
}
?>

<form id="loginForm" class = "loginformat" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">


    <div class = "form sign-in">
        <label for="username">Username:</label>
        <input id="username" type="text" name="username" />
    </div>
    <div>
        <label for="password">Password:</label>
        <input id="password" type="password" name="password" />
    </div>
    <div>
        <button name="login" type="submit">Log In!</button>
    </div>


</form>
