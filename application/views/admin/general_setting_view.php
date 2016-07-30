<?php echo page_restrict_author(); ?>
<?php echo page_restrict_manager(); ?>
<div id="main-wrapper">
    	
        <div class="clr"></div>
        <div class="content-wrapper">
        	<div class="content-header title-bars">
       			<h2>General Setting</h2>
                <div class="clr"></div>
            </div>
           
			
            	
            </div>           
           
           
            <div class="content-body form-padding">
            	
                <div class="form-conatiner-2 fl">
            	<div class="gen-container">
            
            <?php echo form_open();?>
                <p class="lbel">Site Title</p><p class="gen-inp"><input type="text" name="site_title" value="<?php echo $query->site_title ?>"></p>
                <div class="clr"></div>
                <p class="lbel">Tag Line</p><p class="gen-inp"><input type="text" name="tag_line" value="<?php echo $query->tag_line ?>"><span> In a few words, explain what this site is about.</span></p> 
                <div class="clr"></div>
                <p class="lbel">Meta</p><p><textarea name="meta_tags"><?php echo $query->meta?> </textarea></p>
                <div class="clr"></div>
                <p class="lbel">Email Address</p> 	
                <p class="gen-inp"><input type="text" name="email" value="<?php echo $query->email_address?>"> <span>This Address is used for Admin Purposes</span> </p>
                <div class="clr"></div>
                <p class="sub-gen"><input type="submit" value="Update" name="update"></p>
                <div class="clr"></div>
             <?php echo form_close();?>
            </div>
                
            </div>
            <div class="clr"></div>
        
        </div>
    
    </div>
     <div class="clr"></div>