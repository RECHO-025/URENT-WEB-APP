
<?php 
class collect_data {
	public $servername;
	public $username;
	public $password;
	public $dbname;
	public $schoolid;
	
  function __construct($servername, $username, $password,$dbname,$schoolid) {
		$this->servername = $servername;
		$this->username = $username;
		$this->password = $password;
		$this->dbname = $dbname;
		$this->schoolid = $schoolid;
			}
			// function for connect
				
function connect_database ($servername,$username,$password,$dbname) {

	// Create connection
	
	$conn = new mysqli($servername,$username,$password,$dbname);
	// Check connection
	if ($conn->connect_error) {
	  $conn1 = "Failed";
	  }
	  else{
	  
	   $conn1 = "success";
	  
	  }
	  return $conn;
}

// Query function to the database for one outcome 	

function query_database_one ($conn,$sql) { 

     $query = mysqli_query($conn,$sql);
	 $outcome =mysqli_fetch_row($query)[0]; 
	 if($outcome = mysqli_fetch_row($query))
	 {
	   $outcome =mysqli_fetch_row($query)[0]; 
	 }
	 else {
	  $outcome = 0;
	  }
	  return $outcome; 
}

// Query function to the database for many outcomes

function query_database_many ($conn,$sql) { 

     $query = mysqli_query($conn,$sql);
	  return $query;
			 }
function query_database_many_rows ($conn,$sql) { 

     $query = mysqli_query($conn,$sql);
	 $row =  $query->fetch_assoc();
	  return $row;
			 }
			 
function count_rows($sql)
 {

$num = mysqli_num_rows ($sql);

return $num;

}	



	 		 
			 
// Query function delete and edit records		 

function update_record ($conn,$sql)  {

    $my_sql_statement = $sql; 
    
	if (!mysqli_query($conn,$my_sql_statement))
	{
	$status_edit = "False"; 
	}
	 else {
	
	$status_edit = "True"; 
	
		
	   }		 
		return $status_edit; 	 
			 
	}		 
			 		 	
  }
 
// require 'session.php';
 // Database connection instance 

$servername = "localhost";
$username = "eljotellug_house_rental2";
$password = "0702014626@Bjk";
$dbname = "eljotellug_house_rental2";

$landlordid ="1213333"; 

$conn1 = new collect_data($servername, $username, $password, $dbname,$landlordid);	

$conn = $conn1->connect_database($servername,$username,$password,$dbname);

// Query one data instance 

//$sql = "SELECT Fname FROM person where personid ='1'";

//$myquery = $conn1-> query_database_one($conn,$sql);

//echo $myquery; 

// Query many records instance 
/*
$sql2 = "SELECT Fname FROM person";

$myquery = $conn1-> query_database_many($conn,$sql2);

while($info = $myquery->fetch_assoc()) {
  
  // query the database for specific rows 
  
  //$username1= htmlspecialchars(($info["Fname"])); 
  
   //echo $username1;
   
   //echo "<br>";
  }
   
// delete / edit records instance
/*
$sql3 ="UPDATE person  
SET Fname ='NDAGIRE'
where personid ='1'";

$conn = $conn1->connect_database($servername,$username,$password,$dbname);

$my_edirz = $conn1-> query_database_one($conn,$sql);
*/
/*
$query23 = "SELECT *FROM school_status where schoolid ='$schoolid'";
			
$conn = $conn1->connect_database($servername,$username,$password,$dbname);
			
$status1 = $conn1-> query_database_many_rows($conn,$query23);

$status = $status1['status'];


/*	
$dstabaseconnection = new collect_data($no_of_outcomes,$servername, $username, $password, $dbname);	

echo $dstabaseconnection->connect_database($servername,$username,$password,$dbname);

		 
$query = "SELECT Fname FROM person where personid = '1'";
$no_of_outcomes =1;

$apple = new collect_data($conn,$query,$no_of_outcomes);
echo $apple->execute_querry($conn,$query);
*/
?>
