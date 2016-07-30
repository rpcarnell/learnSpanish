<style>
h1{color:#FFA500;font-size:30px;font-family: Century Gothic;}
.title{font-size:24px;padding:5px;font-family: Century Gothic;}
.content{font-size:14px; text-align:center;font-family: Century Gothic;}
	
</style>
<div id="main-wrapper">
    <div class="clr"></div>
    <div class="content-wrapper">
        
            <div class="content-page-view fl" style="width: 950px;margin: auto;"><br />
		<?php echo $breadcrumbs; ?><br /><br />			<?php
					  
					 
					?>
				<table style="">
					 
					<tr>
						<td colspan = "2"><b>Username: </b><?php echo $spanish->username ?></h1> </td>
					</tr>
					<tr>
						<td> E-Mail: </td> <td class = "content"><?php echo (empty($spanish->email) ? 'N/A' : "<a href='mailto:".$spanish->email."'>$spanish->email</a>") ?> </td>
					</tr>
					<tr>
						<td> Skype: </td> <td class = "content"><?php echo (empty($spanish->skype) ? 'N/A' : $spanish->skype) ?></td>
					</tr>
					<tr>
						<td> Biography:  </td> <td class = "content" style="text-align: left;"> <?php echo (empty($spanish->bio) ? 'N/A' : nl2br($spanish->bio)) ?> </td>
					</tr>
					<tr>
						<td> Deleted:  </td><td class = "content"> <?php echo (isset($spanish->deleted) && $spanish->deleted) ? 'YES' : 'NO' ?></td>
					</tr>
					<tr>
						<td> Inactive:  </td><td class = "content"> <?php echo ( isset($spanish->inactive) && $spanish->inactive) ? 'YES' : 'NO' ?></td>
					</tr>
					 
				</table>
            </div>
            <div class="clr"></div>

        </div>
</div>
