<?php 
class Customer
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

	public function getCustomers()
	{
		$sql = "SELECT * FROM customers";
		return $this->db->query($sql,1);
	}

	public function getCustomer($id)
	{
		$sql = "SELECT * FROM customers WHERE id={$id}";
		return $this->db->query($sql,1);
	}

	public function add($data)
	{
		$data = array_filter($data);
		$keys = array_keys($data);
		$sql = "INSERT INTO customers(".implode(",", $keys).") ";
		$sql .= "VALUES(:".implode(",:", $keys).")";
		return $this->db->insert($sql,$data);	
	}

	
	public function update($data)
	{
		$id = (int) $data['id'];
		unset($data['id']);
		$data = array_filter($data);
		$keys = array_keys($data);

		$sql = "UPDATE customers set ";
		foreach ($keys as $value) {
			$sql = $sql ." {$value}=:{$value},";
		}
		$sql =  rtrim($sql,',');
		$sql = $sql . " WHERE id=".$id;
	
		return $this->db->update($sql,$data);
	}

	public function delete($id)
	{
		$sql = "DELETE FROM customers WHERE id={$id}";
		return $this->db->delete($sql);
	}
}