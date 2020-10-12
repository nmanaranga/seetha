<?php
//session_start();

class PR_Backup {
    private $host = "192.168.1.73";
    private $user = "roshan";
    private $pass = "smtk";
    private $db = "seetha";
    
    private $connection;
    
    private $magic_quotes_active;
    private $new_real_escape_string_exists;
    
    public $table = "company";
    public $limit = 0;
    public $count = 50;
    public $query;
    
    function __construct(){
        if($this->connection = mysql_connect($this->host, $this->user, $this->pass)){
            mysql_select_db($this->db);
        }else {
            echo "MySQL error : ".mysql_error();
        }
    }
    
    public function bakup(){
        $sql = ""; $rec = true;
        if($this->limit == 0){
            $row2 = mysql_fetch_array(mysql_query('SHOW CREATE TABLE '.$this->table));
            if(isset($row2['Table'])){
                $sql.= "\n\n/*Table structure for table `".$this->table."` */\n\n\n";
                $sql.= 'DROP TABLE IF EXISTS `'.$this->table.'`;';
                $sql.= "\n\n".$row2['Create Table'].";\n\n";
                $sql.="\n/*Data for the table `".$this->table."` */\n\n\n";
                
                $rec = true;
            }else{
                $sql = "";
                $rec = false;
            }
        }
        
        if($rec == true){
            $sql2 = "SELECT * FROM `".$this->table."` LIMIT ".$this->limit.", ".$this->count;
            
            $query = mysql_query($sql2, $this->connection);
            
            if(mysql_num_rows($query)>0){
                $res["isEmp"] = false;
                $rec = array();
                
                $sql .= "LOCK TABLES `".$this->table."` WRITE;\n\n";
                
                $sql .= "\nINSERT INTO `".$this->table."` (`";
                
                
                $sql2 = "SHOW COLUMNS FROM `".$this->table."` ";
                $query2 = mysql_query($sql2);
                $field = array();
                
                while($r = mysql_fetch_object($query2)){
                    $field[] = $r->Field;
                }
                $sql .= join("`, `", $field);
                $sql .= "`) VALUES ('";
                while($r = mysql_fetch_row($query)){
                    for($i=0; $i<count($r); $i++){
                        $r[$i] = $this->escape_value($r[$i]);
                    }
                    $rec[] = join("', '", $r);
                }
                $sql .= join("'),('", $rec);
                $sql .= "');\n\n\n";
                
                $sql .= "UNLOCK TABLES;\n\n\n";
            }else{
                $res["isEmp"] = true;
            }
        }
        $res["res"] = $sql;
        
        return $res;
    }
    
    public function restore(){
        if($this->query != ""){
            if(mysql_query($this->query)){
                return 1;
            }else{
                return mysql_errno();
            }
        }else{
            return 0;
        }
    }
    
    public function rec_count(){
        $sql = "SELECT COUNT(*) AS `count` FROM `".$this->table."` ";
        $query = mysql_query($sql);
        
        $r = mysql_fetch_object($query);
        
        return $r->count;
    }
    
    public function get_tb(){
        $query = mysql_query("show full tables where Table_Type != 'VIEW'");
        $row = array();
        while($result = mysql_fetch_row($query)){
            $row[] = $result[0];
        }
        
        echo json_encode($row);
    }
    
    public function escape_value($value){
	if($this->new_real_escape_string_exists){
	    // undo any magic quote effects so mysql_real_escape_string can do the work
	    if($this->magic_quotes_active){
                $value = stripslashes($value);
            }
	    $value = mysql_real_escape_string($value);
        }else{
	    // if magic quotes aren't already on then add slashes manually
	    if(!$this->magic_quotes_active){
                $value = addslashes($value);
            }
	    // if magic quotes are active, then the slashes already exist
        }
	return $value;
    }
}
?>