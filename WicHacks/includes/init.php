<?php
// vvv DO NOT MODIFY/REMOVE vvv
// check current php version to ensure it meets 2300's requirements
function check_php_version()
{
  if (version_compare(phpversion(), '7.0', '<')) {
    define(VERSION_MESSAGE, "PHP version 7.0 or higher is required for 2300. Make sure you have installed PHP 7 on your computer and have set the correct PHP path in VS Code.");
    echo VERSION_MESSAGE;
    throw VERSION_MESSAGE;
  }
}
check_php_version();
function config_php_errors()
{
  ini_set('display_startup_errors', 1);
  ini_set('display_errors', 0);
  error_reporting(E_ALL);
}
config_php_errors();
// open connection to database
function open_or_init_sqlite_db($db_filename, $init_sql_filename)
{
  if (!file_exists($db_filename)) {
    $db = new PDO('sqlite:' . $db_filename);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if (file_exists($init_sql_filename)) {
      $db_init_sql = file_get_contents($init_sql_filename);
      try {
        $result = $db->exec($db_init_sql);
        if ($result) {
          return $db;
        }
      } catch (PDOException $exception) {
        // If we had an error, then the DB did not initialize properly,
        // so let's delete it!
        unlink($db_filename);
        throw $exception;
      }
    } else {
      unlink($db_filename);
    }
  } else {
    $db = new PDO('sqlite:' . $db_filename);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $db;
  }
  return null;
}
function exec_sql_query($db, $sql, $params = array())
{
  $query = $db->prepare($sql);
  if ($query and $query->execute($params)) {
    return $query;
  }
  return null;
}
// ^^^ DO NOT MODIFY/REMOVE ^^^
// You may place any of your code here.
$db = open_or_init_sqlite_db('secure/gallery.sqlite', 'secure/init.sql');


// I referenced lecture 17's code for help with this code!!!!!!
///THE LOGIN AND LOGOUT

// the duration for the session
define('SESSION_COOKIE_DURATION', 60 * 60 * 1);
$session_messages = array();

function log_in($username, $password)
{
  global $db;
  global $current_user;
  global $session_messages;

  if (isset($username) && isset($password)) {
    // retrieving a record with the given username
    $sql = "SELECT * FROM users WHERE username = :username;";
    $params = array(
      ':username' => $username
    );
    $records = exec_sql_query($db, $sql, $params)->fetchAll();
    if ($records) {
      // this checks there's only one record we're looking at
      $account = $records[0];
      // checks to see if the password matches
      if (password_verify($password, $account['password'])) {
        $session = session_create_id();

        $sql = "INSERT INTO sessions (user_id, session) VALUES (:user_id, :session);";
        $params = array(
          ':user_id' => $account['id'],
          ':session' => $session
        );
        $result = exec_sql_query($db, $sql, $params);
        // if it does match, login works
        if ($result) {
          setcookie("session", $session, time() + SESSION_COOKIE_DURATION);
          $current_user = $account;
          return $current_user;
          // doesn't match, login doesn't work
        } else {
          array_push($session_messages, "We couldn't log you in.");
        }
      } else {
        array_push($session_messages, "Invalid username or password.");
      }
    } else {
      array_push($session_messages, "Invalid username or password.");
    }
  } else {
    array_push($session_messages, "Please enter both a username and password.");
  }
  $current_user = null;
  return null;
}

function find_user($user_id)
{
  global $db;
// getting all the users
  $sql = "SELECT * FROM users WHERE id = :user_id;";
  $params = array(
    ':user_id' => $user_id
  );
  $records = exec_sql_query($db, $sql, $params)->fetchAll();
  if ($records) {
    return $records[0];
  }
  return null;
}


// making sure the session is unique
function find_session($session)
{
  global $db;

  if (isset($session)) {
    $sql = "SELECT * FROM sessions WHERE session = :session;";
    $params = array(
      ':session' => $session
    );
    $records = exec_sql_query($db, $sql, $params)->fetchAll();
    if ($records) {
      return $records[0];
    }
  }
  return null;
}

//generates a new session with a login, new cookie
function session_login()
{
  global $db;
  global $current_user;

  if (isset($_COOKIE["session"])) {
    $session = $_COOKIE["session"];

    $session_record = find_session($session);

    if (isset($session_record)) {
      $current_user = find_user($session_record['user_id']);
      setcookie("session", $session, time() + SESSION_COOKIE_DURATION);
      return $current_user;
    }
  }
  $current_user = null;
  return null;
}

//are they logges in
function is_user_logged_in()
{
  global $current_user;
  return ($current_user != null);
}

// to log out, manually expires session cookie and sets user to null
function log_out()
{
  global $current_user;
  setcookie('session', '', time() - SESSION_COOKIE_DURATION);
  $current_user = null;
}

//trimming gets rid of extraneous characters such as apostrophes
if (isset($_POST['login']) && isset($_POST['username']) && isset($_POST['password'])) {
  $username = trim($_POST['username']);
  $password = trim($_POST['password']);

  log_in($username, $password);
} else {
  session_login();
}

if (isset($current_user) && (isset($_GET['logout']) || isset($_POST['logout']))) {
  log_out();
}

  ?>
