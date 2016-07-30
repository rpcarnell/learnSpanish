<?php

?>
<style>
    td{
        padding: 2px;
        text-align: center;
    }
    input{
        padding: 5px;
    }
  /*  tr:nth-child(even) {background: #fff; }*/
    </style>
<div id="main-wrapper">
    <div class="clr"></div>
    <div class="content-wrapper">
        <?php echo $breadcrumbs; ?><br /><br />
        <div class="content-page-view fl" style="width: 950px;margin: auto;"><br />
            <form onSubmit='return changePrices()'>
                <table style='border-spacing: 10px; border-collapse: separate;'><tr><th>Type</th><th>Quantity</th><th>Price</th></tr>
                    <tr><td colspan='3'><br /></td></tr>
            <?php
            $pricArr = array();
            foreach ($prices as $pr)
            {
                $pricArr[] = $pr->id;
                echo "<tr><td id='type_$pr->id'>".$pr->type."</td><td ><input type='text' id='quantity_$pr->id' style='width: 40px;' value='".$pr->quantity."' /></td>"
                        . "<td><input type='text' id='price_$pr->id' style='width: 40px;' value='".$pr->price."' /></td></tr>";
                echo "<tr><td colspan='3'><span class='warning' id='warning_$pr->id'></span><hr /></td></tr>";
            }
             
            
            ?>
                    <tr><td colspan='3'><p id="successWarn"></p><input type='submit' style='margin: auto;' value='Save Changes' /></td></tr>
                </table>
            </form>
        </div>
        </div>
    </div>
<script>
    function changePrices()
    {
        var price_ids = [<?php echo implode(',', $pricArr); ?>];
        jQuery('.mainwarning').html('');
        var $error = false;
        var $warning = '';
        goThroughPrices(0);
        
        function goThroughPrices(i)
        {
            $error = false;
            $warning = '';
            //jQuery('#successWarn').html('');
            if (i >= price_ids.length) return;
            jQuery('#successWarn').html('');
            console.log("We are here with " + i + " And " + price_ids.length);
            var type =  jQuery('#type_' + price_ids[i]).html();
            if (isNaN( jQuery('#price_' + price_ids[i]).val() )) { $error = true; $warning = 'Price is not numeric'; }
            else var price = jQuery('#price_' + price_ids[i]).val();
            
            if (isNaN( jQuery('#quantity_' + price_ids[i]).val() )) { $error = true; $warning = "Quantity is not numeric"; }
            else var quantity = jQuery('#quantity_' + price_ids[i]).val();
            
            if ($warning === '') jQuery('#warning_' + price_ids[i]).html("");
            else jQuery('#warning_' + price_ids[i]).html($warning);
            
            
            if ($error === false) {  
                  request = $.ajax({
                             url    : "<?php echo base_url('admin/changPricjax') ?>",
                             type   : "POST",
                             data   : { id: price_ids[i], type: type, price: price, quantity: quantity },
                             success: function(msg){  console.log('We make it here with i=' + i);
                                if (msg == 'ERROR') { alert('ERROR'); }
                                else { jQuery('#successWarn').html('Data has been Changed').css({'color' : '#5a5'}); }//.css({'color' : '#0d0'});
                                 goThroughPrices(i + 1);
                            }
                                
                               
                                
                             
                         });
                        request.fail(function(jqXHR, textStatus){
                            apprise('Something went wrong. Please try again later.', {'animate': true});   
           }) }
           else console.log('skipping this one');
        }
        return false;
    }
</script>

