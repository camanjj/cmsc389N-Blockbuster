<?php
require_once "php/support.php";
require_once "php/library.php";

session_start();

$lib = Library::getLibraryById($_GET["libraryId"], $_SESSION["userId"]);

$yesChecked;
$noChecked;

if($lib->is_shared == 0){
  $yesChecked = "";
  $noChecked = "checked";
} else {
  $noChecked = "";
  $yesChecked = "checked";
}

$html = <<<BODY

<br />
<div class="mx-auto text-center">
    <h2>Edit {$lib->name} Library Information:</h2>
</div>

<br />
<br />

<div class="offset-lg-3 col-lg-6">
<form id="form">
    <div class="form-group">
        Library name:<br>
        <input id="libName" name="search" type="text" class="form-control" placeholder="{$lib->name}" >
    </div>
    <div class="form-group">
        Library description:<br>
        <input id="libDesc" name="search" type="text" class="form-control" placeholder="{$lib->description}" >
    </div>
    <div class="form-check form-check-inline">Share?:<br>
      <label class="form-check-label">
        <input class="form-check-input" type="radio" name="isShared" id="shared" value="1" $yesChecked>Yes
      </label>
    </div>
    <div class="form-check form-check-inline">
      <label class="form-check-label">
        <input class="form-check-input" type="radio" name="isShared" id="shared" value="0" $noChecked>No
      </label>
    </div><br>
    <button type="button" class="btn btn-primary offset-md-4 col-md-4 text-center">Update Library</button>
    </form>
</div>
<br />




<script>

$('#form').submit(function(e) {
    e.preventDefault();

    let name = $('#libName').val();
    let description = $('#libDesc').val();
    let isShared = $('input[type=radio]').filter(':checked').first().val();

    let data = {
        "action": "editLibrary",
        "name": name,
        "description": description,
        "is_shared": isShared,
        "libId": {$lib->id}
    };

    // send request to server
    $.post("php/api.php", data, (data, status, xhr) => {
        console.log(data);
        if (status === "success") {
            // move to next page
            window.location = "main.php";
        } else {
            // there was an issue
            alert("Unable to update library");
        }
    });

    return false;
});


</script>

BODY;

echo generatePage($html);




?>
