<?

class Station {

    public $stationName;
    public $stationDescription;
    public $currentDJ;
    public $nowPlaying;

    public function __construct(){
		$connect = new DBConnect();
        $row = $connect->getData("SELECT stationname,stationdescription FROM station WHERE status = 'Active'") or die("Error: Station not found.");
        foreach($row as $row){
            $this->stationName = $row['stationname'];
            $this->stationDescription = $row['stationdescription'];
        }
}
}

?>