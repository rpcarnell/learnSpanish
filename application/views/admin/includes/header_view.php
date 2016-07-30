<div id="header-wrapper">
    	<div class="top-header">
		<br>
           			<div class="clr"></div>
            <div class="search fr">
			<div class="clr"></div>
            	<div class="menu-pot">
							<div class="clr"></div>
                	<ul>
						<?php 
							// echo "<pre>";
							$adminCredentials = $this->session->userdata('adminCredentials');
							// var_dump($this->session->all_userdata());
							// die;
							
						?>
                    	 <li>Hello <?php echo ucfirst($adminCredentials['username']) . '';?><a style="margin-left:10px;" href="<?php echo base_url('admin/logout');?>"  style = "color:white !important; font-size:14px;">Log Out</a></li>
                    </ul>
                    <div class="clr"></div>
                </div>
                <div class="clr"></div>
                <script type="text/javascript" src="<?php echo base_url()?>js/lib/jquery1.7.1jquery.min.js"></script>
				<script type="text/javascript">
                    function lookup(inputString) {
                        if(inputString.length == 0) {
                            $('#suggestions').hide();
                        } else {
                            $.post("<?php echo base_url();?>admin/autocomplete/", {queryString: ""+inputString+""}, function(data){
                                if(data.length >0) {
                                    $('#suggestions').show();
                                    $('#autoSuggestionsList').html(data);
                                }
                            });
                        }
                    }
                    
                    function fill(thisValue) {
                        $('#inputString').val(thisValue);
                        setTimeout("$('#suggestions').hide();", 200);
                    }
					
					function lookup2(inputString2) {
                        if(inputString.length == 0) {
                            $('#suggestions2').hide();
                        } else {
                            $.post("<?php echo base_url();?>admin/autocomplete1/", {queryString: ""+inputString2+""}, function(data){
                                if(data.length >0) {
                                    $('#suggestions2').show();
                                    $('#autoSuggestionsList2').html(data);
                                }
                            });
                        }
                    }
                    
                    function fill(thisValue) {
                        $('#inputString2').val(thisValue);
                        setTimeout("$('#suggestions2').hide();", 200);
                    }
					
					
					 
                </script>
                
                
               
            </div>
            <div class="clr"></div>
        </div>
		