<?php 
include 'inc/header.php';
include 'inc/sidebar.php';
include "../classes/category.php";

 ?>

 <?php
  if (!isset($_GET['catid'])  || $_GET['catid'] == NULL ) { // get this ID as catid
     echo "<script>window.location = 'catlist.php';  </script>"; // we transfer to catlist.php page
  }else {
    $id = $_GET['catid']; // Get this id from catadd.php and take this on $id variable.
  }
 ?> 

 <?php
 $cat=new category();
 if($_SERVER['REQUEST_METHOD']=='POST'){
    $catName = $_POST['catName'];
    $updatecat=$cat->catUpdate($catName,$id);
    if (isset($updateCat)) {
         echo $updateCat;
            }
    
 }
 ?>
        <div class="grid_10">
            <div class="box round first grid">
                <h2>Add New Category</h2>
               <div class="block copyblock"> 
                <?php
                $getcat=$cat->getCatbyId($id);
                if( $getcat){
                    while($result=$getcat->fetch_assoc()){
                
                ?>


                 <form action="" method="post">
                    <table class="form">					
                        <tr>
                            <td>
                                <input type="text"  class="medium"  name="catName" value="<?php echo $result['catName'] ?>"/>
                            </td>
                        </tr>
						<tr> 
                            <td>
                                <input type="submit" name="submit" Value="Save" />
                            </td>
                        </tr>
                    </table>
                    </form>
                <?php } }?>
                </div>
            </div>
        </div>
<?php include 'inc/footer.php';?>