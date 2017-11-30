<?php
declare(strict_types=1);
require_once "../dbLogin.php";
require_once "media.php";

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
                array_push($media, new Media($row["name"], $row["genre"], $row["year"], $row["poster"]));
            }
            $lib->media = $media;
        }

        $result->free();

        // return all the libraries with all of the media for them
        return $libraries;

    }

    static function createDefaultLibraries(int $userId)
    {

        global $db_connection;
        $names = ["Watch Again", "Current", "Public"];

        foreach ($names as $name) {
            $desc = "";
            $result = $db_connection->query("insert into Library (user_id, name, description, is_shared) values ($userId, $name, $desc, false)");
            $result->free();
        }

    }
    
    public function jsonSerialize() {
        return (object) get_object_vars($this);
    }
}