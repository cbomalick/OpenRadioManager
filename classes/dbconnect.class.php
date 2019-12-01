<?

class DBConnect {

    private $host = "localhost";
    private $user = "**REPLACE_USER**";
    private $password = "**REPLACE_PASSWORD**";
    private $database = "**REPLACE_DB**";
    private $con;
    
    public function __construct() {
        $this->con =  $this->connectDB();
    }   

    public function __destruct(){
    }
    
    public function connectDB() {
        $con = mysqli_connect($this->host,$this->user,$this->password,$this->database);
        return $con;
    }
    
    public function getData($query) {
        $result = mysqli_query($this->con, $query);
        while($row=mysqli_fetch_assoc($result)) {
            $resultset[] = $row;
        }       
        if(!empty($resultset))
            return $resultset;
    }

    public function runQuery($sql) {
        if (mysqli_query($this->con, $sql) === TRUE) {
        } else {
        echo "Error: " . $sql . "<br>" . $con->error;
        }
    }

    public function fetchCount($sql){
        $query = $this->con->prepare($sql);
        if(!$query){
            echo "Prepare failed: (". $this->con->errno.") ".$this->con->error."<br>";
         }
        $query->execute();
        $query->store_result();
        $rows = $query->num_rows;
        return $rows;
    }
}

?>