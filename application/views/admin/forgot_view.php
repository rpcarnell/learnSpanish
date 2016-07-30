<center>
<div style="padding:5px; width:auto;">
<?php echo validation_errors(); ?>
<?php echo form_open();?>
<label for="">Email:</label>
<input type="text" name="email" />
<input type="submit" value="Submit" name="send"/>
<?php echo form_close();?>
</div>
</center>