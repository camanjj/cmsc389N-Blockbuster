<?php

function generatePage($body, $title="Example") {
    $page = <<<EOPAGE
<!doctype html>
<html>
    <head> 
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>$title</title>	
        <!--Bootstrap stuff-->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css"
              integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
        <script src="js/jquery-3.2.1.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js"
                integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh"
                crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js"
                integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ"
                crossorigin="anonymous"></script>
                
        <script src="https://rawgit.com/Pixabay/JavaScript-autoComplete/master/auto-complete.min.js" ></script>
        <link rel="stylesheet" type="text/css" href="https://rawgit.com/Pixabay/JavaScript-autoComplete/master/auto-complete.css" />
    </head>
            
    <body>
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="main.php">Blockbuster</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mr-auto">
            <li id="home-item" class="nav-item">
                <a class="nav-link" href="main.php">Home <span class="sr-only">(current)</span></a>
            </li>
            <li id="add-item" class="nav-item">
                <a class="nav-link" href="addMedia.php">Add Media</a>
            </li>
        </ul>
        <form class="form-inline my-2 my-lg-0">
            <input name="userSearch" class="form-control mr-sm-2" type="search" placeholder="Find users">
        </form>
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Logout, {$_SESSION["username"]}</a>
            </li>
        </ul>
    </div>
</nav>

<script>
    
    
    // add searching for users
    let xhr;
    let my_autoComplete = new autoComplete({
        selector: 'input[name="userSearch"]',
        minChars: 2,
        source: function(term, response){
            if (xhr !== undefined) {
                try {
                    xhr.abort();
                } catch (e) {
                    console.log(e)
                }
            }

           xhr = jQuery.get(`php/api.php?action=users&term=`+term, function(data){
                response(JSON.parse(data));
           });
        },
        onSelect: function(event, term, item) {
            window.location = "profile.php?username=" + term;
        }
    });


    $(function() {
        
        // updates the active flag on the navigaiton bar
        
        // get the last part of the path
        let loc = window.location.pathname.split("/").slice(-1)[0];
        
        if (loc === "main.php") {
            $("#home-item").addClass("active");
        } else if (loc === "addMedia.php") {
            $("#add-item").addClass("active");
        }
        
    })

</script>
    
            $body
            </div>
    </body>
</html>
EOPAGE;

    return $page;
}
?>