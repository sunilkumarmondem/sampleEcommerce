<?php
$filepath = realpath(dirname(__FILE__));
include_once ($filepath.'/../lib/database.php');
include_once ($filepath.'/../helper/format.php');

?>

<?php
class category{
	private $db;
	private $fmt;

	public function __construct(){
		$this->db=new database();
		$this->fmt=new format();
	}

	public function catInsert($catName){
		 $catName = $this->fmt->validate($catName); // Validation for special Characters             
    $catName =  mysqli_real_escape_string($this->db->link, $catName ); // Validation for mysqli   
    if (empty($catName)) {
    	 $msg = "Category Field must not be empty"; // validation for empty 
    	 return $msg;
    	}else {
        $query = "insert into tbl_category(catName) values('$catName') "; 
        $catinsert=$this->db->insert($query);
        if($catinsert){
        	$msg = "<span class='success'>Category Inserted Successfully.</span> "; // I create one span class

        	return $msg;
        }else{
        	$msg = "<span class='error'>Category Not Inserted .</span> ";
        	return $msg;
        }

     }
	}

	public function getAllcat(){
		$query="select * from tbl_category order by catId desc";
		$result=$this->db->select($query);
		return $result;
	}

	public function getCatbyId($id){
		$query = "SELECT * FROM tbl_category WHERE catId ='$id' ";
         $result = $this->db->select($query);
         return $result;
	}

	public function catUpdate($catName, $id){
     $catName = $this->fmt->validate($catName);
     $catName =  mysqli_real_escape_string($this->db->link, $catName );
     $id =  mysqli_real_escape_string($this->db->link, $id );
     if (empty($catName)) {
    	 $msg = "<span class='error'>Category Field Must Not be empty.</span> ";
    	 return $msg;
     }else {
        	$query = "UPDATE tbl_category
            SET
            catName = '$catName'
            WHERE catId = '$id' ";
            $update_row  = $this->db->update($query);
            if ($update_row) {
            	$msg = "<span class='success'>Category Updated Successfully.</span> ";
            	
            	return $msg; //Return the Message 
            }else {
            	$msg = "<span class='error'>Category Not Updated .</span> ";
    			return $msg; // Return the Message 
            }
     }
 
 }

	 public function delCatById($id){
	 	$query = "DELETE FROM tbl_category WHERE catId ='$id' ";
		  $deldata = $this->db->delete($query);
		  if ($deldata) {
		  	$msg = "<span class='success'>Category Deleted Successfully.</span> ";
		  return $msg; // return this Message 
		  }else {
		  	$msg = "<span class='error'>Category Not Deleted .</span> ";
		    	 return $msg; // return this Message 
  			}
	 }

}

?>


