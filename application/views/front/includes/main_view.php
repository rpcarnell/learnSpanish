<!DOCTYPE html>
<!-- copied bdi -->
<!--[if lt IE 7]>
<html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>
<html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>
<html class="no-js ie8 oldie" lang="en"> <![endif]-->

<html>
<head>
    <meta charset="utf-8">
    <meta name="description" content="">

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

    <title><?php echo $page_title ?></title>

    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>css/style.css"/>

  <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>css/ui-lightness/jquery.ui.timepicker.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>css/ui-lightness/jquery-ui-1.8.23.custom.css"/>

    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
 <script type="text/javascript" src="/js/jquery.jplayer.min.js"></script>
 <script type="text/javascript" src="<?php echo base_url() ?>js_front/clearfield.js"></script>
   <!-- <script type="text/javascript" src="<?php echo base_url() ?>js_front/custom.js"></script>-->

    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
  <script type="text/javascript" src="/js_front/tp/jquery.ui.timepicker.js?v=0.3.1"></script>

    <!-- surebet-->

</head>

<body style='padding: 0px; margin: 0px;'>
<div id="main-container">
    <div id="header-wrapper">
        <?php $this->load->view('front/includes/header_view'); ?>
    </div>

    <div id="main-wrapper">
        <?php $this->load->view($main_content); ?>
    </div>
</div>
<div id="footer-wrapper">
    <?php $this->load->view('front/includes/footer_view'); ?>
</div>
<script type="text/javascript"><!--
    $('.subscribe').click(function(){
        eid = $(this).next().val();
        apprise('Please enter your email address.', {'animate': true, 'input': true},
                function(a){
                    if(a){
                        $.ajax({
                                   type    : "POST",
                                   url     : "<?php echo base_url('spanish/validate_subscribers/'.$this->uri->segment(3)) ?>",
                                   data    : {eid: eid, email: a},
                                   dataType: "JSON",
                                   success : function(response){
                                       if(response.success != 4){
                                           //while not success
                                           apprise(response.err_msg);
                                       }else{
                                           //success
                                           //apprise('Subscription Success!');


                                           $.ajax({
                                                      url     : '<?php echo base_url("spanish/send_events/" . $this->uri->segment(3));?>',
                                                      type    : "POST",
                                                      data    : {eid: eid, email: a},
                                                      dataType: "JSON",
                                                      success : function(r){
                                                          apprise('Subscription Success!');
                                                      }
                                                  });

                                       }
                                   }
                               });
                    }else{
                        apprise('Please enter your email address!');
                    }
                }
        );

        $('input[type="text"]').attr('name', 'email');
        $('input[type="text"]').attr('autocomplete', 'off');
        return false;
    });
    -->
</script>

<script type="text/javascript" src="<?php echo base_url() ?>js_front/jquery.history.js"></script>

<script type="text/javascript" src="<?php echo base_url() ?>js_front/apprise-1.5.full.js"></script>

 
</body>
</html>
