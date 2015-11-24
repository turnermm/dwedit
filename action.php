<?php
/**
 * Action adding DW Edit button to page tools (useful with fckedit)
 *
 * @author     Anonymous
 * @author     Kamil Demecki <kodstark@gmail.com>
 * @author     Myron Turner <turnermm02@shaw.ca>
 */

if (!defined('DOKU_INC')) 
{    
    die();
}

class action_plugin_dwedit extends DokuWiki_Action_Plugin
{
    var $ckgedit_loaded = false;

    function __construct() {
       $list = plugin_list('helper');
       if(in_array('ckgedit',$list)) {
           $this->ckgedit_loaded=true;
       }
      
    }    
    function register(&$controller)
    {
        $controller->register_hook('TEMPLATE_PAGETOOLS_DISPLAY', 'BEFORE', $this, 'dwedit_action_link');
    }
    
    function dwedit_action_link(&$event, $param)
    {
        if (!$_SERVER['REMOTE_USER'] || ! $this->ckgedit_loaded) 
        {
            return;
        }
        
        global $ID;

        $event->data['items']['dw_edit'] = '<li class="dwedit"><a href="doku.php?id=' . $ID .
         '&do=edit&mode=dwiki&fck_preview_mode=nil" ' . 'class="action edit" rel="nofollow" title="DW Edit"><span>DW Edit</span></a></li>';

    }
    
 
}
?>