<?
$station = new Station();

Echo"
<div class=\"requestform\">
    <form form method=\"post\" action=\"?p=stationsub\">
        <h2>Manage Station</h2>
        <p class=\"header\">Station Name <span class=\"requiredfield\">*</span></p>
            <p><input name=\"stationName\" id=\"stationName\" value=\"{$station->stationName}\"></p>
        <p class=\"header\">Description</p>
            <textarea name=\"stationDescription\">{$station->stationDescription}</textarea></p>
        <p class=\"header\">Timezone <span class=\"requiredfield\">*</span></p>
            <p>
                <select name=\"timeZone\" id=\"timeZone\">";
                    $station->generateTimeZoneDropdown($station->timeZone);
            Echo"</select>
            </p>
        <p class=\"header\">Domain <span class=\"requiredfield\">*</span></p>
            <p><input name=\"domain\" id=\"domain\" value=\"{$station->domain}\"></p>
        <p class=\"header\">Installation Path <span class=\"requiredfield\">*</span></p>
            <p><input name=\"installPath\" id=\"installPath\" value=\"{$station->installPath}\"></p>
        <button class=\"actionbutton request\" type=\"submit\" name=\"submit\" id=\"submit\">Submit Request</button>
    </form>
</div>
";
?>