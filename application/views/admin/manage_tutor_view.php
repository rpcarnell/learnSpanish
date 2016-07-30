<style>
    .heading{
        padding:10px;
        background-color:#fff;
        text-align:center;
        font-size:18px;
        width:500px;
    }

    td{
        padding:10px;
        text-align:center;
        width:500px;
    }

    table{
        border-spacing:0px;
        font-size:14px;
    }

    .action{
        color:#7070FF;
    }

    .btn{
        padding:5px;
        border:0px;
        border-radius:3px;
        color:white;
        background-color:#B87700;
        border:1px solid #B87700;
    }

    .txt{
        border-radius:0px !important;
        width:260px;
        height:20px;
    }

    table a{
        color:#7070FF;
    }
</style>
<div id="main-wrapper">
    <div class="clr"></div>
    <div class="content-wrapper">
        <?php echo $breadcrumbs; ?><br /><br />
        <div class="content-page-view fl" style="width: 950px;margin: auto;"><br />
            <p><a class='tutorbutton' href="<?php echo base_url();?>admin/add_tutor">ADD TUTOR</a></p><br />
                <form method="post">
                    <input type="text" class="txt" name="keyword" value='<?php echo (isset($_POST['keyword'])) ? $_POST['keyword'] : '';?>' placeholder="Type your keywords here" style="float: left; padding: 5px; margin-left: 5px;" />
                   
                    <input type="submit" class="btn" name="btnsubmit" value="Search" style="float: left; margin-left: 5px;" />
                    
                    <div style="clear: both;"></div>
                    <?php echo (isset($_POST['keyword'])) ? "<div style='margin-top: 10px; clear: both;font-size: 12px'><a href='".base_url('admin/manage_tutors')."' >Clear</a></div>" : '';?>
                    <br><br>

                    <div class="clr"></div>
                </form>
                <?php
                if(count($spanish) > 0){

                    ?>
                    <table>
                        <tr>
                            <td class="heading"> Name of Tutor</td>
                            <td class="heading"> Email</td>
                            <td class="heading"> Phone</td>
                            <td class="heading"> Action</td>

                        </tr>

                        <?php
                        foreach($spanish as $d){
  
                              if(!empty($d->photo) && trim($d->photo) != ''):	
							$srcimg = '<div style="margin: 0px; padding: 0px;"><img src = "'.base_url('uploads/tutors/'.$d->photo).'" style="max-height: 100px; margin: 0px; padding: 0px;" /></div>';
						 else:
                                                     $srcimg = '<div style="margin: auto; background: #777; color: #fff; height: 100px; width: 100px; ">NO IMAGE AVAILABLE</div>';
                                                      if (1 == 2) { 
                                                      $srcimg = '<img src = "'.base_url('images/spanishforgood.png').'" width="182" height="232" /><br />'; }
						  endif;
                            
                            $l  = base_url('admin/view_tutor/' . $d->tutor_ID);
                            $ew  = base_url('admin/edit_tutor/' . $d->tutor_ID);
                            $ee = base_url('admin/delete_tutor/' . $d->tutor_ID);
                            echo <<<qaz
					<tr>
                            <td style="text-align: center; border-bottom: 1px solid #999;">{$srcimg}</td>
                                                <td style="border-bottom: 1px solid #999;">{$d->name}</td>
						<td style="border-bottom: 1px solid #999;">{$d->email_list}</td>
                        <td style="border-bottom: 1px solid #999;">{$d->phone}</td>
                        <td style="border-bottom: 1px solid #999;">
                            <a href="$l"/>View</a> | <a href="$ew"/>Edit</a> | <a href="$ee" class="del_user" onclick="return confirm('Do you want to delete this tutor?');">Delete</a><input type="hidden" value="{$d->tutor_ID}"/>
                        </td>
						
                    </tr>
qaz;
                        }
                        ?>
                    </table>
                <?php
                } else
                    echo '<br/><p style="text-align: center;color: red; font-size:16px;">No result(s) found.</p>';
                ?>
            
        </div>
        <div class="clr"></div>
    </div>

</div>
