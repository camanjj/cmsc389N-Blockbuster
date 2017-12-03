<?

declare(strict_types=1);
require_once "dbLogin.php";
require_once "library.php";

class User
{

    public $username;

    static function login(string $username, string $password): int
    {
        // check database
        global $db_connection;

        $password = password_hash($password, PASSWORD_DEFAULT);

        $result = $db_connection->query("select * from User where username=$username and password=$password");
        $user_row = mysqli_fetch_row($result);

        if (mysqli_num_rows($result) != 1) {
            return -1;
        }

        $result->free();

        return $user_row[0];
    }

    static function register(string $username, string $password): ?int
    {
        // check if username was taken, if not create the user
        global $db_connection;

        // check if the username was taken
        $query = $db_connection->query("select * from User where username='$username'");
        if ($query->num_rows > 0) {
            // username already taken
            return null;
        }

        // hash the password
        $password = password_hash($password, PASSWORD_DEFAULT);

        $result = $db_connection->query("INSERT INTO User (username, password) VALUES ('$username', '$password')");
        if (!$result) {
            trigger_error('Invalid query: ' . $db_connection->error);
            return null;
        }


        // get the user id
        $result = $db_connection->query("select * from User where username='$username'");

        $user_row = $result->fetch_row()[0];
//        var_dump($user_row);


        // create the default libraries for the user
        Library::createDefaultLibraries((int)$user_row);

        return (int)$user_row;
    }

    static function findUser(string $term) : ?array {

        global $db_connection;

        $result = $db_connection->query("select username from User where username LIKE '%$term%'");
        if (!$result) {
            trigger_error('Invalid query: ' . $db_connection->error);
            return null;
        }

        $users = [];
        while($row = $result->fetch_assoc()) {
            array_push($users, $row["username"]);
        }

        return $users;
    }

    static function getUserId(string $username) : ?int {

        // check database
        global $db_connection;

        $result = $db_connection->query("select * from User where username='$username'");
        $user_row = mysqli_fetch_row($result);

        if (mysqli_num_rows($result) != 1) {
            return null;
        }

        $result->free();

        return (int)$user_row[0];

    }

}