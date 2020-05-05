<?php
function sp_login(){
?>
<div class="login_wrapper">
    <div id="login_inner">
        <div id="form_div">
            <form action="?" method="POST">
            <div id="title_div" style="border-bottom:1px solid #05622d;"><?php if(defined("ADMIN_ROOT")){ ?>Administrator Login<?php }else{ ?>Login<?php } ?></div>
            <?php system::warningText(ERROR_MESSAGE); ?>
            <div class="list_rw" style="font-size:12px; margin:10px;text-align: center; color:#05622d;">Enter Username and Password</div>
            <div class="list_rw" style="margin-top: 5px;"><strong>Username</strong><input type="text" name="user_username" id="userdets"/></div>
            <div class="list_rw"><strong>Password</strong><input type="password" name="user_password" id="userdets"/></div>
            <div class="list_rw" style="margin:10px;"><input type="submit" name="user_login" value="Login" id="btn_login"/><input type="reset" id="btn_reset" /></div>
            <input type="hidden" value="<?php echo time(); ?>" name="identifier" />
        </form>
        </div>
    </div>
     
     <div id="messages_div"><?php system::userInfo(); ?></div>        
     
</div>

<?php
    return true;
}
?>