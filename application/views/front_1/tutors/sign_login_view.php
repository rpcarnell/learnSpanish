<?php
// copied bdi

?>
<div style="display:none;">
    <div id="termsAndCond">
        <div><img src="<?php echo base_url() ?>images_front/terms/terms_conditions.png" width="366" height="36" alt="" title=""/></div>
        <?php //echo get_content(111); ?>
    </div>
</div>

<div class="spanish">
   <br />
        <div class="home-main-content"><?php echo $breadcrumbs; ?></div>
    <div class="spanish-content contact-us-content">
       
       
        
            <div>
                <div >
                    <h4 class="orange">Log In</h4>
                    <?php echo $err_log ?>
                    <form method="post" action="<?php echo base_url()."tutors/login";?>">
                        <ul class="signup-table">
                            <li>
                                <div>E-Mail:<br /><input type="text" name="email" value="<?php echo $this->input->post('email') ?>"/></div>
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
                               <div class="fl"><input type="checkbox" name="" value=""/>Remember Me </div>
                                <p class="fl"><a href="#" class="forg">Forgot password?</a></p>
                                </div>
                                <div class="clr">
                                </div>
                    </form>
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