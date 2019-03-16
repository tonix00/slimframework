<?php

class Todo
{
	private static $instance;
	private $db = null;
	public function __construct()
	{
		$this->db = MysqlDriver::getInstance();
	}

	public static function getInstance() 
	{
		if (!self::$instance instanceof self)
			self::$instance = new self;
		return self::$instance;
	}

	public function getTodos()
	{
		$sql = "SELECT * FROM todo";
		return $this->db->query($sql,1);
	}


	public function getTodo($id)
	{
		$sql = "SELECT * FROM todo WHERE id=$id";
		return $this->db->query($sql,1);
	}

	public function add($data)
	{
		$data = array_filter($data,'strlen');
		$keys = array_keys($data);
		$sql = "INSERT INTO todo(".implode(",", $keys).") ";
		$sql .= "VALUES(:".implode(",:", $keys).")";
		return $this->db->insert($sql,$data);	
	}

	public function update($data)
	{
		$id = (int) $data['id'];
		unset($data['id']);
		$data = array_filter($data,'strlen');
		$keys = array_keys($data);

		$sql = "UPDATE todo set ";
		foreach ($keys as $value) {
			$sql = $sql ." {$value}=:{$value},";
		}
		$sql =  rtrim($sql,',');
		$sql = $sql . " WHERE id=".$id;
		return $this->db->update($sql,$data);
	}

	public function delete($id)
	{
		$sql = "DELETE FROM todo WHERE id={$id}";
		return $this->db->delete($sql);
	}

}