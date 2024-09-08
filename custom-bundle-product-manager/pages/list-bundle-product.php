<?php 

global $wpdb;
$message = "";



if($_SERVER['REQUEST_METHOD'] == "POST")
{
    if((isset($_POST['b_product_delete_id'])) && (!empty($_POST['b_product_delete_id'])))

    $wpdb->delete("{$wpdb->prefix}cbpm_bundle_products", array('id' => intval($_POST['b_product_delete_id'])));

    $message = "Bundle Product deleted successfully.";
}

$b_products = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}cbpm_bundle_products", ARRAY_A);

?>


<div class="container">
  <div class="row">
    <div class="col-sm-12">
    <h2>List of Bundle Products</h2>
    <div class="panel panel-primary">
  <div class="panel-heading">List of Bundle Products</div>
  <div class="panel-body">
    <?php 
    if(!empty($message))
    {?>
        <div class="alert alert-success">
            <?php echo $message; ?>
        </div>
    <?php }
    ?>
  <table class="table table-striped" id="tbl-product">
               <thead>
                 <tr>
                   <th>Id</th>
                   <th>Product Name</th>
                   <th>Product Description</th>
                   <th>Price</th>
                   <th>Product Image</th>
                   <th>Child Products</th>
                   <th>Action</th>
                 </tr>
               </thead>
               <tbody>
                <?php 
                if(count($b_products) > 0)
                {
                    foreach($b_products as $b_product)
                    {?>
                         <tr>
                            <td><?php echo $b_product['id']?></td>
                            <td><?php echo $b_product['bundle_name']?></td>
                            <td><?php echo $b_product['bundle_description']?></td>
                            <td><?php echo $b_product['bundle_price']?></td>
                            <td><img src="<?php echo $b_product['bundle_image']?>" width="100px"></td>
                            <td><?php echo $b_product['bundle_products'];?></td>
                            <td>
                        <a href="admin.php?page=bundle-product&action=edit&id=<?php echo $b_product['id']?>" class="btn btn-warning">Edit</a>
                        <form id="fem-delete-product-<?php echo $b_product['id']?>" method="post" action="<?php echo $_SERVER['PHP_SELF']?>?page=list-bundle-product">
                            <input type="hidden" value="<?php echo $b_product['id']?>" name="b_product_delete_id">
                        </form>
                        <a href="javascript:void(0)" onclick="if(confirm('Are you sure want to delete?')){jQuery('#fem-delete-product-<?php echo $b_product['id']?>').submit();}" class="btn btn-danger">Delete</a>
                        <a href="admin.php?page=bundle-product&action=view&id=<?php echo $b_product['id']?>" class="btn btn-info">View</a>
                   </td>
                 </tr>
                    <?php }
                }
                else
                {
                    echo "No Bundle Products found";
                }
                
                ?>
               </tbody>
             </table>
  </div>
</div>           
    </div>
  </div>   
</div>


