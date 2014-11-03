<br/><br/><br/><br/><br/>
<div class="grid8 albums-mess">
    <?php include(APPPATH . 'views/views/profile/external/rightblockext.php'); ?>
    <div class="grid_4 width734">
        <h4 class="head1  rez_t"><span aria-hidden="true" class="icomoon-icon-gift"></span>Send a gift to <?php echo $user_to->username; ?></h4>
        <div class="box b2">              
            <div class="albums">
                <div class="box b2">
                    <?php if ($auth_email == false) { ?>
                        <div class="bs-callout bs-callout-warning">
                            <?php echo $translator['text39']; ?>
                        </div>
                    <?php } ?>
                    <div class="sign_up">                   
                        <div class="sin_form">
                            <form name="form" id="form" action="/profile/addgift/<?php echo $user_to->id; ?>" method="post" onsubmit="return validate()">   
                                <?php if ($balance > $min_cost_gift) { ?>
                                    <ul class="form_table">
                                        <li>
                                            <label>Gift to: </label>
                                            <div class="fullname">
                                                <input class='form-control'  data-validation="length" data-validation-length="min4"  type="text" name="to" id="to" maxlength="40" value='<?php echo $user_to->username; ?>' disabled>                                               
                                            </div>
                                        </li>
                                        <li>
                                            <label>Subject: </label>
                                            <div class="fullname">
                                                <input class='form-control'  data-validation="length" data-validation-length="min4"  type="text" name="subject" id="subject" maxlength="40" value=''>                                               
                                            </div>
                                        </li>
                                        <li>
                                            <label>Message: *</label>
                                            <div class="fullname">
                                                <textarea name="message" data-validation="length" data-validation-length="min4"  class="form-control"></textarea>                                           
                                            </div>
                                        </li>
                                        <li>
                                        <?php } ?>
                                        <br/>
                                        <div class="clearboth displayblock overflowauto">
                                            <?php foreach ($gifts as $gift) { ?>
                                                <div class="gift-wrapper">                                                
                                                    <img src="<?php echo $gift->url; ?>" width="214" height="214"/>
                                                    <div class="gift-descr">
                                                        <div><?php echo $gift->description; ?></div>

                                                        <?php if ($is_gold == '1') {                                                             
                                                            if ($mygiftscountgold < 5) { 
                                                                ?>
                                                                <input class="gifts" type="radio" name="gift<?php echo $gift->id; ?>"/> <?php echo $gift->coins; ?> coins
                                                            <?php }
                                                        } else { 
                                                            ?>
        <?php if ((int) $gift->coins  < (int) $balance) { //if ((int) $gift->coins + $cost_gift < (int) $balance) { ?><input class="gifts" type="radio" name="gift<?php echo $gift->id; ?>"/><?php } ?> <?php echo $gift->coins; ?> coins
    <?php } ?>

                                                    </div>
                                                </div>
                                        <?php } ?>
                                        </div>
                                        <?php if($is_gold!='1') { ?>
                                            <?php if ($balance > $min_cost_gift) { ?>
                                                </li>                                                                
                                                 </ul>
                                                <br/>
                                                     <div class="common_button fr mt10">
                                                        <div class="but_lft">
                                                            <div class="but_rgt">
                                                                <div class="but_mid">
                                                                    <input name="submit" type="submit"  class='btn-pink'>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                            <?php } else { ?>
                                                    <br/>
                                                    <div class='center'>
                                                        <h4><?php echo $translator['text37']; ?></h4>                    
                                                        <input type="button" onclick="window.location = '/profile/buycoins'" class="btn-pink" value="Buy coins"/>
                                                    </div>
                                            <?php } ?>
                                        <?php } else { ?>
                                                <?php if($mygiftscountgold>4) { ?>
                                                    <?php if ($balance > $min_cost_gift) { ?>
                                                            </li>                                                                
                                                        </ul>
                                                        <br/>
                                                        <div class="common_button fr mt10">
                                                            <div class="but_lft">
                                                                <div class="but_rgt">
                                                                    <div class="but_mid">
                                                                        <input name="submit" type="submit"  class='btn-pink'>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php } else { ?>
                                                        <br/>
                                                        <div class='center'>
                                                            <h4><?php echo $translator['text37']; ?></h4>                    
                                                            <input type="button" onclick="window.location = '/profile/buycoins'" class="btn-pink" value="Buy coins"/>
                
                                                    <?php } } else { ?>
                                                            </li>                                                                
                                                                                                </ul>
                                                                                                <br/>
                                                                                                <div class="common_button fr mt10">
                                                                                                    <div class="but_lft">
                                                                                                        <div class="but_rgt">
                                                                                                            <div class="but_mid">
                                                                                                                <input name="submit" type="submit"  class='btn-pink'>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                    <?php } ?>
                                        <?php } ?>
                            </form>
                        </div>      
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
                                jQuery(document).ready(function() {
                                    jQuery.validate();
                                });
                                function validate() {
                                    var flag = false;
                                    jQuery('.gifts').each(function() { // find unique names
                                        if (jQuery(this).attr('checked')) {
                                            flag = true;
                                        }
                                    }).promise().done(function() {
                                        if (flag === true) {
                                            jQuery('#form').submit();
                                        } else {
                                            alert('<?php echo $translator['text23']; ?>');
                                            return false;
                                        }
                                    });
                                    return false;
                                }
</script>








