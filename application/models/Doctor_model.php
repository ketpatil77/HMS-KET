<?php
/**
* 
*/
class Doctor_model extends Hospital_model
{
	private $table;
	function __construct()
	{
		parent::__construct();
		$this->table = 'doctor';
		parent::set_table($this->table);
	}
	public function add($data){
		return parent::New_Data($data);
	}
	public function Get($data = array()){
		/*if(isset($_GET['s']) && !empty($_GET['s'])){
			$data['name'] = $this->input->get("s");
		}*/
		if(isset($_GET['department']) && !empty($_GET['department'])){
			$data['department'] = $this->input->get("department");
		}
		return parent::Get_Data($data);
	}
	public function Update($options=array(),$data = array()){
		return parent::Update_Data($options,$data);
	}
	public function Delete($options=array()){
		return parent::Delete_Data($options);
	}

	public function AddAppoinment($doctorId,$patientId,$date,$scheduleId,$max_patients_allow,$details = "")
	{
		$result = array();
		$errorFound = false;
		$message = "";
		
		$newData = array();
		$newData['doctor_id'] = $doctorId;
		$newData['details'] = $details;
		$newData['patient_id'] = $patientId;
		$newData['date'] = $date;
		$isExistAppinment = parent::exist($newData,"appoinment");
		$todayAppointments = $this->json_store->filter("appoinment", array("date" => $date, "doctor_id" => $doctorId));
		$currentMaxSerial = 0;
		foreach ($todayAppointments as $appointment) {
			if (isset($appointment['serial_no']) && (int)$appointment['serial_no'] > $currentMaxSerial) {
				$currentMaxSerial = (int)$appointment['serial_no'];
			}
		}
		if($isExistAppinment){
			$errorFound = true;
			$message = "We have founded an appoinment at $date for you.";
		}else if($todayAppointments){
			if(count($todayAppointments) >= $max_patients_allow){
				$errorFound = true;
				$message = "Appoinment is not available at $date. Plese try another date.";
			}else{
				$newData['serial_no'] = $currentMaxSerial + 1;
			}
		}else{
			$newData['serial_no'] = 1;
		}
		// Check if Current Appoinment Exist;
		
		if(!$errorFound){
			$newData['schedule_id'] = $scheduleId;
			$newData['created_date'] = date("d/m/y");
			$newData['status'] = "pending";
			$this->json_store->insert("appoinment",$newData);
			$message = "Congratulation! Your appoinment has been placed at $date. <br>Your Serial Number: ".$newData['serial_no'];
		}
		$result["result"] = ($errorFound ? false:true);
		$result["message"] =$message;
		return $result;
	}


	public function addSchedule($data = array())
	{
		$this->json_store->insert("doctors_schedule",$data);
	}
	public function getSchedule($dataArg = array())
	{
		$rows = $this->json_store->filter("doctors_schedule", $dataArg);
		return array_map(function($row) {
			return (object)$row;
		}, $rows);

	}
	public function deleteSchedule($doctorId,$scheduleId)
	{
		$this->json_store->delete("doctors_schedule", array("id" => $scheduleId, "doctor_id" => $doctorId));
	}
}
