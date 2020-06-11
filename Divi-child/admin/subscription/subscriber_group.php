<?php

if(isset($_GET['new_action'])){
    global $wpdb;
	if($_GET['new_action'] == 'add_group'){
		include( get_template_directory() . '-child/admin/subscription/add_group.php' );
    }
    if($_GET['new_action'] == 'delete'){
        $id = $_GET['id'];
        $table_name = $wpdb->prefix.'subscriber_group';
        $where = array('group_id'=>$id);
        $wpdb->delete($table_name,$where);
        flash('msg','<div class="alert alert-success border-success">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <i class="icofont icofont-close-line-circled"></i>
                        </button> Group deleted Successfully!
                        </code>
                    </div>');
    }
    if($_GET['new_action'] == 'edit'){
		include( get_template_directory() . '-child/admin/subscription/edit_group.php' );
	}elseif($_GET['new_action'] == 'subscriber'){		
		include( get_template_directory() . '-child/admin/subscription/subscriber.php' );
		exit;
	}	        	
}
if(isset($_GET['subscriber_action'])){
	if($_GET['subscriber_action'] == 'exportcsv'){
		include( get_template_directory() . '-child/admin/sms_voice_broadcast/export_csv.php' );
	}
}else{
include( get_template_directory() . '-child/admin/header.php' );
global $wpdb;

$table_name = $wpdb->prefix.'subscriber_group';


?>
<section class="dashboard">
		<div class="container-fluid">
			<div class="dashboard-box no-padding-left no-padding-top no-padding-bottom no-margin-top">
				<div class="row">
				<div class="col-md-3 col-sm-3 col-lg-3 col-xs-12 no-padding-left">
						<?php include( get_template_directory() . '-child/admin/sidebar.php' ); ?>
					</div>
					<div class="col-md-9 col-sm-9 col-lg-9 col-xs-12">
					<h4 style="text-align:right; margin-top:3%;">
						<a href="<?php echo home_url();?>/dashboard/" class="btn btn-danger"><i class="fa fa-arrow-left"></i> Back</a>
						</h4>
						<div class="table-div">
							<div class="table-box">
								<div class="table-heading subsciber-heading">
									<h5>
										<div class="sub_headpart_left">
										Group of Subscribers
										</div>
										<div class="sub_headpart_right">
                                    <small class="heading-earnings add-earning subsciber-link">
										
										<font color="#007803">
										<!-- <form style="display:inline;" action="<?php echo home_url();?>/dashboard/?option=subscription" method="post">
										<select class="" name="drop_campaign" onchange="form.submit();">
											<option value="">Select Campaigns</option>
											<?php 
											$camp_table_name = $wpdb->prefix.'svb_campaings';
											$camp_list = $wpdb->get_results("SELECT * FROM $camp_table_name");
											foreach($camp_list as $list){
											?>
											<option value="<?php echo $list->id;?>"><?php echo $list->title;?></option>
											
											<?php } ?>
										</select>
										</form> -->
										<a href="<?php echo home_url();?>/dashboard/?option=subscription&new_action=add_group"><i class="fa fa-plus"></i> Create Group</a>
										</font></small>
										</div>
                                    </h5>
									<hr />
								</div>	
                                <?php flash('msg');?>
                                <?php if(isset($_GET['imsg'])) {?>
								<div class="alert alert-success border-success">
											<button type="button" class="close" data-dismiss="alert" aria-label="Close">
											<i class="icofont icofont-close-line-circled"></i>
											</button> Group added Successfully!
											</code>
										</div>
								<?php } ?>	
								<?php if(isset($_GET['umsg'])) {?>
								<div class="alert alert-success border-success">
											<button type="button" class="close" data-dismiss="alert" aria-label="Close">
											<i class="icofont icofont-close-line-circled"></i>
											</button> Group updated Successfully!
											</code>
										</div>
								<?php } ?>	
						<div class="table-responsive">
								<table id="subscription" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%">
									<thead>
										<tr>
											<!-- <th>Group Id</th> -->
											<th>Group Name</th>
											<th>Subscribers</th>	
											<!-- <th>Campaigns Title</th> -->
											<th>Status</th>
											<th>Action</th>
										</tr>
									</thead>
									
									<tbody>
									<?php
									if(isset($_POST['drop_campaign'])){
										$campagin_id= $_POST['drop_campaign'];	
										$results = $wpdb->get_results("SELECT * FROM $table_name WHERE campaign_id = $campagin_id",ARRAY_A); 
								   }else{
									    $user_id = $_SESSION['user_id'];
										$results = $wpdb->get_results("SELECT * FROM `$table_name` where registereduser_id = $user_id",ARRAY_A); 
								   }
									$i = 0;
									foreach($results as $result){										
									?>
										<tr>
											<!-- <td><?php echo $result['group_id'];?></td> -->
											<td><?php echo $result['group_name'];?></td>
											<td><?php echo subscriberCount($result['group_id'])->total;?></td>
											<!-- <td><?php echo ucwords(getCampaignName($result['campaign_id'])->title); ?></td>										 -->
											<td><font color="#228B22"><?php echo $result['status'];?></font></td>
											<td>
												<a href="<?php echo home_url();?>/dashboard?option=subscription&new_action=subscriber&id=<?php echo $result['group_id'];?>"><i class="fa fa-eye fa-1x camp-action"></i></a>
												<a href="<?php echo home_url();?>/dashboard?option=subscription&new_action=edit&id=<?php echo $result['group_id'];?>"><i class="fa fa-edit fa-1x camp-action"></i></a>
                                                <a href="<?php echo home_url();?>/dashboard?option=subscription&new_action=delete&id=<?php echo $result['group_id'];?>"  onclick="return confirm('Are you sure ?');"><i class="fa fa-trash fa-1x camp-action"></i></a>
												<!-- <a href="#"><i class="fa fa-trash-o fa-1x camp-action"></i></a> -->
											</td>
										</tr>												
									<?php $i++; } ?>
									</tbody>
							</table>																													</div>							
							</div>							
						</div>
                    </div>
                </div>
            </div>
        </div>
</section>

<!-- Modal Box For Upload Csv-->

	<div id="upload_csv" class="modal fade" role="dialog">
		<div class="modal-dialog">

			<!-- Modal content-->
			<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Upload CSV</h4>
			</div>
			<div class="modal-body">
			<form action="" method="post" enctype="multipart/form-data">
				<div class="form-group">
					<label for="csv_file">Choose CSV:</label>
					<input type="file" class="form-control" name="svb_csv_file" >
				</div>
				
				<button type="submit" class="btn btn-info" name="svb_csv">Submit</button>
			</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
			</div>
			</div>

		</div>
		</div>


<!-- End Modal Box For Upload Csv-->
<?php
include( get_template_directory() . '-child/admin/footer.php' );
	}
	if(isset($_POST['svb_csv'])){
		$row = 0;
		$table_name = $wpdb->prefix.'svb_subscriber';
		$handle = fopen($_FILES['svb_csv_file']['tmp_name'], "r");
		while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) { 
		if($row !=0 ){
			$idata = array('name' =>$data[0],'phone_number'=>$data[1] );
			$wpdb->insert($table_name,$idata);
		}		
		$row++;		
		 //echo "</pre>";
		//$idata = array('name' =>$data[0],'phone_number'=>$data[1] );
		//$wpdb->insert($table_name,$idata);
		 
		}
		fclose($handle);
		echo '<script>window.location.href="'.home_url().'/dashboard/?page=sms_voice_broadcast&sms_page=subscriber&import=1"</script>';		 		 
	}
?>