<?php
$method = $this->router->fetch_method();
$class	= $this->router->fetch_class();
?>
<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="en"> <![endif]-->

<html>
<head>
    <meta charset="utf-8">
    <?php header("Last-Modified: " . gmdate( "D, j M Y H:i:s" ) . " GMT"); // Date in the past
    header("Expires: " . gmdate( "D, j M Y H:i:s", time() ) . " GMT"); // always modified
    header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
    header("Cache-Control: post-check=0, pre-check=0", FALSE);
    header("Pragma: no-cache");
    ?>

    <title><?php echo ucfirst($class)." || ".str_replace("_"," ",ucfirst($method));?></title>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/farbtastic.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/style.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/reset.css" />
      <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>css/ui-lightness/jquery.ui.timepicker.css"/>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>css/ui-lightness/jquery-ui-1.8.23.custom.css"/>
<!--    <script type="text/javascript" src="--><?php //echo base_url(); ?><!--fancy/lib/jquery-1.7.2.min.js"></script>-->
<!--    <link rel="stylesheet" type="text/css" href="--><?php //echo base_url(); ?><!--fancy/source/jquery.fancybox.css?v=2.0.6" media="screen" />-->
   <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
 <script type="text/javascript" src="/js/jquery.jplayer.min.js"></script>
 <script type="text/javascript" src="<?php echo base_url() ?>js_front/clearfield.js"></script>
   <!-- <script type="text/javascript" src="<?php echo base_url() ?>js_front/custom.js"></script>-->

    <script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
  <script type="text/javascript" src="/js_front/tp/jquery.ui.timepicker.js?v=0.3.1"></script>

</head>

<body>
<?php $display = $this->My_model->select_where('display_setting',array('id'=>1));?>
<style type="text/css">
    body{ color:#000; font-family: 'Century Gothic';  }
    <!--body{ background:<?php# echo $display->body_color?> !important; }-->
    .add-product-menu, .add-product-menu li, .main-menu, .admin-sidebar {  background:<?php echo $display->main_menu_bg; ?> !important;}

    .add-product-menu li .admin-sidebar, .active,.sub-menu{ background:<?php echo $display->sub_menu_bg; ?> !important;}
    /**fonts**/
    .main-menu-list a, .admin-sidebar a{color:<?php echo $display->main_menu_fc; ?> !important; }
    .sub-menu-list a{color:<?php echo $display->sub_menu_fc; ?> !important;}
    .main-menu-list li
    {
        margin-bottom: 20px;
         list-style-type: none;
    }
    .add-product-menu , .recent-order, .recent-activity, .quick-links{border: 1px solid <?php echo $display->sub_menu_bg; ?> !important;}
    .news-loop{ border-bottom:1px solid <?php echo $display->sub_menu_bg; ?>; }

    /**title bar**/
    .content-header , .title-bar, .title-bar2, .month { background:<?php echo $display->title_bar_bg; ?> !important; color:<?php echo $display->title_bar_fc ?> !important;}

    /***fonts***/
    .content-header li{color:<?php echo $display->title_bar_fc ?> !important;}
    #footer-wrapper{ background: <?php echo $display->sub_menu_bg; ?>; !important;}
</style>
<?php $this->load->view('admin/includes/header_view'); ?>
<div id="main-container">
   
    <?php $this->load->view($main_content); ?>
</div>

<!-- Use this when you upload your file -->

<!-- <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script> -->
<script type="text/javascript" src="js/lib/jquery1.7.1jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/clearfield.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/farbtastic.js"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('#demo').hide();
//        var f = $.farbtastic('#picker');
        var p = $('#picker').css('opacity', 0.25);
        var selected;
        $('.colorwell')
            .each(function () { f.linkTo(this); $(this).css('opacity', 0.75); })
            .focus(function() {
                       if (selected) {
                           $(selected).css('opacity', 0.75).removeClass('colorwell-selected');
                       }
                       f.linkTo(this);
                       p.css('opacity', 1);
                       $(selected = this).css('opacity', 1).addClass('colorwell-selected');
                   });
    });


    $(window).load(function() {
        $('.class-name li').last().css('margin-right',0);
        $('.clearField').clearField();
    });

    $('#def_link').click( function (){
        $('#custom_link').fadeIn("fast");
        $('#custom_link2').fadeIn("fast");
        $('#def_link2').fadeOut("fast");
        $('#def_link').fadeOut("fast");
    });

    $('#custom_link2').click( function (){
        $('#custom_link').fadeOut("fast");
        $('#custom_link2').fadeOut("fast");
        $('#def_link2').fadeIn("fast");
        $('#def_link').fadeIn("fast");
    });

    $('#foo').change( function (){
        aw = $('#foo').val();

        window.location = "<?php echo base_url();?>admin/pages/"+aw;

    });

    $('#foo2').change( function (){
        aw = $('#foo2').val();
    });

    $('#del_mul').click( function (){
        var chk = [];
        $("input:checkbox:checked").each(function() {
            chk.push($(this).val());
        });

        ew = chk.join("-");

        if(!ew==""){
            var conf= confirm("Do you really want  to delete this items?");
            if (conf == true){
                window.location.href = "<?php echo base_url();?>admin/multiple_delete/"+ew;
            }

        }else{
            alert('You do Not Select Anything');
        }
    });

    $('#del_mul2').click( function (){
        var chk = [];
        $("input:checkbox:checked").each(function() {
            chk.push($(this).val());
        });


        ew = chk.join("-");

        if(!ew==""){
            var conf= confirm("Do you really want  to delete this items?");
            if (conf == true){
                window.location.href = "<?php echo base_url();?>admin/multiple_delete_default/"+ew;
            }

        }else{
            alert('You do Not Select Anything');
        }
    });

    $('#del_mul3').click( function (){
        var chk = [];
        $("input:checkbox:checked").each(function() {
            chk.push($(this).val());
        });


        ew = chk.join("-");

        if(!ew==""){
            var conf= confirm("Do you really want  to delete this items?");
            if (conf == true){
                window.location.href = "<?php echo base_url();?>admin/multiple_delete_default1/"+ew;
            }

        }else{
            alert('You do Not Select Anything');
        }
    });


    $('#del_mul_blog').click( function (){
        var chk = [];
        $("input:checkbox:checked").each(function() {
            chk.push($(this).val());
        });


        ew = chk.join("-");

        if(!ew==""){
            var conf= confirm("Do you really want  to delete this items?");
            if (conf == true){
                window.location.href = "<?php echo base_url();?>admin/multiple_delete_blog/"+ew;
            }

        }else{
            alert('You do Not Select Anything');
        }

    });
</script>

</body>
</html>
