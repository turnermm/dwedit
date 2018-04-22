<?php

namespace dokuwiki\plugin\dwedit;

use dokuwiki\Menu\Item\AbstractItem;

/**
 * Class MenuItem
 *
 * Implements the PDF export button for DokuWiki's menu system
 *
 * @package dokuwiki\plugin\dwedit
 */
class MenuItem extends AbstractItem {

    /** @var string do action for this plugin */
    protected $type = 'edit';
    private  $btn_name;

    /** @var string icon file */
   protected $svg = __DIR__ . '/edit_pencil.svg';

    /**
     * MenuItem constructor.
     * @param string $btn_name (can be passed in from the  event handler)
     */
    public function __construct($btn_name = "") {
        parent::__construct();
        global $REV, $INFO;
         
         if($btn_name)  {
            $this->btn_name = $btn_name;     
         }               
        
        if($REV) $this->params['rev'] = $REV;
        
        /*switching over to the native dw editor rquires two additional http paramters */
        $this->params['mode'] = 'dwiki';   
        $this->params['fck_preview_mode'] = 'nil';        
        
        if ($INFO['perm'] < AUTH_EDIT) {   // use alternate icon if user does not have edit permission
            $this->svg =  __DIR__ . '/book-open.svg';
        }
    }

    /**
     * Get label from plugin language file
     *
     * @return string
     */
    public function getLabel() {        
        if($this->btn_name) return $this->btn_name;
    /* 
        if the button name has not been set up  in the constructor    
        you can get it now.
        Note:    In the current case the name is guaranteed by
        having been hard-coded in the event of a name not having been found        
     */
         $hlp = plugin_load('action', 'dwedit');   
        return $hlp->getLang('btn_dw_edit');
       
        
    }
}
