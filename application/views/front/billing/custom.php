<div class="spanish">
     
<div class="spanish-content profile">
    <br />
    <?php
if (isset($tutor))
{ 
		echo "<div class='teacher-box'>";
		 echo "<img src='".base_url()."uploads/tutors/".$tutor->photo."' style='float: left; margin: 5px; height: 150px;  border-radius: 10px;' />";
		echo "<div><p>Teacher Name: <b>".ucwords($tutor->name)."</b></p></div>";
		
		echo "<div><p>".nl2br(substr($tutor->bio, 0,300))."...</p><br /></div><div style='clear: both;'></div>";
		echo "</div><div style='clear: both; margin-bottom: 0px;'></div>";
	
}
?>
        
       <form method="post"><div>
            <h2>Buy Non-Booked Items</h2>  
            <label>How much money?</label><br /><input type='text' style='width: 300px;' name='enterhours' />
            <br />
            <p>What is this for? i.e. Translate 20 pages, Christmas gift, etc</p>
            <table><tr>
                    <td><td><input type='submit' value="Submit" /></td>
                </tr></table>
            
           </div></form>
        </div>
    </div>