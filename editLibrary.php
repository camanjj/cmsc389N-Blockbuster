<?php
require_once "php/support.php";
require_once "php/library.php";

session_start();

$lib = Library::getLibraryById($_GET["libraryId"]);


$html = <<<BODY

<br />
<div class="mx-auto text-left">
    <h2>Edit {$lib->name} Library Information:</h2>
</div>

<br />
<br />

<div class="offset-lg-3 col-lg-6">
<form id="form">
    <div class="input-group">
        Library name:<input id="libName" name="search" type="text" class="form-control" placeholder="Search for..." >
        <!--<span class="input-group-btn">-->
            <!--<button class="btn btn-secondary" type="button">Search</button>-->
        <!--</span>-->
    </div>
    </form>
</div>
<br />

<div id="mediaCard" class="card mx-auto" style="width: 20rem;">
    <img id="cardImage" class="card-img-top mx-auto" style="max-width: 150px;">
    <div class="card-body">
        <h4 id="cardTitle" class="card-title"></h4>
        <p id="cardContent" class="card-text"></p>
        <!--<a href="#" class="btn btn-primary">Go somewhere</a>-->
    </div>
    $select
</div>
<br/>

<button onclick="addMedia()" type="button" class="btn btn-primary offset-md-4 col-md-4 text-center">Add to Library</button>

<script>

    // handle adding the media item to the selected library
    function addMedia() {
        if (selectedMedia == null) {
            alert("You have not selected a media item");
            return;
        }

        let payload = {
            'action': "addMedia",
            'name': selectedMedia.Title,
            'imdbId': selectedMedia.imdbID,
            'poster': selectedMedia.Poster,
            'libraryId': $('option').filter(':selected').first().val()
        };

        console.log(payload);

        $.post("php/api.php", payload, (data, status, xhr) => {
            console.log(data);
            if (status === "success") {

                alert("Added " + selectedMedia.Title);
                selectedMedia = null;
                document.getElementById("form").reset();
                // clear the card
                $("#cardImage").attr('src', 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABA‌​AACAUwAOw==');
                $("#cardTitle").empty();
                $("#cardContent").empty();

            } else {
                // there was an issue
                alert("Issue saving media")
            }
        });
    }

</script>

BODY;

echo generatePage($html);




?>
