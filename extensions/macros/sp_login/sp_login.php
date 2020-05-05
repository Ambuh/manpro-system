<?php
function sp_login(){
?>
<div class="login_wrapper">

    <div id="login_inner">
    
        <div id="form_div">
            <form action="?" method="POST">
            
            
            <div id="title_div" style="border-bottom:1px solid #05622d;"><?php if(defined("ADMIN_ROOT")){ ?>Administrator Login<?php }else{ ?>Login<?php } ?></div>
            
            <?php if(defined("ERROR_MESSAGE")){System::warningText(ERROR_MESSAGE,"text-align:center;");} ?>
            <div id="fg2" class="list_rw" style="font-size:12px; color:#05622d;">Enter Username and Password</div>
            <div class="list_rw" style="margin-top: 5px;"><div id="mini_label"><strong>Username</strong></div><input type="text" name="user_username" id="userdets"/></div>
            <div class="list_rw"><div id="mini_label"><strong>Password</strong></div><input type="password" name="user_password" id="userdets"/></div>
            <div class="list_rw" style="margin:10px;"><input type="submit" name="user_login" value="Login" id="btn_login"/><input type="reset" id="btn_reset" /></div>
            <input type="hidden" value="<?php echo time(); ?>" name="identifier" />
       <div id="form_row" style=" text-align:center;"><a id="fg" href="?p_option=@forgot_username" style="color:#05622d;font-size:12px; text-align:center;">Forgot Username      
</a> or <a href="?p_option=@forgot_password" style="color:#05622d;font-size:12px; text-align:center;margin-left:1px;">Forgot Password?
</a> </div>      </form>
        </div>
        </div>
        
         <div id="messages_div"><?php system::userInfo(); ?></div>      
     
     
      
    
     </div>  
      

<?php
    return true;
}
?>