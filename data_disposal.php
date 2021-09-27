<?php 
$con=mysqli_connect("localhost","root","","db_hris");
if (mysqli_connect_errno()){
    echo "Failed to connect to MySQL:".mysqli_connect_error();
}

$now = date('Y-m-d');

/************************ DISPOSING DAILY MONITORING **************************/
$get_daily = mysqli_query($con,"SELECT daily_id, date_encoded FROM daily_monitoring");
while($fetch_daily = mysqli_fetch_array($get_daily)){
	$diff = abs(strtotime($now) - strtotime($fetch_daily['date_encoded']));
	$years = floor($diff / (365*60*60*24));
	$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
	$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));


	if($days >= 30){
		$delete_daily = mysqli_query($con, "DELETE FROM daily_monitoring WHERE daily_id = '$fetch_daily[daily_id]'");
		
	}
}


/************************ DISPOSING HOSPITAL EXPOSURE **************************/

$get_hospital = mysqli_query($con,"SELECT exposure_id, date_encoded FROM hospital_exposure");
while($fetch_hospital = mysqli_fetch_array($get_hospital)){
	$diff = abs(strtotime($now) - strtotime($fetch_hospital['date_encoded']));
	$years = floor($diff / (365*60*60*24));
	$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
	$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));


	if($days >= 30){
		$delete_hospital = mysqli_query($con, "DELETE FROM hospital_exposure WHERE exposure_id = '$fetch_hospital[exposure_id]'");
		$delete_hospital_contact = mysqli_query($con, "DELETE FROM hospital_contact WHERE exposure_id = '$fetch_hospital[exposure_id]'");
		
	}
}


/************************ DISPOSING TRAVEL HISTORY **************************/

$get_travel = mysqli_query($con,"SELECT travel_id, date_encoded FROM travel_history");
while($fetch_travel = mysqli_fetch_array($get_travel)){
	$diff = abs(strtotime($now) - strtotime($fetch_travel['date_encoded']));
	$years = floor($diff / (365*60*60*24));
	$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
	$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));


	if($days >= 30){
		$delete_travel = mysqli_query($con, "DELETE FROM travel_history WHERE travel_id = '$fetch_travel[travel_id]'");
		$delete_travel_det = mysqli_query($con, "DELETE FROM travel_history_details WHERE travel_id = '$fetch_travel[travel_id]'");
		$delete_travel_places = mysqli_query($con, "DELETE FROM travel_history_places WHERE travel_id = '$fetch_travel[travel_id]'");
		
	}
}

/************************ DISPOSING VISITOR HISTORY **************************/

$get_visitor = mysqli_query($con,"SELECT visitor_id, date_encode FROM visitor_history");
while($fetch_visitor = mysqli_fetch_array($get_visitor)){
	$diff = abs(strtotime($now) - strtotime($fetch_visitor['date_encode']));
	$years = floor($diff / (365*60*60*24));
	$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
	$days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));


	if($days >= 30){
		$delete_visitor = mysqli_query($con, "DELETE FROM visitor_history WHERE visitor_id = '$fetch_visitor[visitor_id]'");
	
		
	}
}


