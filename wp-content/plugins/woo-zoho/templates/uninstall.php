<?php
if ( ! defined( 'ABSPATH' ) ) {
     exit;
 }                                            
 ?>  <h3><?php _e('Uninstall WooCommerce Zoho Plugin','woo-zoho'); ?></h3>
  <?php
  if(isset($_POST[$this->id.'_uninstall'])){ 
  ?>
  <div class="vxc_alert updated  below-h2">
  <h3><?php _e('Success','woo-zoho'); ?></h3>
  <p><?php _e('WooCommerce Zoho Plugin has been successfully uninstalled','woo-zoho'); ?></p>
  <p>
  <a class="button button-hero button-primary" href="plugins.php"><?php _e("Go to Plugins Page",'woo-zoho'); ?></a>
  </p>
  </div>
  <?php
  }else{
  ?>
  <div class="vxc_alert error below-h2">
  <h3><?php _e("Warning",'woo-zoho'); ?></h3>
  <p><?php _e('This Operation will delete all Zoho logs and feeds.','woo-zoho'); ?></p>
  <p><button class="button button-hero button-secondary" id="vx_uninstall" type="submit" onclick="return confirm('<?php _e("Warning! ALL Zoho Feeds and Logs will be deleted. This cannot be undone. OK to delete, Cancel to stop.", 'woo-zoho')?>');" name="<?php echo $this->id ?>_uninstall" title="<?php _e("Uninstall",'woo-zoho'); ?>" value="yes"><?php _e("Uninstall",'woo-zoho'); ?></button></p>
  </div>
  <?php
  } ?>