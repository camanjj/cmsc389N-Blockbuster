<?php


/* Update based on your database and account info */
$host = "localhost:3306";
$user = "cmsc389n";
$password = "blockbuster";
$database = "blockbuster";

$db_connection = new mysqli($host, $user, $password, $database);
if ($db_connection->connect_error) {
    die($db_connection->connect_error);
} else {
//    echo "Connection to database established<br><br>";
}

$query = "CREATE TABLE User (id INT NOT NULL AUTO_INCREMENT, username VARCHAR(255), password VARCHAR(255), PRIMARY KEY (id))";

/* Create User table */
$result = $db_connection->query($query);
if (!$result) {
    die("CREATE User TABLE failed: " . $db_connection->error);
}

$query = "CREATE TABLE Media (id INT NOT NULL AUTO_INCREMENT, name VARCHAR(255), genre VARCHAR(60), year VARCHAR(4), poster TEXT, PRIMARY KEY (id))";

/* Create Media table */
$result = $db_connection->query($query);
if (!$result) {
    die("CREATE Media TABLE failed: " . $db_connection->error);
}

$query = "CREATE TABLE Library (id INT NOT NULL AUTO_INCREMENT, name VARCHAR(150), description TEXT, user_id INT, is_shared BOOL, PRIMARY KEY (id), FOREIGN KEY (user_id) REFERENCES User(id))";

/* Create Library table */
$result = $db_connection->query($query);
if (!$result) {
    die("CREATE Library TABLE failed: " . $db_connection->error);
}

$query = "CREATE TABLE Library_Media (id INT NOT NULL AUTO_INCREMENT, media_id INT, library_id INT, PRIMARY KEY (id), FOREIGN KEY (media_id) REFERENCES Media(id), FOREIGN KEY (library_id) REFERENCES Library(id))";

/* Create Library_Media table */
$result = $db_connection->query($query);
if (!$result) {
    die("CREATE Library_Media TABLE failed: " . $db_connection->error);
}


/* Closing connection */
$db_connection->close();
?>
