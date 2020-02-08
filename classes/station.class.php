<?

class Station {

    public $stationName;
    public $stationDescription;
    public $timeZone;
    public $currentDJ;
    public $nowPlaying;
    public $onAir;
    public $staffLoggedIn;
    public $domain;
    public $installPath;
    public $webAddress;

    public function __construct(){
        $this->connect = DBConnect::getInstance()->getConnection();
        $sql = "SELECT name,description,timezone,domain,installpath FROM station WHERE status = 'Active'";
        $stmt = $this->connect->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetchAll();
        
        foreach($row as $row){
            $this->stationName = $row['name'];
            $this->stationDescription = $row['description'];
            $this->timeZone = $row['timezone'];
            $this->domain = $row['domain'];
            $this->installPath = $row['installpath'];
            $this->webAddress = $this->domain . $this->installPath;
        }

        $this->currentDJ = "";
        $this->nowPlaying = "";
        $staffLoggedIn = TRUE;

        if($staffLoggedIn == TRUE){
            $this->onAir = "<span class=\"stationstatus onair\"> LIVE NOW </span>";
        } else {
            $this->onAir = "<span class=\"stationstatus offair\"> OFF AIR </span>";
        }
        
}

    public function stationUpdateDetails($stationName,$stationDescription,$timeZone,$domain,$installPath){
        //Purpose: Updates the station details as given in Manage Station screen
        $sql = "UPDATE station SET name = ?, description = ?, timezone = ?, domain = ?, installpath = ? WHERE status = 'Active'";
            $stmt = $this->connect->prepare($sql);
            $stmt->execute([$stationName, $stationDescription, $timeZone, $domain, $installPath]);
    }

    public function generateTimeZoneDropdown($timeZone){
        $timeArray = array(
            "Eastern" => "America/New_York",
            "Central" => "America/Chicago",
            "Mountain" => "America/Denver",
            "Mountain no DST" => "America/Phoenix",
            "Pacific" => "America/Los_Angeles",
            "Alaska" => "America/Anchorage",
            "Hawaii" => "America/Adak",
            "Hawaii no DST" => "Pacific/Honolulu"
        );

        foreach($timeArray as $key => $value){
            if ($value == $timeZone){
                $Selected = 'Selected';
            } else {
                $Selected = "";
            }

            Echo"<option value=\"{$value}\" {$Selected}>{$key}</option>";
            unset($Selected);
        }
     }
}

?>