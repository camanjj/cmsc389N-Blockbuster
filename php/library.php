<?php
declare(strict_types=1);
require_once "dbLogin.php";
require_once "media.php";

class Library implements JsonSerializable {

    public $userId;
    public $id;
    public $name;
    public $description;
    public $is_shared;

    public $media; // array of media

    public function __construct(int $id, int $userId, string $name, string $desc, bool $is_shared) {
        $this->id = $id;
        $this->userId = $userId;
        $this->name = $name;
        $this->description = $desc;
        $this->is_shared = $is_shared;
    }

    static function fetchUsersLibraries(int $userId): ?array {

        global $db_connection;

        // get all of the libraries for the user
        $result = $db_connection->query("SELECT * FROM Library WHERE user_id='$userId' GROUP BY id");
        $libraries = [];
        while ($row = $result->fetch_assoc()) {
            array_push($libraries, new Library((int)$row["id"], $userId, $row["name"], $row["description"], (bool)$row["is_shared"]));
        }

        $result->free();

        // for every library, fetch all of the media items
        foreach ($libraries as $lib) {
            $result = $db_connection->query("SELECT m.* FROM Library_Media lm, Media m WHERE lm.library_id=$lib->id and m.id=lm.media_id GROUP BY m.imdb_id");
            $media = [];
            while($row = $result->fetch_assoc()) {
                array_push($media, new Media($row["name"], $row["genre"], $row["year"], $row["poster"], $row["imdb_id"]));
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
            $db_connection->query("INSERT INTO Library (user_id, name, description, is_shared) VALUES ('$userId', '$name', '$desc', false)");
        }

    }

    static function getLibraryNamesAndIds(int $userId): ?array {

        global $db_connection;

        $result = $db_connection->query("SELECT id, name FROM Library WHERE user_id=$userId");
        $libraries = [];
        while ($row = $result->fetch_assoc()) {
            array_push($libraries, $row);
        }

        $result->free();

        return $libraries;
    }

    static function addMediaToLibrary(int $mediaId, int $libraryId) {
        global $db_connection;

        if(!$db_connection->query("insert into Library_Media (media_id, library_id) values ($mediaId, $libraryId)")) {
            trigger_error('Invalid query: ' . $db_connection->error);
        }

    }

    public function jsonSerialize() {
        return (object) get_object_vars($this);
    }
}