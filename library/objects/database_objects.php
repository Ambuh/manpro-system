<?php
class template{
    public $template_id;
    public $template_name;
    public $template_status;
    public $template_author; 
} 
class menu_items{
    public $item_id;
    public $item_menuid;
    public $item_title;
    public $item_modules;
    public $item_status;
    public $item_link;
	public $item_parentid;
	public $item_children;
	public $item_hasChild;
	public $item_forsuper;
	public $item_isdefault;
	public $item_macroId;
	public $item_accesslevel;
	public $item_hasrestriction;
	public $item_isall;
	public $item_alias;
	public $item_rawLink;
}
class module{
    public $module_id;
    public $module_name;
    public $module_status;
    public $module_position;
    public $module_title;
    public $module_show_title;
    public $module_ismenu;
	public $module_menuassign;
    public $module_status_mode;
    public $module_for_super;
    public $module_suffix;
	public $module_accesslevel;
}
class macro{
    public $macro_id;
    public $compontnt_name;
    public $macro_status;
    public $macro_for_super;
    public $macro_mode_status;
    public $macro_title;
	public $macro_show_to_admin;
	public $macro_category;
	public $macro_description;
	public $macro_isRestricted;
}
class plugin{
    public $plugin_id;
    public $plugin_name;
    public $plugin_status;
	public $plugin_type;
}
class User_Session{
    public $username;
    public $id;
    public $parent_account;
    public $user_type;
    public $parent_id;
    public $firstname;
    public $secondname;
    public $lastname;
	public $email_address;
	public $profile_image;
	public $gender;
	public $cellphone;
    public $session_id;
	public $tokenTime;
	public $token;
	public $hasToken;
}

?>