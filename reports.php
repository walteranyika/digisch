<?php
include 'connect.php';
header("Content-type:application/json");

//top_ten($conn,"ABC126","1");
if (isset($_POST["top_ten"])) {
	extract($_POST);
	top_ten($conn, $school_id, $class);
}
else if (isset($_POST["bottom_ten"])) {
	extract($_POST);
	bottom_ten($conn, $school_id, $class);
}
else if (isset($_POST["all_students"])) {
	extract($_POST);
	all_students($conn, $school_id, $class);
}
else if (isset($_POST["totals_per_class"])) {
	extract($_POST);
	total_per_stream($conn, $school_id);
}
else if (isset($_POST["totals_per_stream"])) {
	extract($_POST);
	total_per_class($conn, $school_id);
}

else if (isset($_POST["total_for_school"])) {
	extract($_POST);
	total_for_school($conn, $school_id);
}
else if (isset($_POST["total_all_schools"])) {
	extract($_POST);
	total_all_schools($conn);
}
else if (isset($_POST["stream_mean_score"])) {
	extract($_POST);
	stream_mean_score($conn,$school_id);
}
else if (isset($_POST["class_mean_score"])) {
	extract($_POST);
	class_mean_score($conn,$school_id);
}
else{
	//echo json_encode(array("error"=>"No values received"));
}

/*Gson gson = new Gson();

    NavItem[] navigationArray = gson.fromJson(jsonString, NavItem[].class);*/


function top_ten($conn, $school_id, $class)
{
	$sql="SELECT students.names, students.stdreg_no,students.class as classy, exams.exam_name, exams.term,
	SUM(exams.score) as total FROM exams JOIN students ON exams.std_regno=students.stdreg_no 
	WHERE students.school_id='$school_id' and students.class LIKE '$class%'  GROUP BY exams.exam_name, 
	exams.std_regno, exams.term   ORDER BY total DESC LIMIT 10";
	$result=mysqli_query($conn, $sql);
	$data= array();
	while ($row=mysqli_fetch_assoc($result)) 
	{
		$data[]=$row;
	}
	echo json_encode($data);
}

function bottom_ten($conn, $school_id, $class)
{
	$sql="SELECT students.names, students.stdreg_no,students.class as classy, exams.exam_name, exams.term,
	SUM(exams.score) as total FROM exams JOIN students ON exams.std_regno=students.stdreg_no 
	WHERE students.school_id='$school_id' and students.class LIKE '$class%'  GROUP BY exams.exam_name, 
	exams.std_regno, exams.term   ORDER BY total LIMIT 10";
	$result=mysqli_query($conn, $sql);
	$data= array();
	while ($row=mysqli_fetch_assoc($result)) 
	{
		$data[]=$row;
	}
	echo json_encode($data);
}

function all_students($conn, $school_id, $class)
{
	$sql="SELECT students.names, students.stdreg_no,students.class as classy, exams.exam_name, exams.term,
	SUM(exams.score) as total FROM exams JOIN students ON exams.std_regno=students.stdreg_no 
	WHERE students.school_id='$school_id' and students.class LIKE '$class%'  GROUP BY exams.exam_name, 
	exams.std_regno, exams.term   ORDER BY total";
	$result=mysqli_query($conn, $sql);
	$data= array();
	while ($row=mysqli_fetch_assoc($result)) 
	{
		$data[]=$row;
	}
	echo json_encode($data);
}


function total_per_stream($conn, $school_id)
{
	$sql="SELECT class as classy, COUNT(names) as total FROM students WHERE school_id='$school_id' GROUP by class";
	$result=mysqli_query($conn, $sql);
	$data= array();
	while ($row=mysqli_fetch_assoc($result)) 
	{
		$data[]=$row;
	}
	echo json_encode($data);
}



function total_per_class($conn, $school_id)
{
	$sql="SELECT classy, COUNT(names) as total FROM students WHERE school_id='$school_id' GROUP by classy";
	$result=mysqli_query($conn, $sql);
	$data= array();
	while ($row=mysqli_fetch_assoc($result)) 
	{
		$data[]=$row;
	}
	echo json_encode($data);
}

function total_for_school($conn, $school_id)
{
	$sql="SELECT  COUNT(names) as total FROM students WHERE school_id='$school_id' ";
	$result=mysqli_query($conn, $sql);
	$data= array();
	$row=mysqli_fetch_assoc($result);
	echo json_encode($row);
}

function total_all_schools($conn)
{
	$sql="SELECT schools.school_reg, schools.school_name, COUNT(students.names) as total FROM students JOIN schools ON schools.school_reg=students.school_id GROUP BY schools.school_reg";
	$result=mysqli_query($conn, $sql);
	$data= array();
	while ($row=mysqli_fetch_assoc($result)) 
	{
		$data[]=$row;
	}
	echo json_encode($data);
}

function class_mean_score($conn, $school_id)
{
	$sql="SELECT students.classy, (SUM(exams.score)/COUNT(names)) as total FROM exams JOIN students ON exams.std_regno=students.stdreg_no WHERE students.school_id='$school_id' GROUP BY students.classy";
	$result=mysqli_query($conn, $sql);
	file_put_contents("class.txt", $sql."\n", FILE_APPEND);
	$data= array();
	while ($row=mysqli_fetch_assoc($result)) 
	{
		$data[]=$row;
	}
	echo json_encode($data);
}

function stream_mean_score($conn, $school_id)
{
	$sql="SELECT students.class as classy, (SUM(exams.score)/COUNT(names)) as total FROM exams JOIN students ON exams.std_regno=students.stdreg_no WHERE students.school_id='$school_id' GROUP BY students.class";
	$result=mysqli_query($conn, $sql);
	file_put_contents("stream.txt", $sql."\n", FILE_APPEND);
	$data= array();
	while ($row=mysqli_fetch_assoc($result)) 
	{
		$data[]=$row;
	}
	echo json_encode($data);
}


?>