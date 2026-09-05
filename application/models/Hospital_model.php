<?php
/**
* 
*/
class Hospital_model extends CI_Model
{
	private $table;
	private $store;
	function __construct()
	{
		parent::__construct();
		$this->load->library('json_store');
		$this->store = $this->json_store;
		$this->table = 'users';
	}
	public function set_table($table){
		$this->table = $table;
	}
	public function get_table($table){
		return $this->table;
	}
	public function New_Data($data = array()){
		if(!is_array($data))
			return;
		return $this->store->insert($this->table, $data);
	}
	public function Update_Data($options = array(),$data = array()){
		if(!is_array($options))
			return;
		return $this->store->update($this->table, $options, $data);
	}
	public function Get_Data($data = array(),$orderyBy = array()){
		$rows = $this->store->filter($this->table, $data);
		if (isset($_GET["s"])) {
			$search = trim($this->input->get("s"));
			if ($search !== '') {
				$rows = array_values(array_filter($rows, function($row) use ($search) {
					return $this->searchMatchesRow($row, $search);
				}));
			}
		}
		if(!empty($orderyBy)){
			$field = $orderyBy[0];
			$direction = strtolower($orderyBy[1]);
		}else{
			$field = 'id';
			$direction = 'desc';
		}
		usort($rows, function($a, $b) use ($field, $direction) {
			$aValue = isset($a[$field]) ? $a[$field] : null;
			$bValue = isset($b[$field]) ? $b[$field] : null;
			if (is_numeric($aValue) && is_numeric($bValue)) {
				$cmp = (float)$aValue <=> (float)$bValue;
			} else {
				$cmp = strcmp((string)$aValue, (string)$bValue);
			}
			return $direction === 'desc' ? -$cmp : $cmp;
		});
		return array_map(function($row) {
			return (object)$row;
		}, $rows);
	}
	public function Delete_Data($data = array()){
		if(empty($data))
			return;
		return $this->store->delete($this->table, $data);
	}
	public function exist($data = array(),$tableArg = ""){
		$table = $this->table;
		if(!empty($tableArg))
			$table = $tableArg;
		if(empty($data))
			return;
		return $this->store->exists($table, $data);
	}

	private function searchMatchesRow(array $row, $search)
	{
		if ($this->table === 'user') {
			return stripos((string)($row['full_name'] ?? ''), $search) !== false
				|| stripos((string)($row['user_name'] ?? ''), $search) !== false
				|| stripos((string)($row['email'] ?? ''), $search) !== false;
		}
		if ($this->table === 'doctor' || $this->table === 'patient' || $this->table === 'nurse') {
			return stripos((string)($row['name'] ?? ''), $search) !== false
				|| stripos((string)($row['phone'] ?? ''), $search) !== false
				|| stripos((string)($row['email'] ?? ''), $search) !== false;
		}
		return true;
	}
}
