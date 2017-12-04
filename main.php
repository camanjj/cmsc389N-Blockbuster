<?php

require_once "php/support.php";
require_once "php/library.php";

session_start();

$libraries = Library::fetchUsersLibraries((int)$_SESSION["userId"]);

$html = "<br><br><div class=\"card-deck\">";
$color = false;

foreach ($libraries as $lib) {

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
    <div class="card-title">
    <h4>$lib->name</h4>
    <a href="addMedia.php?libraryId={$lib->id}" class="card-link">Add Movie/Show</a>
    <a href="editLibrary.php?libraryId={$lib->id}" class="card-link">Edit Library</a>
    </div>
    <!--<h6 class="card-subtitle mb-2 text-muted">Card subtitle</h6>-->
    <p class="card-text">$lib->description</p>

    $items
  </div>
</div>

CARD;
}

$html .= "</div>";



echo generatePage($html);
