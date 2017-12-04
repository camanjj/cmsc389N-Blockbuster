<?php

require_once "user.php";
require_once "library.php";
require_once "media.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // handle post request

    $action = $_POST["action"];

    if ($action === "register") {
        // attempt to register a new user
        $id = User::register($_POST["username"], $_POST["password"]);
        if (is_null($id)) {
            http_response_code(400);
            echo "";
        } else {
            session_start();
            echo $id;
            $_SESSION["userId"] = $id;
            $_SESSION["username"] = $_POST["username"];
            http_response_code(200);
            echo $id;
        }

    } else if ($action === "login") {
        $id = User::login($_POST["username"], $_POST["password"]);
        if (is_null($id)) {
            http_response_code(400);
            echo "baf";
        } else {
            session_start();

            $_SESSION["userId"] = $id;
            $_SESSION["username"] = $_POST["username"];
            http_response_code(200);

        }
    } else if ($action == "logout") { // not needed, handled in logout.php
        session_destroy();
        http_response_code(200);
    } else if ($action === "addMedia") {
        $mediaId = Media::createNew((string)$_POST["name"], "", "", (string)$_POST["poster"], (string)$_POST["imdbId"]);

        if (is_null($mediaId)) {
            http_response_code(400);
            echo "bad params";
        } else {
            Library::addMediaToLibrary($mediaId, (int)$_POST["libraryId"]);
            http_response_code(200);
            echo "";
        }
    } else if ($action === "editLibrary") {
        $lib = Library::getLibraryById($_POST["libId"]);
        if (isset($_POST["name"])) {
            $lib->name = $_POST["name"];
        }

        if (isset($_POST["description"])) {
            $lib->description = $_POST["desciption"];
        }

        if (isset($_POST["is_shared"])) {
            $lib->is_shared = $_POST["is_shared"];
        }

        $lib->updateLibrary();
        http_response_code(200);


    }


    die();

} else {
    // handle get request

    $action = $_GET["action"];

    if ($action == "libraries") {
        $userId = $_SESSION["userId"];
        echo json_encode(Library::fetchUsersLibraries($userId));
    } elseif ($action === "users") {

        $users = User::findUser($_GET["term"]);
        http_response_code(200);
        if (!$users) {
            echo "";
        } else {
            echo json_encode($users);
        }

    }

    die;
}