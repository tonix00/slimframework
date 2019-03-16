<?php 
include(dirname(__FILE__)."/config.php");
class MysqlDriver
{
	private static $instance;
    public $db;
	public $db_err   = null;
	
	public function __construct($db_host=null,$db_name=null,$db_username=null,$db_password=null) 
	{

        if(empty($db_host)) $db_host = DB_HOST;
		if(empty($db_name)) $db_name = DB_DSN;
		if(empty($db_username)) $db_username = DB_USERNAME;
		if(empty($db_password)) $db_password = DB_PASSWORD;

        $dsn= "mysql:host=$db_host;dbname=$db_name";
 
        try{
            // create a PDO connection with the configuration data
            $this->db = new PDO($dsn, $db_username, $db_password);   
        }catch (PDOException $e){
            $this->db_err = $e->getMessage();
        }
	}
	
	public static function getInstance() 
	{
		if (!self::$instance instanceof self)
			self::$instance = new self;
		return self::$instance;
	}
	
	private function checkError()
	{
		if($this->db_err){
            print $this->db_err."\n";
            return true;
        }else{
            return false;
        }
	}
	
	public function query($sql,$query_type=2)
	{
        $hasError = $this->checkError();

        if($hasError==true){
            return false;
        }

        $stmt = $this->db->query($sql);
        $res = array();

        if($stmt === false){
            $this->db_err = "Error executing the query";
            return false;
        }

        if($query_type==1){
            while($row = $stmt->fetch(PDO::FETCH_ASSOC))    
				$res[] = $row; 
        }
        if($query_type==2){
            while($row = $stmt->fetch(PDO::FETCH_OBJ))    
				$res[] = $row; 
        }
		return $res;
	}
    
    public function insert($sql,$data=null)
    {
        if($this->executeQuery($sql,$data)===false){
            return false;
        }
        return $this->db->lastInsertId();
    }
    
    public function update($sql,$data=null)
    {
        return $this->executeQuery($sql,$data);
    }

    public function delete($sql)
    {
        return $this->executeQuery($sql);
    }
	
	private function executeQuery($sql,$data=null)
    {
        $hasError = $this->checkError();

        if($hasError==true){
            return false;
        }

        if($data==null)
        {
            if($this->db->exec($sql) === false)
            {
                $this->db_err = "Error executing the query.";
                return false;
            }
            return true;
        }

        try
        {
            $stmt = $this->db->prepare($sql); 
            foreach ($data as $key => $value) {
                $$key = $value;
                $stmt->bindParam(':'.$key,$$key);
            }
            $test = $stmt->execute();
            return true;
        }
        catch(PDOException $e)
        {
            return false;
        }
    }
}
