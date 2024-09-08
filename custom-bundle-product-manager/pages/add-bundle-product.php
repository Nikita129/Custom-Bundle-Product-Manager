<?php 

$message = "";
$status = "";
$action = "";
global $wpdb;


// Check for Action for View & Edit
if(isset($_GET['action']) && (isset($_GET['id'])))
{
    $pId = $_GET['id'];
    // Action : Edit
    if($_GET['action'] == "edit")
    {
        $action = "edit";
        
    }
    // Action : View
    if($_GET['action'] == "view")
    {
        $action = "view";
    } 

    

    //Get single bundle product information

    $b_products = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}cbpm_bundle_products WHERE id = %d", $pId), ARRAY_A);

    

}


if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['btn_submit']))
{
    // save form data into table

    $p_name = sanitize_text_field($_POST['pname']);
    $p_desc = sanitize_text_field($_POST['pdesc']);
    $price = sanitize_text_field($_POST['price']);
    $image_url = sanitize_text_field($_POST['image-url']);

    

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $selectedOptions = $_REQUEST['bundle_products'];
      
        if (!empty($selectedOptions)) {
         foreach ($selectedOptions as $option) {
            $productAll = wc_get_product($option);
            $productAll_name = $productAll->get_name();
            $products[] = $productAll_name;
           
         }
        } else {
         echo 'No options selected.';
        }

    }

    $bundle_products =  implode(',', array_values($products));

    $table = $wpdb->prefix.'cbpm_bundle_products';
    $data = array(
        'bundle_name' => $p_name,
        'bundle_description' => $p_desc,
        'bundle_price' => $price,
        'bundle_image' => $image_url,
        'bundle_products' => $bundle_products
    );

   

    if($_GET['action'] == "edit" )
    {
        $bproduct_id = $_GET['id'];
        // Edit Operation
        $wpdb->update(
            $table,
            $data, 
            array(
                'id' => $bproduct_id
            ));

            $message = "Bundle Product updated successfully.";
            $status = 1;
    }
    else
    {
        // Add Operation
        $wpdb->insert($table,$data);

        $last_inserted_id = $wpdb->insert_id;

        if($last_inserted_id > 0)
        {
            $message = "Bundle Product saved successfully.";
            $status = 1;

            $product = new WC_Product_Simple();
            $product->set_name($p_name);
            $product->set_regular_price($price);
            $product->set_description($p_desc);
            $product->set_category_ids( array('17') );
            $product->set_short_description("This Bundle Products will include below Products:".'</br>'.$bundle_products);
            $product->save();
        }
        else
        {
            $message = "Failed to save Bundle Product.";
            $status = 0;
        }
    }
    


    
}
?>

<div class="container">
  <div class="row">
    <div class="col-sm-8">
    <h2>
        <?php 
        if($action == "edit")
        {
            echo "Edit Bundle Product";
        }
        elseif($action == "view")
        {
            echo "View Bundle Product";
        }
        else{
            echo "Add Bundle Product";
        }
        ?>
    </h2>
  <div class="panel panel-primary">
    <div class="panel-heading">
    <?php 
        if($action == "edit")
        {
            echo "Edit Bundle Product";
        }
        elseif($action == "view")
        {
            echo "View Bundle Product";
        }
        else{
            echo "Add Bundle Product";
        }
        ?>
    </div>
    <div class="panel-body">
    <?php 
    
    if(!empty($message))
    {
        if($status == 1)
        {?>
        <div class="alert alert-success">
            <?php echo $message; ?>
        </div>
       <?php  } else { ?>
        <div class="alert alert-danger">
            <?php echo $message; ?>
        </div>
       <?php }
        
    }
    ?>
   
   <form method="post" action="admin.php?page=bundle-product&action=add" id="bundle-product-form">

    <div class="form-group">
      <label for="pname">Product Name:</label>
      <input type="text" 
      class="form-control" 
      required 
      id="pname" 
      placeholder="Enter Bundle Product Name" 
      <?php if($action == 'view')
      { 
        echo "readonly";
      }?> 
      value="<?php 
      
      if($action == 'edit' || $action == 'view')
      {
        echo $b_products['bundle_name'];
      }
      
      ?>"
      name="pname">
    </div>

    <div class="form-group">
      <label for="pdesc">Product Description:</label>
      <input type="text" 
      class="form-control"
      required 
      id="pdesc" 
      placeholder="Enter Bundle Product Description"
      <?php if($action == 'view')
      { 
        echo "readonly";
      }?> 
      value="<?php 
      
      if($action == 'edit' || $action == 'view')
      {
        echo $b_products['bundle_description'];
      }
      
      ?>"
      name="pdesc">
    </div>

    <div class="form-group">
      <label for="price">Product Price:</label>
      <input type="text" 
      class="form-control" 
      id="price" 
      required
      placeholder="Enter Bundle Product Price" 
      <?php if($action == 'view')
      { 
        echo "readonly";
      }?> 
      value="<?php 
      
      if($action == 'edit' || $action == 'view')
      {
        echo $b_products['bundle_price'];
      }
      
      ?>"
      name="price">
    </div>

    <!-- Upload Button-->
    <div class="form-group">
        <input type="text" 
        name="image-url" 
        id="image-url" 
        readonly 
        class="form-control" 
        placeholder="Product Image URL"
        value="<?php 
      
            if($action == 'edit' || $action == 'view')
            {
                echo $b_products['bundle_image'];
            }
            
      ?>"
        >

        <?php if($action == 'view' || $action == 'edit'){?>
            <img src="<?php echo $b_products['bundle_image'];?>" width="100px" style="margin-top:10px;">
        <?php }?>
        <button id="btn-upload-image" class="btn btn-info" style="margin-top: 2%;">Upload Product Image</button>
    </div>
   

    <div class="form-group">
        <select id="bundle_products" name="bundle_products[]" multiple="multiple" class="form-control" required>
                    
        <?php
    
                global $wpdb;

                $all_product_data = $wpdb->get_results("SELECT ID,post_title,post_content,post_author,post_date_gmt FROM `" . $wpdb->prefix . "posts` where post_type='product' and post_status = 'publish'", ARRAY_A);

                echo "<pre>";
            // print_r($all_product_data);
                
                foreach($all_product_data as $a)
                {?>
                <option value="<?php echo $a['ID'];?>"><?php echo $a['post_title']; ?></option>
                <?php }
    
    
    ?>
        </select>
    </div>

    <button type="submit" class="btn btn-success" name="btn_submit" id="btn-bundle-form">Submit</button>
    
    
  </form>
    </div>
  </div>
    </div>
  </div>
</div>