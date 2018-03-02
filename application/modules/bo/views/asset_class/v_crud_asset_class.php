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
	     		<div class="row">    				
	     		<div class="col-lg-6">
				     		<form id="frmproduk" method="post" action="<?php echo base_url('bo/'.$class.'/proses')?>">
				     			<input type="hidden" name="id" value="<?php echo isset($asset_class) ? $asset_class : ''?>">
				     			<?php $readonly = isset($asset_class) ? 'readonly' : '';?>		
		     					<div class="form-group">
		     						<label> Asset Class <span class="required">*</span></label>
		     						<div class="controls">	     						
		     							<input class="form-control" <?php echo $readonly?> type="text" name="assetclass" placeholder="Asset Class" value="<?php echo isset($asset_class) ? $asset_class : ''?>"  required>	     							
		     						</div>
		     					</div>	  
		     					<div class="form-group">
		     						<label> Nama Class <span class="required">*</span></label>
		     						<div class="controls">	     						
		     							<input class="form-control" type="text" name="nama" placeholder="Nama Class" value="<?php echo isset($nama_class) ? $nama_class : ''?>"  required>	     							
		     						</div>
		     					</div>
		     					
		     					
		     					<div class="cleaner_h20"></div>
		     					<span class="required">*</span> Wajib diisi.
			     				<div class="cleaner_h10"></div>
			     				<button type="submit" class="btn btn-primary">Simpan</button>
			     				<button type="button" class="btn btn-warning" onclick="window.history.back()">Kembali</button>  
			     				</form>		  					
	     			</div> 	     				    			        									
     				</div>   				
     								
     				
     				
     			</div>
     		</div>
     	</div>
     </div>
</div>
       