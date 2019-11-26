<?
//TODO: Truncate values past limit
if(!empty($_POST["artist"]) && !empty($_POST["song"])){
    $artist = htmlentities($_POST["artist"], ENT_QUOTES);
    $song = htmlentities($_POST["song"], ENT_QUOTES);
    $name = htmlentities($_POST["name"], ENT_QUOTES);

    $newRequest = new Request("NEW");
    $newRequest->addNewRequest($artist,$song,$name);

    Echo"<div class=\"requestform\">
        <h2>Request a Song</h2>
            <p style=\"text-align: center;\">Thank you for your request!</p>
        </div>";
} else {
    Echo"<div class=\"requestform\">
        <h2>Request a Song</h2>
            <p style=\"text-align: center;\">Sorry, we could not process your request!</p>
            <p style=\"text-align: center;\">Please make sure artist name and song title are included</p>
        </div>";
}
?>

