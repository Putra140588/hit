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
				     			<input type="hidden" name="id" value="<?php echo isset($id_product) ? $id_product : ''?>">
				     			<?php $select='';?>		
		     					<div class="form-group">
		     						<label> Sub Number <span class="required">*</span></label>
		     						<div class="controls">	     						
		     							<input class="form-control" type="text" name="itemnumber" placeholder="Sub Number" value="<?php echo isset($product_code) ? $product_code : ''?>"  required>	     							
		     						</div>
		     					</div>	  
		     					<div class="form-group">
		     						<label> Description <span class="required">*</span></label>
		     						<div class="controls">	     						
		     							<input class="form-control" type="text" name="desc" placeholder="Description" value="<?php echo isset($description) ? $description : ''?>"  required>	     							
		     						</div>
		     					</div>
		     					<div class="form-group">
		     						<label>Asset Class <span class="required">*</span></label>
		     						<select name="assetclass" class="form-control" required>
		     							<option value="" selected disabled>--Pilih Asset Class--</option>
		     							<?php foreach ($assetclass as $row){
		     								$selected='';
		     								if (isset($id_product)){
												$selected = ($row->asset_class == $asset_class) ? 'selected':'';
											}
											echo '<option value="'.$row->asset_class.'" '.$selected.'>'.$row->asset_class.' - '.$row->nama_class.'</option>';
		     							}?>
		     						</select>
		     						
		     					</div>			     					
		     					<div class="form-group">
		     						<label>Bisnis Area<span class="required">*</span></label>
		     						<input type="text" class="form-control" name="bisnisarea" placeholder="Bisnis Area" value="<?php echo isset($bisnis_area) ? $bisnis_area : ''?>" onkeyup="javascript:this.value=this.value.toUpperCase()" required>
		     					</div>	  
		     					<div class="form-group">
		     						<label>Sub Location<span class="required">*</span></label>
		     						<input type="text" class="form-control" name="sublocation" placeholder="Sub Location" value="<?php echo isset($sub_location) ? $sub_location : ''?>" required>
		     					</div>
		     					
		     					<div class="cleaner_h20"></div>
		     					<span class="required">*</span> Wajib diisi.
			     				<div class="cleaner_h10"></div>
			     				<button type="submit" class="btn btn-primary">Simpan</button>
			     				<button type="button" class="btn btn-warning" onclick="window.history.back()">Kembali</button>  
			     				</form>		  					
	     					</div> 
	     				    <div class="col-lg-6">
	     				    	<form id="frmstock" enctype="multipart/form-data" method="post" action="<?php echo base_url('bo/'.$class.'/import')?>">     						
     				<div class="row">     				
     					<div class="col-lg-6">
	     					<div class="form-group">
	     						<label> File Import <span class="required">*</span></label>	     							     						
	     							<input type="file" name="filemaster">
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
</div>
       