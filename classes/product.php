<?php
$filepath = realpath(dirname(__FILE__));
include_once ($filepath.'/../lib/database.php');
include_once ($filepath.'/../helper/format.php');
?>

<?php
class product{
	private $db;
	private $fm;

	public	function __construct(){
		$this->db   = new database();
		$this->fm   = new format();
	}

	public function productInsert($data, $file){

		$productName   =  mysqli_real_escape_string($this->db->link, $data['productName'] );
		$catId 	   =  mysqli_real_escape_string($this->db->link, $data['catId'] );
		$brandId 	   =  mysqli_real_escape_string($this->db->link, $data['brandId'] );
		$body 	   =  mysqli_real_escape_string($this->db->link, $data['body'] );
		$price  	   =  mysqli_real_escape_string($this->db->link, $data['price'] );
		$type 	   =  mysqli_real_escape_string($this->db->link, $data['type'] );

		$permited = array('jpg','png','jpeg','gif');
		$file_name = $file['image']['name'];
		$file_size = $file['image']['size'];
		$file_temp = $file['image']['tmp_name'];

		$div = explode('.', $file_name);
		$file_ext = strtolower(end($div));
		$unique_image = substr(md5(time()), 0, 10).'.'.$file_ext;
		$uploaded_image = "upload/".$unique_image;
		if ($productName == "" || $catId == "" || $brandId == "" || $body == "" || $price == "" || $type == "" ) {
			$msg = "<span class='error'>Field Must Not be empty .</span> ";
			return $msg;
		}else{
			move_uploaded_file($file_temp, $uploaded_image);
			$query = "INSERT INTO tbl_product(productName, catId, brandId, body, price, image, type) 
			VALUES ('$productName','$catId','$brandId','$body','$price','$uploaded_image','$type')";  

			$inserted_row = $this->db->insert($query);
			if ($inserted_row) {
				$msg = "<span class='success'>Product Inserted Successfully.</span> ";
				return $msg; 
			}else {
				$msg = "<span class='error'>Product Not Inserted .</span> ";
				return $msg; 
			}  

		} 

	}

	public function getAllProduct(){
		$query = "SELECT tbl_product.*, tbl_category.catName, tbl_brand.brandName
		FROM tbl_product
		INNER JOIN tbl_category
		ON tbl_product.catId = tbl_category.catId
		INNER JOIN tbl_brand
		ON tbl_product.brandId = tbl_brand.brandId
		ORDER BY tbl_product.productId DESC";
		$result =  $this->db->select($query);
		return $result; 
	}

	public function getProById($id){
		$query = "SELECT * FROM tbl_product WHERE productId ='$id' ";
		$result = $this->db->select($query);
		return $result;
	}

	public function productUpdate($data, $file, $id){

		$productName     =  mysqli_real_escape_string($this->db->link, $data['productName'] );
		$catId 	     =  mysqli_real_escape_string($this->db->link, $data['catId'] );
		$brandId 	     =  mysqli_real_escape_string($this->db->link, $data['brandId'] );
		$body 	     =  mysqli_real_escape_string($this->db->link, $data['body'] );
		$price  	     =  mysqli_real_escape_string($this->db->link, $data['price'] );
		$type 	     =  mysqli_real_escape_string($this->db->link, $data['type'] );

		$permited = array('jpg','png','jpeg','gif');
		$file_name = $file['image']['name'];
		$file_size = $file['image']['size'];
		$file_temp = $file['image']['tmp_name'];

		$div = explode('.', $file_name);
		$file_ext = strtolower(end($div));
		$unique_image = substr(md5(time()), 0, 10).'.'.$file_ext;
		$uploaded_image = "upload/".$unique_image;
		if ($productName == "" || $catId == "" || $brandId == "" || $body == "" || $price == "" || $type == "" ) {
			$msg = "<span class='error'>Field Must Not be empty .</span> ";
			return $msg;
		}else {
			if (!empty($file_name)) {
				if ($file_size > 1054589) {
					echo "<span class='error'>Image Size should be less then 1MB .</span>";
				}elseif (in_array($file_ext, $permited) === false) {
					echo "<span class='error'> You can Upload Only".implode(',', $permited)."</span>";
				} else{
					move_uploaded_file($file_temp, $uploaded_image);
					$query = "UPDATE tbl_product
					SET 
					productName 	= '$productName',
					catId 		= '$catId',
					brandId 		= '$brandId',
					body 			= '$body',
					price 		= '$price',
					image 		= '$uploaded_image',
					type 			= '$type'
					WHERE ProductID = '$id' ";

					$updated_row = $this->db->update($query);
					if ($updated_row) {
						$msg = "<span class='success'>Product Updated Successfully.</span> ";
						return $msg;
					}else {
						$msg = "<span class='error'>Product Not Updated .</span> ";
						return $msg;
					} 
				}

			} else{
				$query = "UPDATE tbl_product
				SET 
				productName 	= '$productName',
				catId 		= '$catId',
				brandId 		= '$brandId',
				body 			= '$body',
				price 		= '$price',

				type 			= '$type'
				WHERE ProductID = '$id' ";

				$updated_row = $this->db->update($query);
				if ($updated_row) {
					$msg = "<span class='success'>Product Updated Successfully.</span> ";
    			return $msg; // return This Message 
    		}else {
    			$msg = "<span class='error'>Product Not Updated .</span> ";
    			return $msg; // return This Message 
    		} 

    	}
    }

}

public function delPorById($id){
	$query = "SELECT * FROM tbl_product WHERE ProductID = '$id' ";
	$getData = $this->db->select($query);
	if ($getData) {
		while ($delImg = $getData->fetch_assoc()) {
			$dellink = $delImg['image'];
			unlink($dellink);
		}
	}

	$delquery = "DELETE FROM tbl_product WHERE productId = '$id' ";
	$deldata = $this->db->delete($delquery);
	if ($deldata) {
		$msg = "<span class='success'>Product Deleted Successfully.</span> ";
		return $msg;
	}else {
		$msg = "<span class='error'>Product Not Deleted .</span> ";
		return $msg;
	} 
}

public function getFeaturedProduct() {
	$query = "SELECT * FROM tbl_product WHERE type='0' ORDER BY ProductID DESC LIMIT 6 ";
	$result = $this->db->select($query);
	return $result;
}

public function getNewProduct(){
	$query = "SELECT * FROM tbl_product ORDER BY ProductID DESC LIMIT 4 ";
	$result = $this->db->select($query);
	return $result;
}

public function getSingleProduct($id){
	$query = "SELECT tbl_product.*, tbl_category.catName, tbl_brand.brandName
	FROM tbl_product
	INNER JOIN tbl_category
	ON tbl_product.catId = tbl_category.catId
	INNER JOIN tbl_brand
	ON tbl_product.brandId = tbl_brand.brandId
	AND tbl_product.ProductID = $id
	ORDER BY tbl_product.ProductID DESC";
	$result =  $this->db->select($query);
	return $result;   
}

public function latestFromAcer(){
	$query = "SELECT * FROM tbl_product WHERE brandId ='5' ORDER BY ProductID DESC LIMIT 1 ";
	$result = $this->db->select($query);
	return $result;
}

public function latestFromSamsung(){
	$query = "SELECT * FROM tbl_product WHERE brandId ='4' ORDER BY ProductID DESC LIMIT 1 ";
	$result = $this->db->select($query);
	return $result;
}

public function latestFromIph(){
	$query = "SELECT * FROM tbl_product WHERE brandId ='3' ORDER BY ProductID DESC LIMIT 1 ";
	$result = $this->db->select($query);
	return $result;
}

public function latestFromCan(){
	$query = "SELECT * FROM tbl_product WHERE brandId ='8' ORDER BY ProductID DESC LIMIT 1 ";
	$result = $this->db->select($query);
	return $result;
}
public function productByCat($id){
	$catId  =  mysqli_real_escape_string($this->db->link, $id);
	$query = "SELECT * FROM tbl_product WHERE catId ='$catId' ";
	$result = $this->db->select($query);
	return $result; 
} 
public function productByOnlyCat($id){
	$query = "SELECT * FROM tbl_category WHERE catId ='$id' ";
	$result = $this->db->select($query);
	return $result;
}




}


?>