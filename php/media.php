<?php

declare(strict_types=1);
require_once("dbLogin.php");

class Media implements JsonSerializable {
    private $name, $id;
    protected $genre, $year, $poster;
    public $imdbId;

    public function __construct(string $name, string $genre, string $year, string $poster, string $imdbId)
    {
        $this->name = $name;        // Notice this->name (no $ for name)
        $this->genre = $genre;  // We need $this-> to refer to data members
        $this->year = $year;
        $this->poster = $poster;
        $this->imdbId = $imdbId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSection(): string
    {
        return $this->genre;
    }

    public function getYear(): string
    {
        return $this->year;
    }

    public function getPoster(): string
    {
        return $this->poster;
    }

    public static function createNew(string $name, string $genre, string $year, string $poster, string $imdbId): ?int
    {
        global $db_connection;
        $query = "INSERT INTO Media (name, genre, year, poster, imdb_id) VALUES ('$name', '$genre', '$year', '$poster', '$imdbId')";

        /* Create Media table */
        if (!$db_connection->query($query)) {
            trigger_error('Invalid query: ' . $db_connection->error);
            return null;
        }

        $result = $db_connection->query("SELECT * FROM Media WHERE imdb_id='$imdbId'");

        return (int)$result->fetch_row()[0];
    }

    public static function find($name)
    {
        global $db_connection;
        $query = "SELECT * FROM Media WHERE name=$name";

        /* Create Media table */
        $result = $db_connection->query($query);
        if (!$result) {
            die("CREATE Media TABLE failed: " . $db_connection->error);
        } else {
            return $result;
        }
    }

    public function jsonSerialize()
    {
        return (object) get_object_vars($this);
    }
}
