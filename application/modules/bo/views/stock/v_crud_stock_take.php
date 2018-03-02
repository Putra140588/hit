<div id="page-wrapper">     
 <div class="cleaner_h50"></div>
     <div class="row">
     	<div class="col-lg-12">
     		<div class="panel panel-info">
     			<div class="panel-heading">
     				<h4><?php echo $page_title?></h4>
     			</div>
     			<div class="panel-body">
     			<?php $this->load->view('bo/v_alert_notif');?>
     			<form id="frmproduk" enctype="multipart/form-data" method="post" action="<?php echo base_url('bo/'.$class.'/proses')?>">     			
     			<?php $select='';?>
     				<div class="row">   				
     					   
     					<div class="col-lg-6">
     						<form id="frmproduk" method="post" action="<?php echo base_url('bo/'.$class.'/proses')?>">				     			
				     			<?php $select='';?>		
		     					<div class="form-group">
		     						<label> Sub Number <span class="required">*</span></label>
		     						<div class="controls">	     						
		     							<input class="form-control" type="text" name="subnumber" placeholder="Sub Number" required>	     							
		     						</div>
		     					</div>	 	     					
		     					<div class="form-group">
		     						<label>Status <span class="required">*</span></label>
		     						<select name="status" class="form-control" required>
		     							<option value="" selected disabled>--Pilih Status--</option>
		     							<?php $status = array(0=>'Found',1=>'Not Found');
		     							for ($i=0; $i < count($status); $i++){											
											echo '<option value="'.$i.'" '.$selected.'>'.$status[$i].'</option>';
		     							}?>
		     						</select>
		     						
		     					</div>			     						     					  
		     					<div class="form-group">
		     						<label>Stock Date<span class="required">*</span></label>
		     						<input type="text" id="stockdate" name="stockdate" class="date-picker form-control" placeholder="yyyy-mm-dd">
		     					</div>		     					
		     					<div class="cleaner_h20"></div>
		     					<span class="required">*</span> Wajib diisi.
			     				<div class="cleaner_h10"></div>
			     				<button type="submit" class="btn btn-primary">Simpan</button>
			     				<button type="button" class="btn btn-warning" onclick="window.history.back()">Kembali</button>  
			     			</form>		  				
     					</div>  		
     				<form id="frmstock" enctype="multipart/form-data" method="post" action="<?php echo base_url('bo/'.$class.'/import')?>">     						
     				<div class="row">     				
     					<div class="col-lg-6">
	     					<div class="form-group">
	     						<label> File Import <span class="required">*</span></label>	     							     						
	     							<input type="file" name="stocktake">
	     							<div class="cleaner_h5"></div>
	     							File Type: *TXT, *csv    						
	     					</div>	
	     					<div class="cleaner_h20"></div>    
	     					<span class="required">*</span> Wajib diisi.
		     				<div class="cleaner_h10"></div>
		     				<button type="submit" class="btn btn-primary">Import</button>		     				
     					</div>     							
     				</div>     				
     				</form>						
     				</div>
     				
     						
     			</div>
     		</div>
     	</div>
     </div>
</div>
       