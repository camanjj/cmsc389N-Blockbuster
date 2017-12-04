<?php

require_once "php/support.php";
require_once "php/library.php";

session_start();


$select = "<select id='libraries' class=\"custom-select mx-auto \">";
foreach(Library::getLibraryNamesAndIds($_SESSION["userId"]) as $e) {
    $select .= "<option value='{$e["id"]}'>" . $e["name"] . "</option>";
//    echo implode($e);
}
$select .= "</select>";

$html = <<<BODY

<br />
<div class="mx-auto text-center">
    <h2>Find a show/movie to add to your library</h2>
</div>

<br />
<br />

<div class="offset-lg-3 col-lg-6">
<form id="form">
    <div class="input-group">
        <input id="search" name="search" type="text" class="form-control" placeholder="Search for..." aria-label="Search for...">
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

    let selectedMedia;
    let results;
    let xhrz;
    new autoComplete({
        selector: 'input[name="search"]',
        source: function(term, response){
            if (xhrz !== undefined) {
                try {
                    xhrz.abort();
                } catch (e) {
                    console.log(e)
                }
            }
            if (term.length <= 5) {
                return;
            }
            xhrz = jQuery.get(`http://www.omdbapi.com/?apikey=412dcdca&s=`+term, function(data){
                console.log(data);
                if (data.hasOwnProperty("Search")) {
                    results = data;
                    response(data.Search);
                } else {
                    response([]);
                }

            });
        },
        renderItem: function (item, search) {
            search = search.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
            let re = new RegExp("(" + search.split(' ').join('|') + ")", "gi");
            return '<div class="autocomplete-suggestion" data-val="' + item.Title + '">' + item.Title.replace(re, "<b>$1</b>") + '</div>';
        },
        onSelect: function(event, term, item) {
            selectedMedia = results.Search.filter((e) => e.Title === term)[0];

            document.getElementById("cardImage").src = selectedMedia.Poster;
            document.getElementById("cardTitle").textContent = selectedMedia.Title;
            document.getElementById("cardContent").textContent = "Year: " + selectedMedia.Year;
        }
    });

    
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

