  <?php 
  include_once 'header.php';

    global $wpdb,$table_prefix;
    $c_nochex_table = $table_prefix.'custom_nochex';

      // Check if user has permissions
    if (!current_user_can('manage_options')) {
        wp_die('Access Denied');
    }

    // Check if form is submitted
    if (isset($_POST['nochex_s2member_submit'])) {
        // Validate and save the settings
        $merchant_id      = sanitize_text_field($_POST['nochex_s2member_merchant_id']);
        $test_success_url = sanitize_text_field($_POST['test_success_url']);
        $success_url      = sanitize_text_field($_POST['success_url']);
        $cancel_url       = sanitize_text_field($_POST['cancel_url']);
        $callback_url     = sanitize_text_field($_POST['callback_url']);


        if (empty($merchant_id)) {
            echo '<div class="error"><p>Please enter a valid Merchant ID.</p></div>';
        }elseif(empty($test_success_url)){
             echo '<div class="error"><p>Please enter a valid Test Success Url.</p></div>';  
        }elseif(empty($success_url)){
             echo '<div class="error"><p>Please enter a valid Success Url.</p></div>';  
        }elseif(empty($cancel_url)){
             echo '<div class="error"><p>Please enter a valid Cancel Url.</p></div>';  
        }elseif(empty($callback_url)){
             echo '<div class="error"><p>Please enter a valid Callback Url.</p></div>';  
        }
         else {
            update_option('nochex_s2member_enabled', isset($_POST['nochex_s2member_enabled']));
            update_option('nochex_s2member_merchant_id', $merchant_id);
            echo '<div class="updated"><p>Settings saved!</p></div>';


            $data = array(
                 'merchant_id' =>$merchant_id,
                 'test_success_url' =>$test_success_url,
                 'success_url' =>$success_url,
                 'cancel_url' =>$cancel_url,
                 'callback_url' =>$callback_url,
             );
            $res = $wpdb->insert($c_nochex_table, $data);
             echo '<meta http-equiv="refresh" content="2">';
        }

         

    }

    // Retrieve the settings
    $enabled = get_option('nochex_s2member_enabled', false);
    $merchant_id = get_option('nochex_s2member_merchant_id', '');


    if(isset($_POST['nochex_remove_btn'])){
         $qry = "TRUNCATE $c_nochex_table";
         $wpdb->query($qry);  
        echo '<meta http-equiv="refresh" content="2">';
    }

   ?>

<style>
.header-right h1{
    border: 1px dashed #000;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 10px 20px 15px 20px;
    height: 50px;
    margin-bottom: 3px;
}

.header-right small{
   font-size: 12px;
}


</style> 

  <div class="wrap">
    
    <div class="container mt-5 pr-5 pt-2 pb-2 pl-0 custom-header">
        <div class="row">
            <div class="col-sm-12 col-lg-6 col-md-6">
                <div class="header-left">
                    <h1>Nochex s2Member Integration Settings</h1>
                </div>  
            </div>
            <div class="col-sm-12 col-lg-6 col-md-6">
                <div class="header-right">
                    <h1>[nochex_s2member_payment_form]</h1>
                    <small >Please copy shortcore from here and paste the code on billing page</small>
                </div>  
            </div>
        </div>
    </div>

    <div class="container mt-2">
          <form method="post" action="" id="myForm">
            <table class="form-table">
                <tr>
                    <th scope="row">Enable Nochex Integration:</th>
                    <td>
                        <input type="checkbox" name="nochex_s2member_enabled" id="nochexCheckbox" value="1" <?php checked($enabled, true); ?>>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Merchant ID:</th>
                    <td>
                        <input type="text" name="nochex_s2member_merchant_id" class="<?php if(C_NOCHEX_MERCHANT_ID){echo 'border border-success text-success';} ?>" <?php if(C_NOCHEX_MERCHANT_ID){echo 'disabled';} ?> value="<?php if(C_NOCHEX_MERCHANT_ID){echo C_NOCHEX_MERCHANT_ID;} ?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row">Test Success Url:</th>
                    <td>
                        <input type="text" name="test_success_url" class="<?php if(C_NOCHEX_TEST_SUCCESS_URL){echo 'border border-success text-success';} ?>" <?php if(C_NOCHEX_TEST_SUCCESS_URL){echo 'disabled';} ?> value="<?php if(C_NOCHEX_TEST_SUCCESS_URL){echo C_NOCHEX_TEST_SUCCESS_URL;} ?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row">Success Url:</th>
                    <td>
                        <input type="text" name="success_url" class="<?php if(C_NOCHEX_SUCCESS_URL){echo 'border border-success text-success';} ?>" <?php if(C_NOCHEX_SUCCESS_URL){echo 'disabled';} ?> value="<?php if(C_NOCHEX_SUCCESS_URL){echo C_NOCHEX_SUCCESS_URL;} ?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row">Cancel Url:</th>
                    <td>
                        <input type="text" name="cancel_url" class="<?php if(C_NOCHEX_CANCEL_URL){echo 'border border-success text-success';} ?>" <?php if(C_NOCHEX_CANCEL_URL){echo 'disabled';} ?> value="<?php if(C_NOCHEX_CANCEL_URL){echo C_NOCHEX_CANCEL_URL;} ?>">
                    </td>
                </tr>
                <tr>
                    <th scope="row">Callback Url:</th>
                    <td>
                        <input type="text" name="callback_url" class="<?php if(C_NOCHEX_CALLBACK_URL){echo 'border border-success text-success';} ?>" <?php if(C_NOCHEX_CALLBACK_URL){echo 'disabled';} ?> value="<?php if(C_NOCHEX_CALLBACK_URL){echo C_NOCHEX_CALLBACK_URL;} ?>">
                    </td>
                </tr>
            </table>

            <div class="row">
                <div class="col-sm-1 col-lg-1 col-md-1 <?php if(C_NOCHEX_MERCHANT_ID){echo 'col-sm-1 col-lg-1 col-md-1';}else{ echo 'col-sm-2 col-lg-2 col-md-2';} ?>">
                   <p class="submit">
                   <input type="submit" name="nochex_s2member_submit" class=" <?php if(C_NOCHEX_MERCHANT_ID){echo 'btn btn-outline-success border-success border';}else{ 'button-primary';} ?>" <?php if(C_NOCHEX_MERCHANT_ID){echo 'disabled';} ?> value="<?php if(C_NOCHEX_MERCHANT_ID){echo 'Activated';}else{ echo 'Save Settings';} ?>">
                   </p> 
                </div>
                <div class="col-sm-6 col-lg-2 col-md-2">
                    <form action="" method="post">
                        <p class="submit">
                      <input type="submit" name="nochex_remove_btn"  class="btn btn-danger" style="display:<?php if(C_NOCHEX_MERCHANT_ID){echo 'block';}else{ echo 'none';} ?>" value="Remove Settings">
                      </p> 
                    </form>
                </div>
            </div>
            
          </form>
          
    </div>

       
    </div>




    <?php   include_once 'footer.php'; ?>