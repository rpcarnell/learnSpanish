<!-- copied bdi -->
<div class="spanish">
    <div class="spanish-content contact-us-content">
         <h4 class="orange">Do you have any questions? We are here to help! <span>To contact us, simply fill out the message form below.</span></h4>
        <div class="contact-right">
            <form method="post">
                <ul>
                    <li>
                        <label for="name">Name <?php echo form_error('name') ?></label>
                        <div><input id="name" type="text" name="name" value="" /></div>
                    </li>
                    <li>
                        <label for="email">Email <?php echo form_error('email') ?></label>
                        <div><input id="email" type="text" name="email" value="" /></div>
                    </li>
                    <li>
                        <label for="cont_num">Contact No.</label>
                        <div><input id="cont_num" type="text" name="cont_num" value="" /></div>
                    </li>
                    <li>
                        <label for="contact-1">Please tell us who you are <?php echo form_error('who') ?></label>
                        <div>
                            <select name="who" id="contact-1" style="width:380px;">
                                <option selected="selected"> </option>
                                <option>Student</option>
                                <option>Tutor</option>
                                <option>Other</option>
                            </select>
                        </div>
                    </li>
                    <li>
                        <label for="msg">Message <?php echo form_error('msg') ?></label>
                        <div><textarea id="msg" name="msg"></textarea></div>
                    </li>
                    <li><input type="submit" name="send" value="SUBMIT" /></li>
                </ul>
            </form>
        </div>
        <div class="clr"></div>
    </div>
</div>