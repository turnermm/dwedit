<?php
/**
 * Action adding DW Edit button to page tools (useful with fckedit)
 *
 * @author     Anonymous
 * @author     Kamil Demecki <kodstark@gmail.com>
 * @author     Myron Turner <turnermm02@shaw.ca>
 * @author     Davor Turkalj <turki.bsc@gmail.com>
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
        global $ACT, $ID, $REV, $INFO, $INPUT, $USERINFO,$conf;

        /* do I need to insert button ?  */
        if (!$this->ckgedit_loaded || $event->data['view'] != 'main' || $ACT != 'show')
        {
            return;
        }
        
        if(!isset($USERINFO) && strpos($conf['disableactions'], 'source') !== false) return;
        $mode = $INPUT->str('mode', 'fckg');
        if($mode == 'dwiki') return;

        /* check excluded namespaces */
        $dwedit_ns = $this->helper->getConf('dwedit_ns');
        if($dwedit_ns) {
            $ns_choices = explode(',',$dwedit_ns);
            foreach($ns_choices as $ns) {
              $ns = trim($ns);
              if(preg_match("/$ns/",$_REQUEST['id'])) return;
            }
        }
        
        /* insert button at second position  */
        $params = array('do' => 'edit');
        if($REV) {
            $params['rev'] = $REV;
        }
        $params['mode'] = 'dwiki';
        $params['fck_preview_mode'] = 'nil';

        $name = $this->helper->getLang('btn_dw_edit');  

        if ($INFO['perm'] > AUTH_READ) {
            $title = 'Classic DokuWiki Editor';
            $name = 'DokuWiki Editor';
            $edclass = 'dwedit';
        }
        else {
            $title = 'Classic DokuWiki View';
            $name = 'DokuWiki View';
            $edclass = 'dwview';
        }
        $link = '<a href="' . wl($ID, $params) . '" class="action ' . $edclass . '" rel="nofollow" title="' . $title . '"><span>' . $name . '</span></a>';

        if($param[0] == 'page_tools') {
            $link = '<li class = "dwedit">' . $link .'</li>';
        }
        else { 
            $link = '<span class = "dwedit">' . $link  .'</span>';          
        }

        $event->data['items'] = array_slice($event->data['items'], 0, 1, true) +
            array('dwedit' => $link) + array_slice($event->data['items'], 1, NULL, true);


    }
}
?>
