<style>
h1{color:#FFA500;font-size:30px;font-family: Century Gothic;}
.title{font-size:24px;padding:5px;font-family: Century Gothic;}
.content{font-size:14px; text-align:center;font-family: Century Gothic;}
td { font-size: 12px; }	
.content, .title { font-size: 14px; }
</style>
<div id="main-wrapper">
    <div class="clr"></div>
  
            <?php echo $breadcrumbs; ?><br /><br />
            

        <div class="content-wrapper">

            <div class="content-page-view fl" style="width: 950px;margin: auto;">
					<?php
					  
					 
					?>
				<table>
					<tr>
						<td rowspan = "10" valign='top'>
						<?php if(!empty($spanish->photo) && trim($spanish->photo) != ''):?>	
							<img src = "<?php echo base_url('uploads/tutors/'.$spanish->photo)?>" />
						<?php else:?>
							<img src = "<?php echo base_url('images/spanishforgood.png')?>" width="282" height="332" />
						<?php endif?>
						</td>
					</tr>
					<tr>
						<td colspan = "2"><h1> <?php echo $spanish->name ?></h1> </td>
					</tr>
					<tr>
						<td class ="title"> E-Mail: </td> <td class = "content"><?php echo (empty($spanish->email_list) ? 'N/A' : "<a href='mailto:".$spanish->email_list."'>$spanish->email_list</a>") ?> </td>
					</tr>
					<tr>
						<td class = "title"> Skype: </td> <td class = "content"><?php echo (empty($spanish->skype_name) ? 'N/A' : $spanish->skype_name) ?></td>
					</tr>
					<tr>
						<td class = "title" valign="top"> Biography:  </td> <td class = "content" style="text-align: left;"> <?php echo (empty($spanish->bio) ? 'N/A' : nl2br($spanish->bio)) ?> </td>
					</tr>
					<tr>
						<td class = "title"> Deleted:  </td><td class = "content"> <?php echo (($spanish->deleted) ? 'YES' : 'NO') ?></td>
					</tr>
					<tr>
						<td class = "title"> Inactive:  </td><td class = "content"> <?php echo (($spanish->inactive) ? 'YES' : 'NO') ?></td>
					</tr>
					<tr>
						<td style="padding-top:31px;"><a style="padding:10px; " href="<?php echo base_url()?>admin/manage_tutors">Go back</a></td>
					</tr>
				</table>
            </div>
            <div class="clr"></div>
            <br /><div><h3>Notes Tutor can see:</h3><p class ="title"><?php echo ($spanish->notes_tutor_viewable) ? nl2br($spanish->notes_tutor_viewable) : 'None';?></p></div>
             <div><h3>Notes Hidden from Tutor:</h3><p class ="title"><?php echo ($spanish->notes_hidden_from_tutor) ? nl2br($spanish->notes_hidden_from_tutor) : 'None';?></p></div>
        </div>
</div>
