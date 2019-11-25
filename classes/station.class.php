<?

class Station {

    public $stationName;
    public $stationDescription;
    public $timeZone;
    public $currentDJ;
    public $nowPlaying;
    public $onAir;
    private $staffLoggedIn;

    public function __construct(){
		$connect = new DBConnect();
        $row = $connect->getData("SELECT name,description,timezone FROM station WHERE status = 'Active'") or die("Error: Station not found.");
        foreach($row as $row){
            $this->stationName = $row['name'];
            $this->stationDescription = $row['description'];
            $this->timeZone = $row['timezone'];
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
}

?>