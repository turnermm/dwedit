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
    var $helper;
    function __construct() {
       $list = plugin_list('helper');
       if(in_array('ckgedit',$list)) {
           $this->ckgedit_loaded=true;
           $this->helper = plugin_load('helper', 'ckgedit');
       }
       else if(in_array('ckgdoku',$list)) {
           $this->ckgedit_loaded=true;
           $this->helper = plugin_load('helper', 'ckgdoku');
       }
    }    

    function register(Doku_Event_Handler $controller)
    {
        $controller->register_hook('TEMPLATE_PAGETOOLS_DISPLAY', 'BEFORE', $this, 'dwedit_action_link',array('page_tools'));
        $controller->register_hook('TEMPLATE_DWEDITLINK_DISPLAY', 'BEFORE', $this, 'dwedit_action_link', array('user'));
    }
    
    function dwedit_action_link(&$event, $param)
    {
        if (!$_SERVER['REMOTE_USER'] || ! $this->ckgedit_loaded) 
        {
            return;
        }
        
           global $ID, $ACT, $INPUT;
           $FG_cookie = $_COOKIE['FCKG_USE'];           
           $mode = $INPUT->str('mode', 'fckg');
           if($ACT == 'edit' && ($FG_cookie == '_false_' || $mode == 'dwiki')) return;
           $name = $this->helper->getLang('btn_dw_edit');  
           $event->data['view'] = 'detail';
           $link = '<a href="doku.php?id=' . $ID . '&do=edit&mode=dwiki&fck_preview_mode=nil" ' . 'class="action edit" rel="nofollow" title="DW Edit"><span>' . $name.'</span></a>';
           if($param[0] == 'page_tools') {
               $event->data['items']['dw_edit'] = '<li>' . $link .'</li>';
           }
           else { 
             $event->data['items']['dwedit'] = '<span class = "dwedit">' . $link  .'</span>';          
           }

    }
}
?>