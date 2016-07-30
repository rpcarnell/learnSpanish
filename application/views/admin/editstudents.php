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
            <p><a class='tutorbutton' href="<?php echo base_url();?>admin/add_student">ADD STUDENT</a></p><br />
                <form method="post">
                    <input type="text" class="txt" name="keyword" value='<?php echo (isset($_POST['keyword'])) ? $_POST['keyword'] : '';?>' placeholder="Type your keywords here" style="float: left; padding: 5px; margin-left: 5px;" />
                   
                    <input type="submit" class="btn" name="btnsubmit" value="Search" style="float: left; margin-left: 5px;" />
                    
                    <div style="clear: both;"></div>
                    <?php echo (isset($_POST['keyword'])) ? "<div style='margin-top: 10px; clear: both;font-size: 12px'><a href='".base_url('admin/editstudents')."' >Clear</a></div>" : '';?>
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
 
                            $l  = base_url('admin/view_student/' . $d->Customer_ID);
                            $ew  = base_url('admin/edit_student/' . $d->Customer_ID);
                            $ee = base_url('admin/delete_student/' . $d->Customer_ID);
                             
                            if ($d->deleted > 0 || $d->inactive > 0)
                            {
                                $cuscolor = "a00";
                            }
                            elseif ($d->email_confirmed == 0)
                            {
                                $cuscolor = "999";
                            } else $cuscolor = "000";
                            echo <<<qaz
					<tr>
                                                <td><span style='color: #$cuscolor'>{$d->name}<span></td>
						<td>{$d->email_list}</td>
                        <td>{$d->phone}</td>
                        <td>
                            <a href="$l"/>View</a> | <a href="$ew"/>Edit</a> | <a href="$ee" class="del_user" onclick="return confirm('Do you want to delete this student?');">Delete</a><input type="hidden" value="{$d->Customer_ID}"/>
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
