<?php
  $servername = "localhost";
  $user = "cmsc389n";
  $password = "blockbuster";

	/* Connecting to the database */
	$db_connection = new mysqli($servername, $user, $password);
	if ($db_connection->connect_error) {
		die($db_connection->connect_error);
	}

	/* Query */
	$query = "CREATE DATABASE blockbuster";

	/* Create database blockbuster */
	$result = $db_connection->query($query);
	if (!$result) {
		die("CREATION failed: " . $db_connection->error);
	}

  $query = "USE blockbuster";

	/* Switch to blockbuster */
	$result = $db_connection->query($query);
	if (!$result) {
		die("USING failed: " . $db_connection->error);
	}

  $query = "CREATE TABLE User (id int NOT NULL AUTO_INCREMENT, username varchar(255), password varchar(255), PRIMARY KEY (id))";

	/* Create User table */
	$result = $db_connection->query($query);
	if (!$result) {
		die("CREATE User TABLE failed: " . $db_connection->error);
	}

  $query = "CREATE TABLE Media (id int NOT NULL AUTO_INCREMENT, name varchar(255), genre varchar(60), year int, poster text, PRIMARY KEY (id))";

	/* Create Media table */
	$result = $db_connection->query($query);
	if (!$result) {
		die("CREATE Media TABLE failed: " . $db_connection->error);
	}

  $query = "CREATE TABLE Library (id int NOT NULL AUTO_INCREMENT, name varchar(150), description text, user_id int, is_shared bool, PRIMARY KEY (id), FOREIGN KEY (user_id) REFERENCES User(id))";

  /* Create Library table */
	$result = $db_connection->query($query);
	if (!$result) {
		die("CREATE Library TABLE failed: " . $db_connection->error);
	}

  $query = "CREATE TABLE Library_Media (id int NOT NULL AUTO_INCREMENT, media_id int, library_id int, PRIMARY KEY (id), FOREIGN KEY (media_id) REFERENCES Media(id), FOREIGN KEY (library_id) REFERENCES Library(id))";

  /* Create Library_Media table */
	$result = $db_connection->query($query);
	if (!$result) {
		die("CREATE Library_Media TABLE failed: " . $db_connection->error);
	}


	/* Closing connection */
	$db_connection->close();
?>
