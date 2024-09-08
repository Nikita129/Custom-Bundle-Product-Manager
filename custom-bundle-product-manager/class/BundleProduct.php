<?php 

class BundleProduct{

    public function __construct()
    {
        add_action("admin_menu", array($this, "addAdminmenu"));
        add_action("admin_enqueue_scripts", array($this, "cbpm_add_plugin_assets"));
        add_action("wp_ajax_cbpm_get_products", array( $this, "get_products" ) );

        // Create new product category Bundle Product

        wp_insert_term(
            'Bundle Product', // the term 
            'product_cat', // the taxonomy
            array(
              'description'=> 'Bundle Product',
              'slug' => 'bundle-product'
            )
          );

        // Get Category id by name

       

        
    }

    public function addAdminmenu()
    {
        // Add Top menu
        add_menu_page(
            "Bundle Product | Custom Bundle Product Manager",
            "Bundle Product",
            "manage_options",
            "bundle-product",
            array($this, "bundle_product"),
            "dashicons-cart",
            6
        );

        // Add Sub menu

        add_submenu_page(
            "bundle-product", 
            "Add Bundle Product | Custom Bundle Product Manager", 
            "Add Bundle Product", 
            "manage_options", 
            "bundle-product", 
            array($this, "bundle_product")
        );

        add_submenu_page(
            "bundle-product",
            "List Bundle Products | Custom Bundle Product Manager",
            "List Bundle Products",
            "manage_options",
            "list-bundle-product",
            array($this, "list_bundle_product")
        );
    }

    // Create Table function
    public function createBundleTable()
    {
        global $wpdb;

        $prefix = $wpdb->prefix;

        $sql = '
        CREATE TABLE `'.$prefix.'cbpm_bundle_products` (
            `id` mediumint(9) NOT NULL AUTO_INCREMENT,
            `bundle_name` varchar(255) NOT NULL,
            `bundle_description` text NOT NULL,
            `bundle_price` decimal(10,2) NOT NULL,
            `bundle_image` varchar(255) NOT NULL,
            `bundle_products` varchar(500) NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
        ';

        include_once ABSPATH.'wp-admin/includes/upgrade.php';

        dbDelta($sql);
    }

    // Drop table function
    public function deleteBundleTable()
    {
        global $wpdb;

        $prefix = $wpdb->prefix;

        $sql = 'DROP TABLE IF EXISTS `'.$prefix.'cbpm_bundle_products`';

        $wpdb->query($sql);
    }

    // Add Bundle Product Function
    public function bundle_product()
    {
        include_once CBPM_PLUGIN_PATH.'pages/add-bundle-product.php';
    }

    // List Bundle Products Function

    public function list_bundle_product()
    {
        include_once CBPM_PLUGIN_PATH.'pages/list-bundle-product.php';
    }

    public function get_products() {
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => -1
        );
        $products = get_posts( $args );
        $product_list = array();

        foreach ( $products as $product ) {
            $product_list[] = array(
                'id' => $product->ID,
                'text' => $product->post_title
            );
        }

        wp_send_json( $product_list );
    }

    function cbpm_add_plugin_assets()
    {
        wp_enqueue_style("cbpm-bootstrap-css", CBPM_PLUGIN_URL."css/bootstrap.min.css", array(), "1.0.0", "all");
        wp_enqueue_style("cbpm-dataTables-css", CBPM_PLUGIN_URL."css/dataTables.dataTables.min.css", array(), "1.0.0", "all");
        wp_enqueue_style("cbpm-custom-css", CBPM_PLUGIN_URL."css/custom.css", array(), "1.0.0", "all");
        wp_enqueue_script("cbpm-bootstrap-js", CBPM_PLUGIN_URL."js/bootstrap.min.js", array("jquery"), "1.0.0");
        wp_enqueue_script("cbpm-datatable-js", CBPM_PLUGIN_URL."js/dataTables.min.js", array("jquery"), "1.0.0");
        wp_enqueue_script("cbpm-custom-js", CBPM_PLUGIN_URL."js/custom.js", array("jquery"), "1.0.0");
        wp_enqueue_script("cbpm-validation-js", CBPM_PLUGIN_URL."js/jquery.validate.min.js", array("jquery"), "1.0.0");
        wp_add_inline_script("cbpm-validation-js", file_get_contents(CBPM_PLUGIN_URL."js/custom.js"));
 }

}

?>