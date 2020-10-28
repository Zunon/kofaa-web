<?php
   /*
   Plugin Name: Export courses Orders
   description: A plugin used for export the learnpress orders
   Author: oodles Technologies 
   */
 
 add_menu_page("Export Courses Orders", "Export Courses Orders", "manage_options", "export_courese", "export_courses_orders", "dashicons-welcome-widgets-menus",5);
 
 
 function export_courses_orders()
 {
	 global $wpdb;
	 
	//$order_sql = "SELECT order_item_name,order_id FROM wp_learnpress_order_items";
	$order_sql = "SELECT o.order_item_name,o.order_id,DATE(p.post_modified) as payment_date,pm.meta_value,ut.status,u.user_login,u.display_name FROM wp_learnpress_order_items o LEFT JOIN wp_posts p ON o.order_id = p.ID LEFT JOIN wp_postmeta pm ON p.ID = pm.post_id LEFT JOIN wp_users u ON u.ID = pm.meta_value LEFT JOIN wp_learnpress_user_items ut ON ut.ref_id = o.order_id where pm.meta_key = '_user_id' order by order_item_id DESC";
	 $order_data = $wpdb->get_results($order_sql, OBJECT);
	
	 ?>
	 <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
	 <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
	 <script src="https://cdn.datatables.net/buttons/1.6.3/js/dataTables.buttons.min.js"></script>
	 <script src="https://cdn.datatables.net/buttons/1.6.3/js/buttons.flash.min.js"></script>
	 <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
	 <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
	 <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
	 <script src="https://cdn.datatables.net/buttons/1.6.3/js/buttons.html5.min.js"></script>
	 <script src="https://cdn.datatables.net/buttons/1.6.3/js/buttons.print.min.js"></script>
	 <link rel="stylesheet" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css">
	 <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.3/css/buttons.dataTables.min.css">
	 
	<div class="wrap">
	<h1 class="wp-heading-inline" style="margin-bottom:2%">Export Courses Orders</h1>

	<table  id="example" class="display" style="width:100%">
	 <thead>
		<tr>
			<th>Order ID</th>
			<th>Student</th>
			<th>Purchased</th>
			<th>Date</th>
			<th>Total</th>
			<th>Currency</th>
			<th>Payment Method </th>
			<th>Status</th>
		</tr>
	 </thead>
		<?php 
		foreach($order_data as $lsorder)
		{
			if($lsorder->status == "finished")
			{
				$order_status = "completed";
			}
			else if($lsorder->status == "enrolled")
			{
				$order_status = "completed";
			}
			else
			{
				$order_status = $lsorder->status;
			}
				
			echo '</tr><td style="text-align:center">';
			echo "#".$lsorder->order_id;
			echo '</td><td style="text-align:center">';
			echo $lsorder->user_login."(".$lsorder->display_name.")";
			echo '</td><td style="text-align:center">';
			echo $lsorder->order_item_name."(#".$lsorder->order_id.")";
			echo '</td><td style="text-align:center">';
			echo $lsorder->payment_date;
			echo '</td><td style="text-align:center">';
			echo get_post_meta($lsorder->order_id, '_order_total',true );
			echo '</td><td style="text-align:center">';
			echo get_post_meta($lsorder->order_id, '_order_currency',true );
			echo '</td><td style="text-align:center">';
			echo get_post_meta($lsorder->order_id, '_payment_method_title',true );
			echo '</td><td style="text-align:center">';
			echo $order_status;
			echo '</td></tr>';
		} 
		?>
		<tfoot>
		<tr>
			<th>Order ID</th>
			<th>Student</th>
			<th>Purchased</th>
			<th>Date</th>
			<th>Total</th>
			<th>Currency</th>
			<th>Payment Method </th>
			<th>Status</th>
		</tr>
	 </tfoot>
	</table>
	</div>
	<script>
	$(document).ready(function() {
    $('#example').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            'csv', 'excel', 'pdf', 'print'
        ]
    } );
} );
</script>
	
	<?php
 }
 /* add_menu_page("Learnpress paytab Setting", "Learnpress paytab Setting", "manage_options", "learnpress_paytab", "learnpress_paytab_setting_fn", "dashicons-welcome-widgets-menus",6);
 function learnpress_paytab_setting_fn() {
	?>
<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://code.jquery.com/jquery-1.11.1.min.js"></script>


<div class="container">
	<div class="row">
    	<div class="container" id="formContainer">

          <form class="form-signin" id="login" role="form">
            <h3 class="form-signin-heading">Please sign in</h3>
            <a href="#" id="flipToRecover" class="flipLink">
              <div id="triangle-topright"></div>
            </a>
            <input type="email" class="form-control" name="loginEmail" id="loginEmail" placeholder="Email address" required autofocus>
            <input type="password" class="form-control" name="loginPass" id="loginPass" placeholder="Password" required>
            <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
          </form>
    
          <form class="form-signin" id="recover" role="form">
            <h3 class="form-signin-heading">Enter email address</h3>
            <a href="#" id="flipToLogin" class="flipLink">
              <div id="triangle-topleft"></div>
            </a>
            <input type="email" class="form-control" name="loginEmail" id="loginEmail" placeholder="Email address" required autofocus>
            <button class="btn btn-lg btn-primary btn-block" type="submit">Recover password</button>
          </form>

        </div> <!-- /container -->
	</div>
</div>
	<?php
 } */
 
 
?>