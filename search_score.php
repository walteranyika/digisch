<?php

include 'connect.php';
if (isset($_POST["adm"])) 
{
	
	  extract($_POST);//$adm,$term
	  $term=str_replace(" ", "", $term);
	  $sql ="SELECT students.names, exams.exam_name, exams.subject, exams.score, exams.term, exams.year
				FROM students
				JOIN exams
				ON students.`stdreg_no`= exams.`std_regno`
				WHERE exams.`std_regno`=$adm
				AND  students.`school_id`='$school_reg'
				AND
				exams.term='$term'";
	  $result= mysqli_query($conn,$sql);

	  //echo "$sql";
	  if(mysqli_num_rows($result)>0)
	  {

	  	$scores=array();
	  	$names;$exam_name;$term;$year;
	  	$total=0;
	  	$mean=0;
	  	$count=0;
	    while ($row=mysqli_fetch_assoc($result)) 
	    {
	       $scores[]= array("subject"=>$row["subject"], "score"=>$row["score"]);
	       $total+=$row["score"];
	       $names=$row["names"];
	       $exam_name=$row["exam_name"];
	       $term=$row["term"];
	       $year=$row["year"];
	       $count++;
	    }
	    $mean=$total/$count;
	    $mean=round($mean,2);
	    
	    $meangrade = "Y";
if ($mean< 34.99) {
	$meangrade = "E";
}

else if ($mean>=35 and $mean <= 39.99) {
	$meangrade = "D-";
}
else if ($mean>= 40 and $mean <= 44.99) {
	$meangrade = "D";
}
else if ($mean>= 45 and $mean <= 49.99) {
	$meangrade = "D+";
}
else if ($mean>= 50 and $mean <= 54.99) {
	$meangrade = "C-";
}
else if ($mean>= 55 and $mean <= 59.99) {
	$meangrade = "C";
}
else if ($mean>= 60 and $mean <= 64.99) {
	$meangrade = "C+";
}
else if ($mean> 65 and $mean <= 69.99) {
	$meangrade = "B-";
}
else if ($mean>= 70 and $mean <= 74.44) {
	$meangrade = "B";
}
else if ($mean>= 74.45 and $mean <= 79.99) {
	$meangrade = "B+";
}
else if ($mean>= 80 and $mean <= 84.99) {
	$meangrade = "A-";
}
else if ($mean>= 85 and $mean <= 100) {
	$meangrade = "A";
}
else {
  $meangrade = "Y";	
}

//echo "$meangrade";


	    $all = array('names' =>$names, 'exam_name'=>$exam_name, 'term'=>$term,'year'=>$year, 'scores'=>$scores,'total'=>$total,'mean'=>$mean,'meangrade'=>$meangrade);
	    echo json_encode($all);
	    
	  }
	  else
	  {
	  	echo json_encode(array('message' =>"No info found with adm no. $adm"));
	  }
}else
{
	echo "Nothing was sent";
}




?>