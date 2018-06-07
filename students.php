<?php

include 'connect.php';
if (isset($_POST["names"]))
{
	    extract($_POST);

	    $sql_check="select * from students where stdreg_no='$admn' AND school_id='$school_reg'";
	    $result_check=mysqli_query($conn,$sql_check);
	    if(mysqli_num_rows($result_check)==0)
	    {
	        $year=date("Y");
			$school_id=1;

			$classy=substr($cls, 0,1);
	        $sql="INSERT INTO `students`(`names`, `stdreg_no`, `school_id`, `kcpe_marks`, `class`, `year`,  `classy`,`phone`) 
	                            VALUES ('$names','$admn','$school_reg','$kcpe','$cls','$year','$classy','$phone')";
			//add the 3 variables
	        /* echo $sql;
	         die(); */                  
			$result=mysqli_query($conn,$sql) or die(mysqli_error($conn));

			//check true/false query execution
			if($result)
			{
			   echo json_encode(array('status' => 1, "message"=>"Success" ));//1 for success
			}
			else {
			   echo json_encode(array('status' => 0, "message"=>"Failed" ));//1 for success
			}
	    }else{
	    	echo json_encode(array('status' => 2, "message"=>"Student already registered" ));//1 for success

	    }
		//save in MYSQL db
		//connect to mysql server
		
}

?>