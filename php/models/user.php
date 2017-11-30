<?

declare(strict_types=1);
require_once "../dbLogin.php";
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

    static function register(string $username, string $password): int
    {
        // check if username was taken, if not create the user
        global $db_connection;

        // check if the username was taken
        $query = $db_connection->query("select * from User where username=$username");
        $count = mysqli_num_rows($query);

        if ($count > 0) {
            // username already taken
            return -1;
        }

        // hash the password
        $password = password_hash($password, PASSWORD_DEFAULT);

        $result = $db_connection->query("insert into User (username, password) values ($username, $password)");
        if (mysqli_num_rows($result) != 1) {
            return -1;
        }

        $user_row = mysqli_fetch_row($result);

        // create the default libraries for the user
        Library::createDefaultLibraries($user_row["id"]);

        $result->free();

        return $user_row[0];
    }

}