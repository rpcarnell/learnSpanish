<?php echo page_restrict_author(); ?>
<?php echo page_restrict_manager(); ?>
<div id="main-wrapper">
        <div class="clr"></div>
        <div class="content-wrapper">
        	<div class="content-header title-bars">
       			<h2>Add User</h2>
                <div class="clr"></div>                
            </div>
           
			
            	
                   
           
            <div class="content-body">
            	
           	<div class="form-conatiner-2 fl">
            	
            
             <?php echo form_open_multipart(); ?>
             <p><?php  echo $success; ?> </p>
             <div class="clr"></div>
            	<input type="hidden" name='date_today' value="<?php  echo $today = date("Y-m-d H:m:s");?>">
         		<div class="fl label">TUTOR NAME</div>
                <div class="fl"><input type="text" name="name" style=" height:20px; width:300px; "> <?php echo form_error('uname'); ?><div class="clr"></div></div>
                <div class="clr"><br /></div> 
                
                 <div class="fl label">Picture</div>
                <div class="fl"><div> <input type='file' name="rolephoto" /></div>
                    <div class="clr"></div></div>
               
                 <br /> 
                
                <div class="fl label">DESCRIPTION</div>
                <div class="fl"><textarea name="description" style=" height:150px; width:300px; "></textarea><?php echo form_error('description'); ?><div class="clr"></div></div>
                <div class="clr"><br /></div> 
                
                <div class="fl label">PHONE</div>
                <div class="fl"><input type="text" name="phone" style=" height:20px; width:300px; "> <?php echo form_error('phone'); ?><div class="clr"></div></div>
                <div class="clr"><br /></div> 
                
                <div class="fl label">PASSWORD</div>
                <div class="fl"><input type="password" name="password" style=" height:20px; width:300px;"> <?php echo form_error('password'); ?><div class="clr"></div></div>
                <div class="clr"><br /></div>
                
                <div class="fl label">CONFIRM PASSWORD</div>
                <div class="fl"><input type="password" name="cpassword" style=" height:20px; width:300px;"><?php echo form_error('cpassword'); ?><div class="clr"></div></div>
                <div class="clr"><br /></div>
               	
                <div class="fl label">EMAIL</div>
                <div class="fl"><input type="text" name="emails" style=" height:20px; width:300px;"><?php echo form_error('emails'); ?><div class="clr"></div></div>
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
                <input type="submit" name="add" value="ADD"  style="width:100px; padding:5px; margin-bottom:10px;" >
            <?php echo form_close(); ?> 
            
                
            </div>
            <div class="clr"></div>
        
        </div>
    	</div> 
    </div>
     <div class="clr"></div>