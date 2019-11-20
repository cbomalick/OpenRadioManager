<?
//TODO: Truncate values past limit
$artist = htmlentities($_POST["artist"], ENT_QUOTES);
$song = htmlentities($_POST["song"], ENT_QUOTES);
$name = htmlentities($_POST["name"], ENT_QUOTES);

$newRequest = new Request("NEW");
$newRequest->addNewRequest($artist,$song,$name);
?>

<div class="requestform">
    <h2>Request a Song</h2>
        <p style="text-align: center;">Thank you for your request!</p>
</div>