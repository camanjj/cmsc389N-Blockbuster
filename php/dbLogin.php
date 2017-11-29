<?

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