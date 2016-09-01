<?php
// copied bdi
$this->Fn_model->ch_login1();
?>
 
   
 

<div class="spanish">
   
    <div class="spanish-content">
       
         
        
            <div>
                <div >
                    <h4 class="orange" style='margin: 10px 5px 5px 0px'>Log In</h4>
                    <?php echo $err_log ?>
                    <div class='authform' >
                    <form method="post">
                        <ul class="signup-table" style="padding-left: 0px;">
                            <li >
                                <div >E-Mail:<br /><input type="text" name="email" value="<?php echo $this->input->post('email') ?>"/></div>
                            </li>
                            <li>Password:<br /><input id="password-password" type="password" name="pwd" value="" autocomplete="off"/>
                                
                            </li>
                            
                            <li><input type="submit" name="login" value="LOGIN"/></li>
                        </ul>
                        <div>
                                <style type="text/css" scoped>
                                    .forg:hover{
                                        color:white;
                                    }
                                </style>
                               <!--div class="fl"><input type="checkbox" name="" value=""/>Remember Me </div>-->
                                <p class="fl"><a href="#" class="forg">Forgot password?</a></p>
                                </div>
                                <div class="clr">
                                </div>
                    </form>
                        </div>
                </div>
            </div>
             
        <div class="clr"></div>
    </div>
</div>
<script type="text/javascript">
    $('#password-clear').focus(function(){
        $('#password-clear').hide();
        $('#password-password').show();
        $('#password-password').focus();
    });
    $('#password-password').blur(function(){
        if($('#password-password').val() == ''){
            $('#password-clear').show();
            $('#password-password').hide();
        }
    });
    $('#password-clear1').focus(function(){
        $('#password-clear1').hide();
        $('#password-password1').show();
        $('#password-password1').focus();
    });
    $('#password-password1').blur(function(){
        if($('#password-password1').val() == ''){
            $('#password-clear1').show();
            $('#password-password1').hide();
        }
    });
    $('#password-clear2').focus(function(){
        $('#password-clear2').hide();
        $('#password-password2').show();
        $('#password-password2').focus();
    });
    $('#password-password2').blur(function(){
        if($('#password-password2').val() == ''){
            $('#password-clear2').show();
            $('#password-password2').hide();
        }
    });
    $('.forg').click(function(){  
        apprise('Please Enter Your Registered Email Address', {'animate': true, 'input': true},
                function(ans){
                    if(ans){
                        var request = $.ajax({
                                                 url    : "<?php echo base_url('signup_login/forg_pass') ?>",
                                                 type   : "POST",
                                                 data   : {email: ans},
                                                 success: function(msg){
                                                     apprise(msg, {'animate': true});
                                                 }
                                             });
                        request.fail(function(jqXHR, textStatus){
                            apprise('Something went wrong. Please try again later.', {'animate': true});
                        });
                    }
                }
        );
    });
</script>
