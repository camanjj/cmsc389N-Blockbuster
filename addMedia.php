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

    let fakeResult = JSON.parse("{\"Search\":[{\"Title\":\"House of Cards\",\"Year\":\"2013–\",\"imdbID\":\"tt1856010\",\"Type\":\"series\",\"Poster\":\"https://images-na.ssl-images-amazon.com/images/M/MV5BMjM3ODMyMjc3MV5BMl5BanBnXkFtZTgwNDgzNDc5NzE@._V1_SX300.jpg\"},{\"Title\":\"House of Wax\",\"Year\":\"2005\",\"imdbID\":\"tt0397065\",\"Type\":\"movie\",\"Poster\":\"https://images-na.ssl-images-amazon.com/images/M/MV5BNDA4Nzg1NjQ2NV5BMl5BanBnXkFtZTcwMDYwNTgyMQ@@._V1_SX300.jpg\"},{\"Title\":\"House of Flying Daggers\",\"Year\":\"2004\",\"imdbID\":\"tt0385004\",\"Type\":\"movie\",\"Poster\":\"https://images-na.ssl-images-amazon.com/images/M/MV5BMzg4MDE0NzIwNl5BMl5BanBnXkFtZTcwMDI2NDcyMQ@@._V1_SX300.jpg\"},{\"Title\":\"House of 1000 Corpses\",\"Year\":\"2003\",\"imdbID\":\"tt0251736\",\"Type\":\"movie\",\"Poster\":\"https://images-na.ssl-images-amazon.com/images/M/MV5BNjUyNjU0NDE0OV5BMl5BanBnXkFtZTYwNzcwMzg3._V1_SX300.jpg\"},{\"Title\":\"House at the End of the Street\",\"Year\":\"2012\",\"imdbID\":\"tt1582507\",\"Type\":\"movie\",\"Poster\":\"https://images-na.ssl-images-amazon.com/images/M/MV5BMjIxNTUwNTU4N15BMl5BanBnXkFtZTcwNTE0MTI3Nw@@._V1_SX300.jpg\"},{\"Title\":\"House of Sand and Fog\",\"Year\":\"2003\",\"imdbID\":\"tt0315983\",\"Type\":\"movie\",\"Poster\":\"https://images-na.ssl-images-amazon.com/images/M/MV5BMTIzMjQ5NzM2M15BMl5BanBnXkFtZTYwMzU2NDY3._V1_SX300.jpg\"},{\"Title\":\"The House of the Devil\",\"Year\":\"2009\",\"imdbID\":\"tt1172994\",\"Type\":\"movie\",\"Poster\":\"https://images-na.ssl-images-amazon.com/images/M/MV5BMTAxMDAxODg5ODReQTJeQWpwZ15BbWU3MDI5ODYxODI@._V1_SX300.jpg\"},{\"Title\":\"House of the Dead\",\"Year\":\"2003\",\"imdbID\":\"tt0317676\",\"Type\":\"movie\",\"Poster\":\"https://images-na.ssl-images-amazon.com/images/M/MV5BMTQwMTU1MzIyNV5BMl5BanBnXkFtZTYwODM4NTc2._V1_SX300.jpg\"},{\"Title\":\"House of Lies\",\"Year\":\"2012–2016\",\"imdbID\":\"tt1797404\",\"Type\":\"series\",\"Poster\":\"https://images-na.ssl-images-amazon.com/images/M/MV5BMjM2NDI0MTc5MV5BMl5BanBnXkFtZTgwMjEzNTQxODE@._V1_SX300.jpg\"},{\"Title\":\"Man of the House\",\"Year\":\"2005\",\"imdbID\":\"tt0331933\",\"Type\":\"movie\",\"Poster\":\"https://images-na.ssl-images-amazon.com/images/M/MV5BODY3Nzg2NDQ5Ml5BMl5BanBnXkFtZTcwOTQ5MDgyMQ@@._V1_SX300.jpg\"}],\"totalResults\":\"1187\",\"Response\":\"True\"}")
    console.log(fakeResult);
    let selectedMedia;
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
            response(fakeResult.Search);
//            xhrz = jQuery.get(`http://www.omdbapi.com/?apikey=412dcdca&s=`+term, function(data){
//                console.log(data);
//                if (data.hasOwnProperty("Search")) {
//                    response(data.Search);
//                } else {
//                    response([]);
//                }
//
//            });
        },
        renderItem: function (item, search) {
            search = search.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
            let re = new RegExp("(" + search.split(' ').join('|') + ")", "gi");
            return '<div class="autocomplete-suggestion" data-val="' + item.Title + '">' + item.Title.replace(re, "<b>$1</b>") + '</div>';
        },
        onSelect: function(event, term, item) {
            selectedMedia = fakeResult.Search.filter((e) => e.Title === term)[0];

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

