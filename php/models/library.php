<?php

require_once "../dbLogin.php";

class Library implements JsonSerializable {

    public $userId;
    public $name;
    public $description;
    public $is_shared;

    public $media; // array of media

    public function __construct(int $userId, string $name, string $desc, bool $is_shared) {
        $this->userId = $userId;
        $this->name = $name;
        $this->description = $desc;
        $this->is_shared = $is_shared;
    }

    static function fetchUsersLibraries(int $userId): ?array {

        global $db_connection;

        // get all of the libraries for the user
        $result = $db_connection->query("select * form Library where user_id=$userId");
        $libraries = [];
        while($row = mysqli_fetch_array($result)) {
            array_push($libraries, new Library($userId, $row["name"], $row["description"], $row["is_shared"]));
        }

        $result->free();

        // for every library, fetch all of the media items
        foreach ($libraries as $lib) {
            $result = $db_connection->query("select * form Library where user_id=$userId");
            $media = [];
            while($row = mysqli_fetch_array($result)) {
                // TODO: create the media items for the library
            }
        }

        return $libraries;

    }

    public function jsonSerialize() {
        return (object) get_object_vars($this);
    }
}