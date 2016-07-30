<?php echo page_restrict_author(); ?>
<?php echo page_restrict_manager(); 

?>
<br /><br />
<div class="home-main-content"><?php echo $breadcrumbs; ?></div>
<div class="spanish">
    <div class="spanish-content contact-us-content">
  
        
        <div class="clr"></div>
        <div class="content-wrapper">
            <br />   
        	<div class="content-header title-bars">
       			<h2>Edit User <?php echo $spanish->name; ?></h2>
                <div class="clr"></div>                
            </div>
           
			
            	
                   
           
            <div class="content-body">
            	
           	<div class="form-conatiner-2 fl">
            	
            
             <?php echo form_open_multipart(); ?>
             <p><?php  echo $success; ?> </p>
              
						<?php if(!empty($spanish->photo) && trim($spanish->photo) != ''):?>	
							<img src = "<?php echo base_url('uploads/tutors/'.$spanish->photo)?>" />
						<?php else:?>
							<img src = "<?php echo base_url('images/spanishforgood.png')?>" width="282" height="332" />
						<?php endif?>
					 <br /><br />
             <div class="clr"></div>
            	<input type="hidden" name='date_today' value="<?php  echo $today = date("Y-m-d H:m:s");?>">
         		<div class="fl label">TUTOR NAME</div>
                <div class="fl"><input type="text" name="name" value='<?php echo $spanish->name; ?>' style=" height:20px; width:300px; "> <div class="clr"></div></div>
                <div class="clr"><br /></div> 
                
                 <div class="fl label">Picture</div>
                <div class="fl"><div> <input type='file' name="rolephoto" /></div>
                    <div class="clr"></div></div>
               
                 <br /> 
                
                <div class="fl label">DESCRIPTION</div>
                <div class="fl"><textarea name="description" style="height:250px; width: 70%;"><?php echo $spanish->bio;?></textarea><?php echo form_error('description'); ?><div class="clr"></div></div>
                <div class="clr"><br /></div> 
                
                <div class="fl label">PHONE</div>
                <div class="fl"><input type="text" name="phone" style=" height:20px; width:300px; " value='<?php echo $spanish->phone;?>'> <?php echo form_error('phone'); ?><div class="clr"></div></div>
                <div class="clr"><br /></div> 
                <div style='color: #a00;'><p><b>Leave the passwords blank unless you want to change the passwords as well</b></p></div>
                <div class="fl label">PASSWORD</div>
                <div class="fl"><input type="password" name="password" style=" height:20px; width:300px;"> <?php echo form_error('password'); ?><div class="clr"></div></div>
                <div class="clr"><br /></div>
                
                <div class="fl label">CONFIRM PASSWORD</div>
                <div class="fl"><input type="password" name="cpassword" style=" height:20px; width:300px;"><?php echo form_error('cpassword'); ?><div class="clr"></div></div>
                <div class="clr"><br /></div>
               	
                <div class="fl label">EMAIL</div>
                <div class="fl"><input type="text" name="emails" value='<?php echo $spanish->email_list;?>' style=" height:20px; width:300px;"><?php echo form_error('emails'); ?><div class="clr"></div></div>
                <div class="clr"><br /></div>
                
                
                <!-- <div class="fl label">USER TYPE</div>  
               <div class="fl">             
                <select name="user_type" style=" height:20px; width:300px;">
                	<option value="Manager">Manager</option>
                    <option value="Author">Author</option>
                </select>
                </div>-->
                <div class="clr"></div>
              
                
                <br />
                <input type='hidden' name='tutor_ID' value='<?php echo $spanish->tutor_ID;?>' />
                <input type="submit" name="edittutor" value="Edit Data"  style="width:150px; padding:5px; margin-bottom:10px;" >
            <?php echo form_close(); ?> 
            
                
            </div>
            <div class="clr"></div>
        
        </div>
    	</div> 
    </div></div>
     <div class="clr"></div>