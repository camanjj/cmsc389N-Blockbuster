<?php

require_once "php/support.php";
require_once "php/user.php";
require_once "php/library.php";
session_start();

if (!isset($_GET["username"])) {
    die("no username passed");
}

$username = $_GET["username"];
$userId = User::getUserId($username);
$libraries = Library::fetchUsersLibraries($userId);

$sharedLibraries = [];
foreach ($libraries as $lib) {
    if ($lib->is_shared)
        array_push($sharedLibraries, $lib);
}

$html = "<br><br><div class=\"card-deck\">";
$color = false;
//var_dump($libraries);
foreach ($sharedLibraries as $lib) {

    $color = !$color;
    $style = $color == false ? "bg-dark text-white card-block" : "bg-light";

    $items = "<ul class=\"list-group list-group-flush\">";
    foreach ($lib->media as $media) {
      $items .= "<a href='http://imdb.com/title/{$media->imdbId}' ><li class=\"list-group-item {$style} \">";
      $items .= "<img src={$media->getPoster()} width=\"80\" height=\"100\" /><br>";
      $items .= "{$media->getName()}</li></a>";
    }
    $items .= "</ul>";



    $html .= <<<CARD

<div class="card $style" style="width: 20rem;">
  <div class="card-body">
    <h4 class="card-title">$lib->name</h4>
    <!--<h6 class="card-subtitle mb-2 text-muted">Card subtitle</h6>-->
    <p class="card-text">$lib->description</p>

    $items
  </div>
</div>

CARD;
}

$html .= "</div>";


echo generatePage($html);
