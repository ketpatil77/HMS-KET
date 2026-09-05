<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Json_store {
	private $basePath;

	public function __construct($basePath = null)
	{
		$this->basePath = $basePath ?: APPPATH.'data/';
		if (!is_dir($this->basePath)) {
			mkdir($this->basePath, 0777, true);
		}
	}

	private function path($table)
	{
		return rtrim($this->basePath, '/\\').'/'.$table.'.json';
	}

	public function load($table)
	{
		$path = $this->path($table);
		if (!file_exists($path)) {
			return array();
		}
		$data = json_decode(file_get_contents($path), true);
		return is_array($data) ? $data : array();
	}

	public function save($table, array $rows)
	{
		$path = $this->path($table);
		file_put_contents($path, json_encode(array_values($rows), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
		return true;
	}

	public function next_id($table)
	{
		$rows = $this->load($table);
		$max = 0;
		foreach ($rows as $row) {
			if (isset($row['id']) && (int)$row['id'] > $max) {
				$max = (int)$row['id'];
			}
		}
		return $max + 1;
	}

	public function all($table)
	{
		return $this->load($table);
	}

	public function filter($table, array $where = array())
	{
		$rows = $this->load($table);
		if (empty($where)) {
			return $rows;
		}
		$result = array();
		foreach ($rows as $row) {
			if ($this->matches($row, $where)) {
				$result[] = $row;
			}
		}
		return $result;
	}

	public function exists($table, array $where = array())
	{
		return count($this->filter($table, $where)) > 0;
	}

	public function insert($table, array $data)
	{
		$rows = $this->load($table);
		if (!isset($data['id']) || $data['id'] === '' || $data['id'] === null) {
			$data['id'] = $this->next_id($table);
		}
		$rows[] = $data;
		$this->save($table, $rows);
		return (int)$data['id'];
	}

	public function update($table, array $where, array $data)
	{
		$rows = $this->load($table);
		$count = 0;
		foreach ($rows as &$row) {
			if ($this->matches($row, $where)) {
				$row = array_merge($row, $data);
				$count++;
			}
		}
		unset($row);
		$this->save($table, $rows);
		return $count;
	}

	public function delete($table, array $where)
	{
		$rows = $this->load($table);
		$filtered = array();
		$deleted = 0;
		foreach ($rows as $row) {
			if ($this->matches($row, $where)) {
				$deleted++;
				continue;
			}
			$filtered[] = $row;
		}
		$this->save($table, $filtered);
		return $deleted;
	}

	public function max_value($table, $column, array $where = array())
	{
		$rows = empty($where) ? $this->load($table) : $this->filter($table, $where);
		$max = null;
		foreach ($rows as $row) {
			if (!isset($row[$column]) || $row[$column] === '') {
				continue;
			}
			$value = is_numeric($row[$column]) ? (float)$row[$column] : 0;
			if ($max === null || $value > $max) {
				$max = $value;
			}
		}
		return $max === null ? null : $max;
	}

	private function matches(array $row, array $where)
	{
		foreach ($where as $key => $value) {
			if (!array_key_exists($key, $row)) {
				return false;
			}
			if ((string)$row[$key] !== (string)$value) {
				return false;
			}
		}
		return true;
	}
}
