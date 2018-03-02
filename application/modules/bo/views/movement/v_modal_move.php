
									<div class="modal-dialog">
										<div class="modal-content">
											<div class="modal-header">
												<button type="button" class="close" data-dismiss="modal">&times;</button>
												<h4>Move Asset Outstanding</h4>
											</div>

											<div class="modal-body">
												<div class="row">
												<div class="col-lg-12">												
												<?php $this->load->view('bo/v_alert_notif');
												$priv = $this->m_master->get_priv($acces_code,'edit');
												if (empty($priv)){?>             
													<form id="frmmove" method="post">
									     			<input type="hidden" name="id" value="<?php echo isset($id_product) ? $id_product : ''?>">
									     			<?php $select='';?>		
							     					<div class="form-group">
							     						<label> Sub Number <span class="required">*</span></label>
							     						<div class="controls">	     						
							     							<input class="form-control" type="text" name="itemnumber" placeholder="Sub Number" value="<?php echo isset($product_code) ? $product_code : ''?>"  readonly required>	     							
							     						</div>
							     					</div>	  
							     					<div class="form-group">
							     						<label> Description <span class="required">*</span></label>
							     						<div class="controls">	     						
							     							<input class="form-control" type="text" name="desc" placeholder="Description" value="<?php echo isset($description) ? $description : ''?>"  disabled required>	     							
							     						</div>
							     					</div>
							     					<div class="form-group">
							     						<label>Asset Class <span class="required">*</span></label>
							     						<select name="assetclass" class="form-control" disabled required>
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
							     						<input type="text" class="form-control" name="bisnisarea" placeholder="Bisnis Area" value="<?php echo isset($bisnis_area) ? $bisnis_area : ''?>" onkeyup="javascript:this.value=this.value.toUpperCase()" disabled required>
							     					</div>	  
							     					<div class="form-group">
							     						<label>Sub Location<span class="required">*</span></label>
							     						<input type="text" class="form-control" name="sublocation" placeholder="Sub Location" value="<?php echo isset($sub_location) ? $sub_location : ''?>" required>
							     					</div>
							     					<div class="form-group">
							     						<label>Company Code<span class="required">*</span></label>
							     						<select name="companycode" class="form-control" required>
							     							<option value="" selected disabled>--Pilih Company Code--</option>
							     							<?php foreach ($getuser as $row){
							     								$selected='';
							     								if (isset($id_product)){
																	$selected = ($row->company_code == $company_code) ? 'selected':'';
																}
																echo '<option value="'.$row->company_code.'" '.$selected.'>'.$row->company_code.'</option>';
							     							}?>
							     						</select>
							     					</div>
							     					<div class="cleaner_h20"></div>
							     					<span class="required">*</span> Wajib diisi.								     				
								     				</form>		
								     				</div>  		
												</div>
											</div>
											<?php }else{
												echo '<div class="alert alert-danger"><h4>Anda tidak punya hak ubah asset move</h4></div>';
											}?>
											<div class="modal-footer">
												<button class="btn btn-sm" data-dismiss="modal">
													<i class="ace-icon fa fa-times"></i>
													Close
												</button>

												<button class="btn btn-sm btn-primary" onclick="addform('<?php echo base_url('bo/movement/save_move')?>',$('#frmmove').serialize(),'')">
													<i class="ace-icon fa fa-check"></i>
													Save
												</button>
											</div>
										</div>
									</div>
