<?php
    /**
     * Plugin Name: Nochex Payment for s2Member
     * Plugin URI: https://your-website.com
     * Description: Integrates Nochex payment gateway with s2Member.
     * Version: 1.0.0
     * Author: GMS
     * Author URI: https://your-website.com
     */



    define('C_NOCHEX_PLUGIN_URL',plugin_dir_path(__FILE__));
    define("C_NOCHEX_ADMIN_URL",get_admin_url());


    // for security 
    if(!defined('ABSPATH')){
        header("Location:/wp_test");
        die();
    }

    // Count rows and fetch data from the WordPress database
     function count_and_fetch_data_from_database() {

        global $wpdb,$table_prefix;
        $c_nochex_table = $table_prefix.'custom_nochex';

        // Count rows
        $row_count = $wpdb->get_var("SELECT COUNT(*) FROM $c_nochex_table");

        // Fetch data if rows exist
        if ($row_count > 0) {
            $query = "SELECT * FROM $c_nochex_table";
            $results = $wpdb->get_results($query);

            // Return the row count and data
            return array(
                'row_count' => $row_count,
                'results' => $results
            );
        } else {
            // No rows found
            return array(
                'row_count' => 0,
                'results' => array()
            );
        }
    }

    // Usage example
    $data = count_and_fetch_data_from_database();
    $row_count = $data['row_count'];
    $results = $data['results'];

    // Check if rows exist
    if ($row_count > 0) {

        define('C_NOCHEX_MERCHANT_ID',$results[0]->merchant_id);
        define('C_NOCHEX_TEST_SUCCESS_URL',$results[0]->test_success_url);
        define('C_NOCHEX_SUCCESS_URL',$results[0]->success_url);
        define('C_NOCHEX_CANCEL_URL',$results[0]->cancel_url);
        define('C_NOCHEX_CALLBACK_URL',$results[0]->callback_url);
           
    } else {
        // No rows found
       // echo 'No rows found.';

        define('C_NOCHEX_MERCHANT_ID','');
        define('C_NOCHEX_TEST_SUCCESS_URL','');
        define('C_NOCHEX_SUCCESS_URL','');
        define('C_NOCHEX_CANCEL_URL','');
        define('C_NOCHEX_CALLBACK_URL','');
    }



    // Register the plugin activation hook
    register_activation_hook(__FILE__, 'nochex_s2member_plugin_activation');

    // Register the plugin deactivation hook
    register_deactivation_hook(__FILE__, 'nochex_s2member_plugin_deactivation');

    // Plugin activation function
    function nochex_s2member_plugin_activation() {
        // Add any activation tasks here

    global $wpdb,$table_prefix;
    $c_nochex_table = $table_prefix.'custom_nochex';
      
    $qry = "CREATE TABLE IF NOT EXISTS $c_nochex_table(`id` INT NOT NULL AUTO_INCREMENT , `merchant_id` VARCHAR(255) NOT NULL , `test_success_url` VARCHAR(255) NOT NULL , `success_url` VARCHAR(255) NOT NULL , `cancel_url` VARCHAR(255) NOT NULL , `callback_url` VARCHAR(255) NOT NULL , `add_on` TIMESTAMP NOT NULL , PRIMARY KEY (`id`)) ENGINE = InnoDB;
       ";
     $wpdb->query($qry);
    }

    // Plugin deactivation function
    function nochex_s2member_plugin_deactivation() {
        // Add any deactivation tasks here

        global $wpdb,$table_prefix;
        $c_nochex_table = $table_prefix.'custom_nochex';

        $qry = "TRUNCATE $c_nochex_table";
        $wpdb->query($qry);
    }

    // Register the plugin menu
    add_action('admin_menu', 'nochex_s2member_plugin_menu');

    // Add menu page
    function nochex_s2member_plugin_menu() {
        add_options_page(
            'Nochex s2Member Integration',
            'Nochex s2Member',
            'manage_options',
            'nochex-s2member-settings',
            'nochex_s2member_plugin_settings_page'
        );
    }

    // Render the plugin settings page
    function nochex_s2member_plugin_settings_page() {
    include_once 'admin/dashboard.php';
    }


    // include(C_NOCHEX_PLUGIN_URL.'admin/header.php');

        // Add the [nochex_s2member_payment_form] shortcode

        add_shortcode('nochex_s2member_payment_form', 'nochex_s2member_payment_form_shortcode');

     // ###################### Payment monthly form shortcode function ########################################

        function nochex_s2member_payment_form_shortcode($atts) {
        // Check if Nochex integration is enabled
        $enabled = get_option('nochex_s2member_enabled', false);
        if (!$enabled) {
            return '<p>Nochex integration is currently disabled.</p>';
        }
         

         // Get the current user object
        $current_user = wp_get_current_user();

        // Access user data
        $user_id = $current_user->ID; // User ID
        $user_login = $current_user->user_login; // User login (username)
        $user_email = $current_user->user_email; // User email
        $user_name = $current_user->display_name; // User display name
        $user_roles = $current_user->roles; // User roles (array)

      // Generate dynamic invoice number
        function generate_invoice_number() {
            $prefix = 'INV'; // Prefix for the invoice number
            $unique_id = $user_id; // Generate a unique identifier
            $timestamp = time(); // Get the current timestamp

            $invoice_number = $prefix . '_' . $timestamp;

            return $invoice_number;
        }


        // Usage example
        $invoice_number = generate_invoice_number();
       

 


     // ####################### First form #####################################


          $form_html = '<h3 style="padding-top:20px; margin:0px;">BILLING DETAILS</h3>';
          $form_html .= '<p><span style="color:red;font-size:14px">'. @$msg.'</span></p>';
          $form_html .= '<form action="" method="post" id="nochex_billing_details_form">';
          $form_html .= '<table id="userFormTable" style="width:530px;">';
          $form_html .= '<tr class="custom_form_table_row"><td style="padding-left:2.5px;padding-right:2.5px;margin-left:2.5px;,margin-right:2.5px;width:50%;"><input style="width:95%!important;height:27px; font-size:13px;padding-left:5px;" type="text" name="first_name" placeholder="First Name" id="custom_value_field1"></td><td style="padding-left:2.5px;padding-right:2.5px;margin-left:2.5px;,margin-right:2.5px;width:50%;"><input style="width:95%!important;height:27px; font-size:13px;padding-left:5px;" type="text" name="last_name" placeholder="Last Name"id="custom_value_field2"></td></tr>';

          $form_html .= '<tr class="custom_form_table_row"><td style="padding-left:2.5px;padding-right:2.5px;margin-left:2.5px;,margin-right:2.5px;width:50%;"><input style="width:95%!important;height:27px;font-size:13px;padding-left:5px;"  type="email" name="email" placeholder="Email" id="custom_value_field3"></td><td style="padding-left:2.5px;padding-right:2.5px;margin-left:2.5px;,margin-right:2.5px;width:50%;"><input style="width:95%!important;height:27px;font-size:13px;padding-left:5px;"  type="text" name="phone" placeholder="Phone" id="custom_value_field4"></td></tr>';
 
          $form_html .= '<tr class="custom_form_table_row"><td style="padding-left:2.5px;padding-right:2.5px;margin-left:2.5px;,margin-right:2.5px;width:50%;"><div style="position:relative;"><input style="width:84%;height:27px;font-size:13px;padding-left:33px;"  type="text" name="card_number" placeholder="Card Number" id="custom_value_field5"><img src="https://amanda-swan.com/wp-content/uploads/2023/06/cardicon.png" id="image_cont" style="width:30px;border:none;position:absolute;left:5px;top:4px;height:25px;" /></div></td><td style="width:32.3%; padding:0px;height:51px;"><table id="userFormTable2" style="width:100%; height:51px; margin:0px!important;">';
           $form_html .= '<tr style="width:100%;"><td class="card_expiry_details" style="width:30%; padding-left:2.5px;padding-right:2.5px;margin-left:2.5px;,margin-right:2.5px;"><select name="month" id="card_month" style="width:100%;background: #ffffff;font-size:13px;padding-left:5px;height:32px;margin-top:1px;">';
           $form_html .='<option value="month">Month</option>' ; 
           for ($i=1; $i <=12 ; $i++) { 
             $form_html .='<option value="'.$i.'" style="font-size:13px;padding-left:5px;">'.$i.'</option>' ; 
           }
           $form_html .='</select></td><td class="card_expiry_details" style="width:30%;padding-left:2.5px;padding-right:2.5px;margin-left:2.5px;,margin-right:2.5px;">';

            $form_html .='<select name="year" id="card_year" style="width:100%;background: #ffffff;font-size:13px;padding-left:5px;height:32px;">';
           $form_html .='<option value="year">Year</option>' ; 
           for ($k=2023; $k <=2050 ; $k++) { 
             $form_html .='<option value="'.$k.'" style="font-size:13px;padding-left:5px;">'.$k.'</option>' ; 
           }


           $form_html .='</td><td style="width:20%;padding-left:2.5px;padding-right:2.5px;margin-left:2.5px;,margin-right:2.5px;">';
           $form_html .= '<input style="width:80%!important;height:27px;font-size:13px;padding-left:5px;"  type="text" name="csv" placeholder="CSV" id="custom_value_field6">';
           $form_html .='</td></tr>' ; 
           $form_html .= '</table></td>';
           $form_html .=  '</tr>';

          $form_html .= '<tr class="custom_form_table_row"><td style="padding-left:2.5px;padding-right:2.5px;margin-left:2.5px;,margin-right:2.5px;width:50%;"><input style="width:95%!important;height:27px;font-size:13px;padding-left:5px;"  type="text" name="country_name" placeholder="Country Name" id="custom_value_field7"></td><td style="padding-left:2.5px;padding-right:2.5px;margin-left:2.5px;,margin-right:2.5px;width:50%;"><input style="width:95%!important;height:27px;font-size:13px;padding-left:5px;"  type="text" name="state" placeholder="State" id="custom_value_field8"></td></tr>';

          $form_html .= '<tr class="custom_form_table_row"><td style="padding-left:2.5px;padding-right:2.5px;margin-left:2.5px;,margin-right:2.5px;width:50%;"><input style="width:95%!important;height:27px;font-size:13px;padding-left:5px;"  type="text" name="town_city" placeholder="Town/City" id="custom_value_field9"></td><td style="padding-left:2.5px;padding-right:2.5px;margin-left:2.5px;,margin-right:2.5px;width:50%;"><input style="width:95%!important;height:27px;font-size:13px;padding-left:5px;"  type="text" name="street_name" placeholder="House Number and Street Name" id="custom_value_field10"></td></tr>';

           $form_html .= '<tr class="custom_form_table_row"><td style="padding-left:2.5px;padding-right:2.5px;margin-left:2.5px;,margin-right:2.5px;width:50%;"><input style="width:95%!important;height:27px;font-size:13px;padding-left:5px;"  type="text" name="apartment_suite_unit" placeholder="Apartment/Suite/Unit (optional)" id="custom_value_field11"></td><td style="padding-left:2.5px;padding-right:2.5px;margin-left:2.5px;,margin-right:2.5px;width:50%;"><input style="width:95%!important;height:27px;font-size:13px;padding-left:5px;"  type="text" name="postcode_zip" placeholder="Postcode/ZIP" id="custom_value_field12"></td></tr>';

           
          $form_html .= '<tr class="custom_form_table_row"><td style="width:50%;padding: 5px 0px; position:relative;"><small class="custom_small_text" style="font-size:13px;width:100%; position:absolute;color: black;left:0px;top: 35%;">Pay with Nochex, Press the button below</small></td><td class="custom_image_logo" style="padding: 5px 0px;"><img src="https://amanda-swan.com/wp-content/uploads/2023/06/image-38-1.png" height="70%" widhth="100%"></td></tr>';

          $form_html .= '</table>';

          $form_html .= '</form>';
        
          
        //    return  $form_html;

     // ####################### End First form #####################################

     // ####################### Get the First form value #####################################
?>
<script defer='true'>

     setTimeout(()=>{
        //   console.log(document.getElementById('custom_value_field5'));
         var x_array = Array();
    // document.getElementById("image_cont").style.display = "none";
    document.getElementById("custom_value_field5").addEventListener("keyup",function(){
        // alert("set");
        var val = document.getElementById("custom_value_field5").value;
        if(val == "") {
            x_array = [];
        }
        else {
            x_array.push(val);
        }
       
        switch(x_array[0]) {
            case "2":
            document.getElementById("image_cont").style.display = "block";
            document.getElementById("image_cont").setAttribute("src","https://amanda-swan.com/wp-content/uploads/2023/06/mastercard.png");
            break;
            case "3":
            document.getElementById("image_cont").style.display = "block";
            document.getElementById("image_cont").setAttribute("src","https://amanda-swan.com/wp-content/uploads/2023/06/american.png");
            break;
            case "4":
            document.getElementById("image_cont").style.display = "block";
            document.getElementById("image_cont").setAttribute("src","https://amanda-swan.com/wp-content/uploads/2023/06/visa.png");
            break;
            case "5":
            document.getElementById("image_cont").style.display = "block";
            document.getElementById("image_cont").setAttribute("src","https://amanda-swan.com/wp-content/uploads/2023/06/mastercard.png");
            break;
            case "6":
            document.getElementById("image_cont").style.display = "block";
            document.getElementById("image_cont").setAttribute("src","https://amanda-swan.com/wp-content/uploads/2023/06/discover.png");
            break;
            default:
            document.getElementById("image_cont").style.display = "block";
            document.getElementById("image_cont").setAttribute("src","https://amanda-swan.com/wp-content/uploads/2023/06/cardicon.png");
            
            break;
        }
    })
     },100);  
   
</script>
<script>
	
	// Here the value is stored in new variable x
	function valueFunction() {
		var first_name = document.getElementById("custom_value_field1").value;
		var last_name = document.getElementById("custom_value_field2").value;
		var email = document.getElementById("custom_value_field3").value;
		var phone = document.getElementById("custom_value_field4").value;
		var card_number = document.getElementById("custom_value_field5").value;
        var card_month = document.getElementById("card_month").value;
        var card_year = document.getElementById("card_year").value;
		var csv = document.getElementById("custom_value_field6").value;
		var country_name = document.getElementById("custom_value_field7").value;
		var state = document.getElementById("custom_value_field8").value;
		var town_city = document.getElementById("custom_value_field9").value;
		var address_first = document.getElementById("custom_value_field10").value;
		var address_second = document.getElementById("custom_value_field11").value;
		var postcode = document.getElementById("custom_value_field12").value;

        var full_name= first_name+" "+last_name;
        var billing_address= address_first+" "+address_second;
         
        document.getElementById("billing_fullname_field").value = full_name;
        document.getElementById("billing_address_field").value = billing_address;
        document.getElementById("billing_city_field").value = town_city;
        document.getElementById("billing_country_field").value = country_name;
        document.getElementById("billing_postcode_field").value = postcode;
        document.getElementById("delivery_fullname_field").value = full_name;
        document.getElementById("delivery_address_field").value = billing_address;
        document.getElementById("delivery_city_field").value = town_city;
        document.getElementById("delivery_country_field").value = country_name;
        document.getElementById("delivery_postcode_field").value = postcode;
        document.getElementById("email_address_field").value = email;
        document.getElementById("customer_phone_number").value = phone;
        document.getElementById("cust_card_number_field").value = card_number;
        
       
	}
	</script>

<?php
     
// ####################### Get the First form value #####################################

   
     
    /* Nochex Payment Form - Fields & Values */

    $c_nochexParams = array('Nochex_Settings' => Array(
                'Merchant_id' =>  C_NOCHEX_MERCHANT_ID,
                'test_transaction' => 0,
                // 'hid3e_billing_details' => $hide_billing_details,
                // 'xml_item_collection' => $item_collect,
                // 'postage' => $amountPostageTotal,
            ),
            'Nochex_Urls' => Array(
                'test_success_url' => C_NOCHEX_TEST_SUCCESS_URL,
                'success_url' => C_NOCHEX_SUCCESS_URL,
                'cancel_url' => C_NOCHEX_CANCEL_URL,
                'callback_url' => C_NOCHEX_CALLBACK_URL,
            ),
            'order_info' => Array(
                'order_id' =>$invoice_number,
                // 'optional_1' => serialize( array( $order_id, $orders->get_order_key() ) ),
                // 'optional_2' => esc_html($optional_2),
                'amount' => 25,
                'description' => 'Order for #'.$invoice_number,
            ),
            
            );


            $form_html .= '<form action="https://secure.nochex.com/default.aspx" method="post" id="nochex_payment_form">';
            $form_html .= '<input type="hidden" name="merchant_id" id="merchant_id_field" value="'. $c_nochexParams["Nochex_Settings"]["Merchant_id"].'" />';
            $form_html .= '<input type="hidden" name="amount" id="amount_field" value="'. $c_nochexParams["order_info"]["amount"] .'" />';
            $form_html .= '<input type="hidden" name="postage" id="postage_field" value="'. $c_nochexParams["Nochex_Settings"]["postage"].'" />';
            $form_html .= '<input type="hidden" name="xml_item_collection" id="xml_item_collection_field" value="'. $c_nochexParams["Nochex_Settings"]["xml_item_collection"].'" />';
            $form_html .= '<input type="hidden" name="description" id="description_field" value="'. $c_nochexParams["order_info"]["description"].'" />';
            $form_html .= '<input type="hidden" name="order_id" id="order_id_field" value="'. $c_nochexParams["order_info"]["order_id"] .'" />';
            $form_html .= '<input type="hidden" name="optional_1" id="optional_1_field" value="'. $c_nochexParams["order_info"]["optional_1"] .'" />';
            $form_html .= '<input type="hidden" name="optional_2" id="optional_2_field" value="'. $c_nochexParams["order_info"]["optional_2"] .'" />';
            $form_html .= '<input type="hidden" name="billing_fullname" id="billing_fullname_field" value=" " />';
            $form_html .= '<input type="hidden" name="billing_address" id="billing_address_field" value=" " />';
            $form_html .= '<input type="hidden" name="billing_city" id="billing_city_field" value=" " />';
            $form_html .= '<input type="hidden" name="billing_country" id="billing_country_field" value="" />';
            $form_html .= '<input type="hidden" name="billing_postcode" id="billing_postcode_field" value=" " />';
            $form_html .= '<input type="hidden" name="delivery_fullname" id="delivery_fullname_field" value=" " />';
            $form_html .= '<input type="hidden" name="delivery_address" id="delivery_address_field" value=" " />';
            $form_html .= '<input type="hidden" name="delivery_city" id="delivery_city_field" value=" " />';
            $form_html .= '<input type="hidden" name="delivery_country" id="delivery_country_field" value=" " />';
            $form_html .= '<input type="hidden" name="delivery_postcode" id="delivery_postcode_field" value=" " />';
            $form_html .= '<input type="hidden" name="email_address" id="email_address_field" value=" " />';
            $form_html .= '<input type="hidden" name="customer_phone_number" id="customer_phone_number" value=" " />';
            $form_html .= '<input type="hidden" name="cust_card_number" id="cust_card_number_field" value=" " />';
            $form_html .= '<input type="hidden" name="success_url" id="success_url_field" value="'. $c_nochexParams["Nochex_Urls"]["success_url"] .'" />';     
            $form_html .= '<input type="hidden" name="hide_billing_details" id="hide_billing_details_field" value="'. $c_nochexParams["Nochex_Settings"]["hide_billing_details"] .'" /> ';      
            $form_html .= '<input type="hidden" name="callback_url" id="callback_url_field" value="'. $c_nochexParams["Nochex_Urls"]["callback_url"] .'" />';
            $form_html .= '<input type="hidden" name="cancel_url" id="cancel_url_field" value="'. $c_nochexParams["Nochex_Urls"]["cancel_url"] .'" />';
            $form_html .= '<input type="hidden" name="test_success_url" id="test_success_url_field" value="'. $c_nochexParams["Nochex_Urls"]["test_success_url"] .'" />';
            $form_html .= '<input type="hidden" name="test_transaction" id="test_transaction_field" value="'. $c_nochexParams["Nochex_Settings"]["test_transaction"] .'" />';
            $form_html .= '<div class"payment_btn" style="margin-top: 20px; height: auto; width: 50%; position:relative;"><img src="https://amanda-swan.com/wp-content/uploads/2023/06/New-Project-4.png" height:auto; width:100%;><input type="submit" class="button-alt" style="height: 90%; width:96%; cursor: pointer; border: none; left: 0; background: transparent;  position:absolute " onclick="valueFunction()" id="c_submit_nochex_payment_form" name="submit_1" value=" "/></div>';
        
        $form_html .= '</form>';
         return  $form_html;
    }




        // ######################## End Payment monthly form shortcode function ###################################





     

        // ######################## Start Payment Yearly form shortcode function  ###############################

    // Add the [nochex_s2member_payment_form_yearly] shortcode

        add_shortcode('nochex_s2member_payment_form_yearly', 'nochex_s2member_payment_form_yearly_shortcode');

       function nochex_s2member_payment_form_yearly_shortcode($atts) {
        // Check if Nochex integration is enabled
    $enabled = get_option('nochex_s2member_enabled', false);
        if (!$enabled) {
            return '<p>Nochex integration is currently disabled.</p>';
        }
         

         // Get the current user object
        $current_user = wp_get_current_user();

        // Access user data
        $user_id = $current_user->ID; // User ID
        $user_login = $current_user->user_login; // User login (username)
        $user_email = $current_user->user_email; // User email
        $user_name = $current_user->display_name; // User display name
        $user_roles = $current_user->roles; // User roles (array)

      // Generate dynamic invoice number
        function generate_invoice_number() {
            $prefix = 'INV'; // Prefix for the invoice number
            $unique_id = $user_id; // Generate a unique identifier
            $timestamp = time(); // Get the current timestamp

            $invoice_number = $prefix . '_' . $timestamp;

            return $invoice_number;
        }


        // Usage example
        $invoice_number = generate_invoice_number();
       

 


     // ####################### First form #####################################


          $form_html = '<h3 style="padding-top:20px; margin:0px;">BILLING DETAILS</h3>';
          $form_html .= '<p><span style="color:red;font-size:14px">'. @$msg.'</span></p>';
          $form_html .= '<form action="" method="post" id="nochex_billing_details_form">';
          $form_html .= '<table id="userFormTable" style="width:530px;">';
          $form_html .= '<tr class="custom_form_table_row"><td style="padding-left:2.5px;padding-right:2.5px;margin-left:2.5px;,margin-right:2.5px;width:50%;"><input style="width:95%!important;height:27px; font-size:13px;padding-left:5px;" type="text" name="first_name" placeholder="First Name" id="custom_value_field1"></td><td style="padding-left:2.5px;padding-right:2.5px;margin-left:2.5px;,margin-right:2.5px;width:50%;"><input style="width:95%!important;height:27px; font-size:13px;padding-left:5px;" type="text" name="last_name" placeholder="Last Name"id="custom_value_field2"></td></tr>';

          $form_html .= '<tr class="custom_form_table_row"><td style="padding-left:2.5px;padding-right:2.5px;margin-left:2.5px;,margin-right:2.5px;width:50%;"><input style="width:95%!important;height:27px;font-size:13px;padding-left:5px;"  type="email" name="email" placeholder="Email" id="custom_value_field3"></td><td style="padding-left:2.5px;padding-right:2.5px;margin-left:2.5px;,margin-right:2.5px;width:50%;"><input style="width:95%!important;height:27px;font-size:13px;padding-left:5px;"  type="text" name="phone" placeholder="Phone" id="custom_value_field4"></td></tr>';


          $form_html .= '<tr class="custom_form_table_row"><td style="padding-left:2.5px;padding-right:2.5px;margin-left:2.5px;,margin-right:2.5px;width:50%;"><input style="width:95%!important;height:27px;font-size:13px;padding-left:5px;"  type="text" name="card_number" placeholder="Card Number" id="custom_value_field5"></td><td style="width:32.3%; padding:0px;height:51px;"><table id="userFormTable2" style="width:100%; height:51px; margin:0px!important;">';
           $form_html .= '<tr style="width:100%;"><td class="card_expiry_details" style="width:30%; padding-left:2.5px;padding-right:2.5px;margin-left:2.5px;,margin-right:2.5px;"><select name="month" id="card_month" style="width:100%;background: #ffffff;font-size:13px;padding-left:5px;height:32px;margin-top:1px;">';
           $form_html .='<option value="month">Month</option>' ; 
           for ($i=1; $i <=12 ; $i++) { 
             $form_html .='<option value="'.$i.'" style="font-size:13px;padding-left:5px;">'.$i.'</option>' ; 
           }
           $form_html .='</select></td><td  class="card_expiry_details" style="width:30%;padding-left:2.5px;padding-right:2.5px;margin-left:2.5px;,margin-right:2.5px;">';

            $form_html .='<select name="year" id="card_year" style="width:100%;background: #ffffff;font-size:13px;padding-left:5px;height:32px;">';
           $form_html .='<option value="year">Year</option>' ; 
           for ($k=2023; $k <=2050 ; $k++) { 
             $form_html .='<option value="'.$k.'" style="font-size:13px;padding-left:5px;">'.$k.'</option>' ; 
           }


           $form_html .='</td><td style="width:20%;padding-left:2.5px;padding-right:2.5px;margin-left:2.5px;,margin-right:2.5px;">';
           $form_html .= '<input style="width:80%!important;height:27px;font-size:13px;padding-left:5px;"  type="text" name="csv" placeholder="CSV" id="custom_value_field6">';
           $form_html .='</td></tr>' ; 
           $form_html .= '</table></td>';
           $form_html .=  '</tr>';

          $form_html .= '<tr class="custom_form_table_row"><td style="padding-left:2.5px;padding-right:2.5px;margin-left:2.5px;,margin-right:2.5px;width:50%;"><input style="width:95%!important;height:27px;font-size:13px;padding-left:5px;"  type="text" name="country_name" placeholder="Country Name" id="custom_value_field7"></td><td style="padding-left:2.5px;padding-right:2.5px;margin-left:2.5px;,margin-right:2.5px;width:50%;"><input style="width:95%!important;height:27px;font-size:13px;padding-left:5px;"  type="text" name="state" placeholder="State" id="custom_value_field8"></td></tr>';

          $form_html .= '<tr class="custom_form_table_row"><td style="padding-left:2.5px;padding-right:2.5px;margin-left:2.5px;,margin-right:2.5px;width:50%;"><input style="width:95%!important;height:27px;font-size:13px;padding-left:5px;"  type="text" name="town_city" placeholder="Town/City" id="custom_value_field9"></td><td style="padding-left:2.5px;padding-right:2.5px;margin-left:2.5px;,margin-right:2.5px;width:50%;"><input style="width:95%!important;height:27px;font-size:13px;padding-left:5px;"  type="text" name="street_name" placeholder="House Number and Street Name" id="custom_value_field10"></td></tr>';

           $form_html .= '<tr class="custom_form_table_row"><td style="padding-left:2.5px;padding-right:2.5px;margin-left:2.5px;,margin-right:2.5px;width:50%;"><input style="width:95%!important;height:27px;font-size:13px;padding-left:5px;"  type="text" name="apartment_suite_unit" placeholder="Apartment/Suite/Unit (optional)" id="custom_value_field11"></td><td style="padding-left:2.5px;padding-right:2.5px;margin-left:2.5px;,margin-right:2.5px;width:50%;"><input style="width:95%!important;height:27px;font-size:13px;padding-left:5px;"  type="text" name="postcode_zip" placeholder="Postcode/ZIP" id="custom_value_field12"></td></tr>';

           
          $form_html .= '<tr class="custom_form_table_row"><td style="width:50%;padding: 5px 0px; position:relative;"><small class="custom_small_text" style="font-size:13px;width:100%; position:absolute;color: black;left:0px; top: 35%;">Pay with Nochex, Press the button below</small></td><td class="custom_image_logo" style="padding: 5px 0px;"><img src="https://amanda-swan.com/wp-content/uploads/2023/06/image-38-1.png" height="70%" widhth="100%"></td></tr>';

          $form_html .= '</table>';

          $form_html .= '</form>';
        
          
        //    return  $form_html;

     // ####################### End First form #####################################

     // ####################### Get the First form value #####################################
?>
<script>
	
	// Here the value is stored in new variable x
	function valueFunction() {
		var first_name = document.getElementById("custom_value_field1").value;
		var last_name = document.getElementById("custom_value_field2").value;
		var email = document.getElementById("custom_value_field3").value;
		var phone = document.getElementById("custom_value_field4").value;
		var card_number = document.getElementById("custom_value_field5").value;
        var card_month = document.getElementById("card_month").value;
        var card_year = document.getElementById("card_year").value;
		var csv = document.getElementById("custom_value_field6").value;
		var country_name = document.getElementById("custom_value_field7").value;
		var state = document.getElementById("custom_value_field8").value;
		var town_city = document.getElementById("custom_value_field9").value;
		var address_first = document.getElementById("custom_value_field10").value;
		var address_second = document.getElementById("custom_value_field11").value;
		var postcode = document.getElementById("custom_value_field12").value;

        var full_name= first_name+" "+last_name;
        var billing_address= address_first+" "+address_second;

        document.getElementById("billing_fullname_field").value = full_name;
        document.getElementById("billing_address_field").value = billing_address;
        document.getElementById("billing_city_field").value = town_city;
        document.getElementById("billing_country_field").value = country_name;
        document.getElementById("billing_postcode_field").value = postcode;
        document.getElementById("delivery_fullname_field").value = full_name;
        document.getElementById("delivery_address_field").value = billing_address;
        document.getElementById("delivery_city_field").value = town_city;
        document.getElementById("delivery_country_field").value = country_name;
        document.getElementById("delivery_postcode_field").value = postcode;
        document.getElementById("email_address_field").value = email;
        document.getElementById("customer_phone_number").value = phone;
        
       
	}
	</script>

<?php
     
// ####################### Get the First form value #####################################

   
     
    /* Nochex Payment Form - Fields & Values */

    $c_nochexParams = array('Nochex_Settings' => Array(
                'Merchant_id' =>  C_NOCHEX_MERCHANT_ID,
                'test_transaction' => 0,
                // 'hid3e_billing_details' => $hide_billing_details,
                // 'xml_item_collection' => $item_collect,
                // 'postage' => $amountPostageTotal,
            ),
            'Nochex_Urls' => Array(
                'test_success_url' => C_NOCHEX_TEST_SUCCESS_URL,
                'success_url' => C_NOCHEX_SUCCESS_URL,
                'cancel_url' => C_NOCHEX_CANCEL_URL,
                'callback_url' => C_NOCHEX_CALLBACK_URL,
            ),
            'order_info' => Array(
                'order_id' =>$invoice_number,
                // 'optional_1' => serialize( array( $order_id, $orders->get_order_key() ) ),
                // 'optional_2' => esc_html($optional_2),
                'amount' => 149,
                'description' => 'Order for #'.$invoice_number,
            ),
            
            );


            $form_html .= '<form action="https://secure.nochex.com/default.aspx" method="post" id="nochex_payment_form">';
            $form_html .= '<input type="hidden" name="merchant_id" id="merchant_id_field" value="'. $c_nochexParams["Nochex_Settings"]["Merchant_id"].'" />';
            $form_html .= '<input type="hidden" name="amount" id="amount_field" value="'. $c_nochexParams["order_info"]["amount"] .'" />';
            $form_html .= '<input type="hidden" name="postage" id="postage_field" value="'. $c_nochexParams["Nochex_Settings"]["postage"].'" />';
            $form_html .= '<input type="hidden" name="xml_item_collection" id="xml_item_collection_field" value="'. $c_nochexParams["Nochex_Settings"]["xml_item_collection"].'" />';
            $form_html .= '<input type="hidden" name="description" id="description_field" value="'. $c_nochexParams["order_info"]["description"].'" />';
            $form_html .= '<input type="hidden" name="order_id" id="order_id_field" value="'. $c_nochexParams["order_info"]["order_id"] .'" />';
            $form_html .= '<input type="hidden" name="optional_1" id="optional_1_field" value="'. $c_nochexParams["order_info"]["optional_1"] .'" />';
            $form_html .= '<input type="hidden" name="optional_2" id="optional_2_field" value="'. $c_nochexParams["order_info"]["optional_2"] .'" />';
            $form_html .= '<input type="hidden" name="billing_fullname" id="billing_fullname_field" value=" " />';
            $form_html .= '<input type="hidden" name="billing_address" id="billing_address_field" value=" " />';
            $form_html .= '<input type="hidden" name="billing_city" id="billing_city_field" value=" " />';
            $form_html .= '<input type="hidden" name="billing_country" id="billing_country_field" value="" />';
            $form_html .= '<input type="hidden" name="billing_postcode" id="billing_postcode_field" value=" " />';
            $form_html .= '<input type="hidden" name="delivery_fullname" id="delivery_fullname_field" value=" " />';
            $form_html .= '<input type="hidden" name="delivery_address" id="delivery_address_field" value=" " />';
            $form_html .= '<input type="hidden" name="delivery_city" id="delivery_city_field" value=" " />';
            $form_html .= '<input type="hidden" name="delivery_country" id="delivery_country_field" value=" " />';
            $form_html .= '<input type="hidden" name="delivery_postcode" id="delivery_postcode_field" value=" " />';
            $form_html .= '<input type="hidden" name="email_address" id="email_address_field" value=" " />';
            $form_html .= '<input type="hidden" name="customer_phone_number" id="customer_phone_number" value=" " />';
            $form_html .= '<input type="hidden" name="success_url" id="success_url_field" value="'. $c_nochexParams["Nochex_Urls"]["success_url"] .'" />';     
            $form_html .= '<input type="hidden" name="hide_billing_details" id="hide_billing_details_field" value="'. $c_nochexParams["Nochex_Settings"]["hide_billing_details"] .'" /> ';      
            $form_html .= '<input type="hidden" name="callback_url" id="callback_url_field" value="'. $c_nochexParams["Nochex_Urls"]["callback_url"] .'" />';
            $form_html .= '<input type="hidden" name="cancel_url" id="cancel_url_field" value="'. $c_nochexParams["Nochex_Urls"]["cancel_url"] .'" />';
            $form_html .= '<input type="hidden" name="test_success_url" id="test_success_url_field" value="'. $c_nochexParams["Nochex_Urls"]["test_success_url"] .'" />';
            $form_html .= '<input type="hidden" name="test_transaction" id="test_transaction_field" value="'. $c_nochexParams["Nochex_Settings"]["test_transaction"] .'" />';
            $form_html .= '<div class"payment_btn" style="margin-top: 20px; height: auto; width: 50%; position:relative;"><img src="https://amanda-swan.com/wp-content/uploads/2023/06/New-Project-4.png" height:auto; width:100%;><input type="submit" class="button-alt" style="height: 90%; width:96%; cursor: pointer; border: none; left: 0; background: transparent;  position:absolute " onclick="valueFunction()" id="c_submit_nochex_payment_form" name="submit_1" value=" "/></div>';
        
        $form_html .= '</form>';
         return  $form_html;
    }




        // ######################## End Payment Yearly form shortcode function  ###############################






    // Create an order or invoice with s2Member data
    function create_invoice_with_s2member_data($user_id, $txn_id, $amount, $currency) {
        // Get user data
        $user_info = get_userdata($user_id);
        $user_email = $user_info->user_email;
        $user_name = $user_info->display_name;

        // Create the invoice or order using the s2Member data and Nochex integration
        // Replace this code with your custom invoice or order creation logic
        $invoice_data = array(
            'user_id' => $user_id,
            'user_email' => $user_email,
            'user_name' => $user_name,
            'txn_id' => $txn_id,
            'amount' => $amount,
            'currency' => $currency,
            'payment_gateway' => 'Nochex',
        );

        // Save the invoice data to your desired location or send it via email
        // ...

        // Example: Display the invoice data on the page
        $invoice_html = '<h2>Invoice Details</h2>';
        $invoice_html .= '<p><strong>User ID:</strong> ' . $invoice_data['user_id'] . '</p>';
        $invoice_html .= '<p><strong>User Name:</strong> ' . $invoice_data['user_name'] . '</p>';
        $invoice_html .= '<p><strong>User Email:</strong> ' . $invoice_data['user_email'] . '</p>';
        $invoice_html .= '<p><strong>Transaction ID:</strong> ' . $invoice_data['txn_id'] . '</p>';
        $invoice_html .= '<p><strong>Amount:</strong> ' . $invoice_data['amount'] . ' ' . $invoice_data['currency'] . '</p>';
        $invoice_html .= '<p><strong>Payment Gateway:</strong> ' . $invoice_data['payment_gateway'] . '</p>';

        echo $invoice_html;
    }

    // Hook into s2Member payment complete event
    add_action('ws_plugin__s2member_during_paypal_notify_success', 'nochex_s2member_payment_complete', 10, 2);

    // Handle s2Member payment complete event
    function nochex_s2member_payment_complete($txn_id, $vars) {
        // Check if Nochex integration is enabled
        $enabled = get_option('nochex_s2member_enabled', false);
        if (!$enabled) {
            return;
        }

        // Get payment details from s2Member
        $user_id = $vars['user_id'];
        $amount = $vars['mc_gross'];
        $currency = $vars['mc_currency'];

        // Create invoice or order using s2Member data
        create_invoice_with_s2member_data($user_id, $txn_id, $amount, $currency);
    }

