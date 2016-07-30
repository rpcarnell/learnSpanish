<style type="text/css">
	.upl_tit{
		color: #BD7400;
		text-align: left;
	}
</style>
<script>
	$(window).load(function(){
		$('#browse-btn').css('cursor', 'pointer');
		$('#browse-btn2').css('cursor', 'pointer');
		$('#browse-btn3').css('cursor', 'pointer');
		$('#browse-btn4').css('cursor', 'pointer');
		$('#browse-btn5').css('cursor', 'pointer');
		$('#browse-btn6').css('cursor', 'pointer');
		
		$('#browse-btn').click(function(){
			$('#myPic').click();

		});
		
		$('#myPic').change(function(){
			$('#myPicName').val($(this).val());

		});
		$('#browse-btn2').click(function(){
			$('#myPic2').click();

		});
		
		$('#myPic2').change(function(){
			$('#myPicName2').val($(this).val());

		});
		$('#browse-btn3').click(function(){
			$('#myPic3').click();

		});
		
		$('#myPic3').change(function(){
			$('#myPicName3').val($(this).val());

		});
		$('#browse-btn4').click(function(){
			$('#myPic4').click();

		});
		
		$('#myPic4').change(function(){
			$('#myPicName4').val($(this).val());

		});
		$('#browse-btn5').click(function(){
			$('#myPic5').click();

		});
		
		$('#myPic5').change(function(){
			$('#myPicName5').val($(this).val());

		});
		$('#browse-btn6').click(function(){
			$('#myPic6').click();

		});
		
		$('#myPic6').change(function(){
			$('#myPicName6').val($(this).val());

		});		
	});
</script>
<div class="spanish-content my-image">
	<div><img src="<?php echo base_url() ?>images_front/image/my_image.png" width="197" height="52"  alt="" title="" /></div>
	<h4 class="orange">You can upload a maximum of six images for your profile. We suggest that you choose engaging pictures of you in action, and make your primary photo a close-up picture of yourself.</h4>
	<div class="image-table">
	<?php //echo $this->upload->display_errors() ?>
	<?php #echo form_open_multipart();?>
	<form method="post" enctype="multipart/form-data">
	<input type="submit" name = "save" value="Save"/>
		<?php if($count == 0): ?>
			<ul>
			<li>
				<div class="dancer-img"></div>
				<div class="upload-input">
					<div id="browse-btn"> 
						<img src="<?php echo base_url() ?>images_front/image/upload.jpg" width="" height="20" alt="" title="" />
					</div>
						<input type="file" name="my_picture" id="myPic" style="display:none;" />
				</div>
				<div class="input-text"><input type="text" name = "filename" readonly="true" id="myPicName" /></div>
				<div class="btn-1"><a href="">Make Profile Picture</a></div>
			</li>
			
			<li>
				<div class="dancer-img"></div>
				<div class="upload-input">
					<div id="browse-btn2">
						<img src="<?php echo base_url() ?>images_front/image/upload.jpg" width="" height="20" alt="" title="" />
					</div>
						<input type="file" name="my_picture2" id="myPic2" style="display:none;"/>
				</div>
				<div class="input-text"><input type="text" name = "filename2" readonly="true" id="myPicName2"/></div>
				<div class="btn-1"><a href="">Make Profile Picture</a></div>
			</li>
			
			<li>
				<div class="dancer-img"></div>
				<div class="upload-input">
					<div id="browse-btn3">
						<img src="<?php echo base_url() ?>images_front/image/upload.jpg" width="" height="20" alt="" title="" />
					</div>
						<input type="file" name="my_picture3" id="myPic3" style="display:none;"  />
				</div>
				<div class="input-text"><input type="text" name = "filename3" readonly="true" id="myPicName3"/></div>
				<div class="btn-1"><a href="">Make Profile Picture</a></div>
			</li>
			
			<li>
				<div class="dancer-img"></div>
				<div class="upload-input">
					<div id="browse-btn4">
						<img src="<?php echo base_url() ?>images_front/image/upload.jpg" width="" height="20" alt="" title="" />
					</div>
						<input type="file" name="my_picture4" id="myPic4" style="display:none;" />
				</div>
				<div class="input-text"><input type="text" name = "filename4" readonly="true" id="myPicName4"/></div>
				<div class="btn-1"><a href="">Make Profile Picture</a></div>
			</li>
			
			<li>
				<div class="dancer-img"></div>
				<div class="upload-input">
					<div id="browse-btn5">
						<img src="<?php echo base_url() ?>images_front/image/upload.jpg" width="" height="20" alt="" title="" />
					</div>
					<input type="file" name="my_picture5" id="myPic5" style="display:none;" />
				</div>
				<div class="input-text"> <input type="text" name = "filename5" readonly="true" id="myPicName5"/></div>
				<div class="btn-1"><a href="">Make Profile Picture</a></div>
			</li>
			
			<li>
				<div class="dancer-img"></div>
				<div class="upload-input">
					<div id="browse-btn6">
						<img src="<?php echo base_url() ?>images_front/image/upload.jpg" width="" height="20" alt="" title="" />
					</div>
					<input type="file" name="my_picture6" id="myPic6" style="display:none;"/>
				</div>
				<div class="input-text"><input type="text" name = "filename6" readonly="true" id="myPicName6"/></div>
				<div class="btn-1"><a href="">Make Profile Picture</a></div>
			</li>
			
		</ul>
		
		<?php else: ?>
		<ul>
			<li>
				<?php $img = $this->My_model->select_where('dancer_img',array('img_id' => 1)); ?>
				<?php $count = $this->My_model->count_where('dancer_img',array('img_id' => 1));  ?>
				
				<?php if($count != 0): ?>
				<div class="dancer-img">
					<img src="<?php echo base_url() ?>uploads/spanish/images/<?php echo $img->dancer_images ?>" alt=""/>
				</div>
				<?php else: ?>
				<div class="dancer-img"></div>
				<?php endif ?>
				
				<div class="upload-input">
					<div id="browse-btn"> 
						<img src="<?php echo base_url() ?>images_front/image/upload.jpg" width="" height="20" alt="" title="" />
					</div>
						<input type="file" name="my_picture" id="myPic" style="display:none;" />
				</div>
				<div class="input-text"><input type="text" name = "filename" readonly="true" id="myPicName" /></div>
				<div class="btn-1"><a href="">Make Profile Picture</a></div>
			</li>
			
			<li>
				<?php $img2 = $this->My_model->select_where('dancer_img',array('img_id' => 2)); ?>
				<?php $count2 = $this->My_model->count_where('dancer_img',array('img_id' => 2));  ?>
				
				<?php if($count2 != 0): ?>
				<div class="dancer-img">
					<img src="<?php echo base_url() ?>uploads/spanish/images/<?php echo $img2->dancer_images ?>" alt=""/>
				</div>
				<?php else: ?>
				<div class="dancer-img"></div>
				<?php endif ?>
				<div class="upload-input">
					<div id="browse-btn2">
						<img src="<?php echo base_url() ?>images_front/image/upload.jpg" width="" height="20" alt="" title="" />
					</div>
						<input type="file" name="my_picture2" id="myPic2" style="display:none;"/>
				</div>
				<div class="input-text"><input type="text" name = "filename2" readonly="true" id="myPicName2"/></div>
				<div class="btn-1"><a href="">Make Profile Picture</a></div>
			</li>
			
			<li>
				<?php $img3 = $this->My_model->select_where('dancer_img',array('img_id' => 3)); ?>
				<?php $count3 = $this->My_model->count_where('dancer_img',array('img_id' => 3));  ?>
				
				<?php if($count3 != 0): ?>
				
				<div class="dancer-img">
					<img src="<?php echo base_url() ?>uploads/spanish/images/<?php echo $img3->dancer_images ?>" alt=""/>
				</div>
				<?php else: ?>
				<div class="dancer-img"></div>
				<?php endif ?>
				<div class="upload-input">
					<div id="browse-btn3">
						<img src="<?php echo base_url() ?>images_front/image/upload.jpg" width="" height="20" alt="" title="" />
					</div>
						<input type="file" name="my_picture3" id="myPic3" style="display:none;"  />
				</div>
				<div class="input-text"><input type="text" name = "filename3" readonly="true" id="myPicName3"/></div>
				<div class="btn-1"><a href="">Make Profile Picture</a></div>
			</li>
			
			<li>
				<?php $img4 = $this->My_model->select_where('dancer_img',array('img_id' => 4)); ?>
				<?php $count4 = $this->My_model->count_where('dancer_img',array('img_id' => 4));  ?>
				
				<?php if($count4 != 0): ?>
				<div class="dancer-img">
					<img src="<?php echo base_url() ?>uploads/spanish/images/<?php echo $img4->dancer_images ?>" alt=""/>
				</div>
				<?php else: ?>
				<div class="dancer-img"></div>
				<?php endif ?>
				<div class="upload-input">
					<div id="browse-btn4">
						<img src="<?php echo base_url() ?>images_front/image/upload.jpg" width="" height="20" alt="" title="" />
					</div>
						<input type="file" name="my_picture4" id="myPic4" style="display:none;" />
				</div>
				<div class="input-text"><input type="text" name = "filename4" readonly="true" id="myPicName4"/></div>
				<div class="btn-1"><a href="">Make Profile Picture</a></div>
			</li>
			
			<li>
				<?php $img5 = $this->My_model->select_where('dancer_img',array('img_id' => 5)); ?>
				<?php $count5 = $this->My_model->count_where('dancer_img',array('img_id' => 5));  ?>
				
				<?php if($count5 != 0): ?>
				<div class="dancer-img">
					<img src="<?php echo base_url() ?>uploads/spanish/images/<?php echo $img5->dancer_images ?>" alt=""/>
				</div>
				<?php else: ?>
				<div class="dancer-img"></div>
				<?php endif ?>
				<div class="upload-input">
					<div id="browse-btn5">
						<img src="<?php echo base_url() ?>images_front/image/upload.jpg" width="" height="20" alt="" title="" />
					</div>
					<input type="file" name="my_picture5" id="myPic5" style="display:none;" />
				</div>
				<div class="input-text"> <input type="text" name = "filename5" readonly="true" id="myPicName5"/></div>
				<div class="btn-1"><a href="">Make Profile Picture</a></div>
			</li>
			
			<li>
				<?php $img6 = $this->My_model->select_where('dancer_img',array('img_id' => 6)); ?>
				<?php $count6 = $this->My_model->count_where('dancer_img',array('img_id' => 6));  ?>
				
				<?php if($count6 != 0): ?>
				
				<div class="dancer-img">
					<img src="<?php echo base_url() ?>uploads/spanish/images/<?php echo $img6->dancer_images ?>" alt=""/>
				</div>
				<?php else: ?>
				<div class="dancer-img"></div>
				<?php endif ?>
				
				<div class="upload-input">
					<div id="browse-btn6">
						<img src="<?php echo base_url() ?>images_front/image/upload.jpg" width="" height="20" alt="" title="" />
					</div>
					<input type="file" name="my_picture6" id="myPic6" style="display:none;"/>
				</div>
				<div class="input-text"><input type="text" name = "filename6" readonly="true" id="myPicName6"/></div>
				<div class="btn-1"><a href="">Make Profile Picture</a></div>
			</li>
			
		</ul>
		<?php endif ?>
		</form>
		<!--
		<hr/>
		<div style="text-align: left;">
			<h4 class="upl_tit">Upload an image here</h4>
			<form style="margin:-10px 0 30px 30px;">
				<img src="<?php echo base_url() ?>images_front/image/upload.jpg" width="194" height="20" alt="" title="" />
			</form>
		</div>
	-->
	</div>
</div>