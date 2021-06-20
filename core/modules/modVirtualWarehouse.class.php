<?php

/**
 * This Dolibarr plugin helps you track your product stock when you have employeed carring products with them
 *
 * @date           File created on Tue Jun 15 2021 17:27:19
 *
 * @category       Dolibarr plugin
 * @package        Virtual Stock
 * @link           https://blacktiehost.com/shop/dolibarr-modules/
 * @since          1.0
 * @version        1.0
 * @author         Milen Karaganski <milen@blacktiehost.com>
 * @license        GPL-2.0+
 * @license        http://www.gnu.org/licenses/gpl-2.0.txt
 * @copyright      Copyright (c) 2021 blacktiehost.com
 *
 */

/* Copyright (C) 2004-2018  Laurent Destailleur     <eldy@users.sourceforge.net>
 * Copyright (C) 2018-2019  Nicolas ZABOURI         <info@inovea-conseil.com>
 * Copyright (C) 2019-2020  Frédéric France         <frederic.france@netlogic.fr>
 * Copyright (C) 2021 SuperAdmin
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */

include_once DOL_DOCUMENT_ROOT . '/core/modules/DolibarrModules.class.php';

/**
 *  Description and activation class for module VirtualWarehouse
 */
class modVirtualWarehouse extends DolibarrModules
{
    /**
     * Constructor. Define names, constants, directories, boxes, permissions
     *
     * @param DoliDB $db Database handler
     */
    public function __construct($db)
    {
        global $langs, $conf;
        $this->db = $db;

        // Id for module (must be unique).
        // Use here a free id (See in Home -> System information -> Dolibarr for list of used modules id).
        $this->numero = 274352; // TODO Go on page https://wiki.dolibarr.org/index.php/List_of_modules_id to reserve an id number for your module

        // Key text used to identify module (for permissions, menus, etc...)
        $this->rights_class = 'virtualwarehouse';

        // Family can be 'base' (core modules),'crm','financial','hr','projects','products','ecm','technic' (transverse modules),'interface' (link with external tools),'other','...'
        // It is used to group modules by family in module setup page
        $this->family = "other";

        // Module position in the family on 2 digits ('01', '10', '20', ...)
        $this->module_position = '90';

        // Gives the possibility for the module, to provide his own family info and position of this family (Overwrite $this->family and $this->module_position. Avoid this)
        //$this->familyinfo = array('myownfamily' => array('position' => '01', 'label' => $langs->trans("MyOwnFamily")));
        // Module label (no space allowed), used if translation string 'ModuleVirtualWarehouseName' not found (VirtualWarehouse is name of module).
        $this->name = preg_replace('/^mod/i', '', get_class($this));

        // Module description, used if translation string 'ModuleVirtualWarehouseDesc' not found (VirtualWarehouse is name of module).
        $this->description = "VirtualWarehouseDescription";
        // Used only if file README.md and README-LL.md not found.
        $this->descriptionlong = "VirtualWarehouseDescription";

        // Author
        $this->editor_name = 'Milen Karaganski';
        $this->editor_url = 'https://blacktiehost.com';

        // Possible values for version are: 'development', 'experimental', 'dolibarr', 'dolibarr_deprecated' or a version string like 'x.y.z'
        $this->version = '1.0';
        // Url to the file with your last numberversion of this module
        //$this->url_last_version = 'http://www.example.com/versionmodule.txt';

        // Key used in llx_const table to save module status enabled/disabled (where VIRTUALWAREHOUSE is value of property name of module in uppercase)
        $this->const_name = 'MAIN_MODULE_' . strtoupper($this->name);

        // Name of image file used for this module.
        // If file is in theme/yourtheme/img directory under name object_pictovalue.png, use this->picto='pictovalue'
        // If file is in module/img directory under name object_pictovalue.png, use this->picto='pictovalue@module'
        // To use a supported fa-xxx css style of font awesome, use this->picto='xxx'
        $this->picto = 'pictovalue@VirtualWarehouse';

        // Define some features supported by module (triggers, login, substitutions, menus, css, etc...)
        $this->module_parts = array(
            // Set this to 1 if module has its own trigger directory (core/triggers)
            'triggers' => 0,
            // Set this to 1 if module has its own login method file (core/login)
            'login' => 0,
            // Set this to 1 if module has its own substitution function file (core/substitutions)
            'substitutions' => 0,
            // Set this to 1 if module has its own menus handler directory (core/menus)
            'menus' => 0,
            // Set this to 1 if module overwrite template dir (core/tpl)
            'tpl' => 0,
            // Set this to 1 if module has its own barcode directory (core/modules/barcode)
            'barcode' => 0,
            // Set this to 1 if module has its own models directory (core/modules/xxx)
            'models' => 1,
            // Set this to 1 if module has its own printing directory (core/modules/printing)
            'printing' => 0,
            // Set this to 1 if module has its own theme directory (theme)
            'theme' => 0,
            // Set this to relative path of css file if module has its own css file
            'css' => array(
                //    '/virtualwarehouse/css/virtualwarehouse.css.php',
            ),
            // Set this to relative path of js file if module must load a js on all pages
            'js' => array(
                //   '/virtualwarehouse/js/virtualwarehouse.js.php',
            ),
            // Set here all hooks context managed by module. To find available hook context, make a "grep -r '>initHooks(' *" on source code. You can also set hook context to 'all'
            'hooks' => array(
                //   'data' => array(
                //       'hookcontext1',
                //       'hookcontext2',
                //   ),
                //   'entity' => '0',
            ),
            // Set this to 1 if features of module are opened to external users
            'moduleforexternal' => 0,
        );

        // Data directories to create when module is enabled.
        // Example: this->dirs = array("/virtualwarehouse/temp","/virtualwarehouse/subdir");
        $this->dirs = array("/virtualwarehouse/temp");

        // Config pages. Put here list of php page, stored into virtualwarehouse/admin directory, to use to setup module.
        $this->config_page_url = array("setup.php@virtualwarehouse");

        // Dependencies
        // A condition to hide module
        $this->hidden = false;
        // List of module class names as string that must be enabled if this module is enabled. Example: array('always1'=>'modModuleToEnable1','always2'=>'modModuleToEnable2', 'FR1'=>'modModuleToEnableFR'...)
        $this->depends = array();
        $this->requiredby = array(); // List of module class names as string to disable if this one is disabled. Example: array('modModuleToDisable1', ...)
        $this->conflictwith = array(); // List of module class names as string this module is in conflict with. Example: array('modModuleToDisable1', ...)

        // The language file dedicated to your module
        $this->langfiles = array("virtualwarehouse@virtualwarehouse");

        // Prerequisites
        $this->phpmin = array(5, 5); // Minimum version of PHP required by module
        $this->need_dolibarr_version = array(11, -3); // Minimum version of Dolibarr required by module

        // Messages at activation
        $this->warnings_activation = array(); // Warning to show when we activate module. array('always'='text') or array('FR'='textfr','ES'='textes'...)
        $this->warnings_activation_ext = array(); // Warning to show when we activate an external module. array('always'='text') or array('FR'='textfr','ES'='textes'...)
        //$this->automatic_activation = array('FR'=>'VirtualWarehouseWasAutomaticallyActivatedBecauseOfYourCountryChoice');
        //$this->always_enabled = true;								// If true, can't be disabled

        // Constants
        // List of particular constants to add when module is enabled (key, 'chaine', value, desc, visible, 'current' or 'allentities', deleteonunactive)
        // Example: $this->const=array(1 => array('VIRTUALWAREHOUSE_MYNEWCONST1', 'chaine', 'myvalue', 'This is a constant to add', 1),
        //                             2 => array('VIRTUALWAREHOUSE_MYNEWCONST2', 'chaine', 'myvalue', 'This is another constant to add', 0, 'current', 1)
        // );
        $this->const = array();

        if (!isset($conf->virtualwarehouse) || !isset($conf->virtualwarehouse->enabled)) {
            $conf->virtualwarehouse = new stdClass();
            $conf->virtualwarehouse->enabled = 0;
        }

        // Permissions provided by this module
        $this->rights = array();
        $r = 0;
        // Add here entries to declare new permissions
        /* BEGIN MODULEBUILDER PERMISSIONS */
        $this->rights[$r][0] = 274352001; // Permission id (must not be already used)
        $this->rights[$r][1] = 'Read objects of VirtualWarehouse'; // Permission label
        $this->rights[$r][4] = 'virtualstock'; // In php code, permission will be checked by test if ($user->rights->virtualwarehouse->level1->level2)
        $this->rights[$r][5] = 'read'; // In php code, permission will be checked by test if ($user->rights->virtualwarehouse->level1->level2)
        $r++;
        $this->rights[$r][0] = 274352002; // Permission id (must not be already used)
        $this->rights[$r][1] = 'Create/Update objects of VirtualWarehouse'; // Permission label
        $this->rights[$r][4] = 'virtualstock'; // In php code, permission will be checked by test if ($user->rights->virtualwarehouse->level1->level2)
        $this->rights[$r][5] = 'write'; // In php code, permission will be checked by test if ($user->rights->virtualwarehouse->level1->level2)
        $r++;
        $this->rights[$r][0] = 274352003; // Permission id (must not be already used)
        $this->rights[$r][1] = 'Delete objects of VirtualWarehouse'; // Permission label
        $this->rights[$r][4] = 'virtualstock'; // In php code, permission will be checked by test if ($user->rights->virtualwarehouse->level1->level2)
        $this->rights[$r][5] = 'delete'; // In php code, permission will be checked by test if ($user->rights->virtualwarehouse->level1->level2)
        $r++;
        /* END MODULEBUILDER PERMISSIONS */

        // Main menu entries to add
        $this->menu = array();
        $r = 0;
        // Add here entries to declare new menus
        /* BEGIN MODULEBUILDER TOPMENU */
        $this->menu[$r++] = array(
            'fk_menu' => '', // '' if this is a top menu. For left menu, use 'fk_mainmenu=xxx' or 'fk_mainmenu=xxx,fk_leftmenu=yyy' where xxx is mainmenucode and yyy is a leftmenucode
            'type' => 'top', // This is a Top menu entry
            'titre' => 'ModuleVirtualWarehouseName',
            'mainmenu' => 'virtualwarehouse',
            'leftmenu' => '',
            'url' => '/virtualwarehouse/index.php',
            'langs' => 'virtualwarehouse@virtualwarehouse', // Lang file to use (without .lang) by module. File must be in langs/code_CODE/ directory.
            'position' => 1000 + $r,
            'enabled' => '$conf->virtualwarehouse->enabled', // Define condition to show or hide menu entry. Use '$conf->virtualwarehouse->enabled' if entry must be visible if module is enabled.
            'perms' => '1', // Use 'perms'=>'$user->rights->virtualwarehouse->virtualstock->read' if you want your menu with a permission rules
            'target' => '',
            'user' => 2, // 0=Menu for internal users, 1=external users, 2=both
        );
        /* END MODULEBUILDER TOPMENU */
        /* LEFT MENU */
        $this->menu[$r++] = array(
            'fk_menu' => 'fk_mainmenu=virtualwarehouse',
            'type' => 'left',
            'titre' => 'ListStock',
            'mainmenu' => 'virtualwarehouse',
            'leftmenu' => 'liststock',
            'url' => '/virtualwarehouse/index.php',
            'langs' => 'virtualwarehouse@virtualwarehouse',
            'position' => 1000 + $r,
            'enabled' => '$conf->virtualwarehouse->enabled',
            'perms' => '$user->rights->virtualwarehouse->virtualstock->read',
            'target' => '',
            'user' => 0,
        );
        $this->menu[$r++] = array(
            'fk_menu' => 'fk_mainmenu=virtualwarehouse',
            'type' => 'left',
            'titre' => 'AddRecord',
            'mainmenu' => 'virtualwarehouse',
            'leftmenu' => 'addrecord',
            'url' => '/virtualwarehouse/add.php',
            'langs' => 'virtualwarehouse@virtualwarehouse',
            'position' => 1000 + $r,
            'enabled' => '$conf->virtualwarehouse->enabled',
            'perms' => '$user->rights->virtualwarehouse->virtualstock->delete',
            'target' => '',
            'user' => 0,
        );
        $this->menu[$r++] = array(
            'fk_menu' => 'fk_mainmenu=virtualwarehouse',
            'type' => 'left',
            'titre' => 'RecordsList',
            'mainmenu' => 'virtualwarehouse',
            'leftmenu' => 'recordslist',
            'url' => '/virtualwarehouse/agenda.php',
            'langs' => 'virtualwarehouse@virtualwarehouse',
            'position' => 1000 + $r,
            'enabled' => '$conf->virtualwarehouse->enabled',
            'perms' => '$user->rights->virtualwarehouse->virtualstock->delete',
            'target' => '',
            'user' => 0,
        );
        /* END LEFT MENU */
    }
    /**
     *  Function called when module is enabled.
     *  The init function add constants, boxes, permissions and menus (defined in constructor) into Dolibarr database.
     *  It also creates data directories
     *
     *  @param      string  $options    Options when enabling module ('', 'noboxes')
     *  @return     int             	1 if OK, 0 if KO
     */
    public function init($options = '')
    {
        global $conf, $langs;

        $result = $this->_load_tables('/virtualwarehouse/sql/');
        if ($result < 0) return -1; // Do not activate module if error 'not allowed' returned when loading module SQL queries (the _load_table run sql with run_sql with the error allowed parameter set to 'default')

        // Permissions
        $this->remove($options);

        $sql = array();

        // Document templates
        $moduledir = 'virtualwarehouse';
        $myTmpObjects = array();
        $myTmpObjects['VirtualStock'] = array('includerefgeneration' => 0, 'includedocgeneration' => 0);

        foreach ($myTmpObjects as $myTmpObjectKey => $myTmpObjectArray) {
            if ($myTmpObjectKey == 'VirtualStock') continue;
            if ($myTmpObjectArray['includerefgeneration']) {
                $src = DOL_DOCUMENT_ROOT . '/install/doctemplates/virtualwarehouse/template_virtualstocks.odt';
                $dirodt = DOL_DATA_ROOT . '/doctemplates/virtualwarehouse';
                $dest = $dirodt . '/template_virtualstocks.odt';

                if (file_exists($src) && !file_exists($dest)) {
                    require_once DOL_DOCUMENT_ROOT . '/core/lib/files.lib.php';
                    dol_mkdir($dirodt);
                    $result = dol_copy($src, $dest, 0, 0);
                    if ($result < 0) {
                        $langs->load("errors");
                        $this->error = $langs->trans('ErrorFailToCopyFile', $src, $dest);
                        return 0;
                    }
                }

                $sql = array_merge($sql, array(
                    "DELETE FROM " . MAIN_DB_PREFIX . "document_model WHERE nom = 'standard_" . strtolower($myTmpObjectKey) . "' AND type = '" . strtolower($myTmpObjectKey) . "' AND entity = " . $conf->entity,
                    "INSERT INTO " . MAIN_DB_PREFIX . "document_model (nom, type, entity) VALUES('standard_" . strtolower($myTmpObjectKey) . "','" . strtolower($myTmpObjectKey) . "'," . $conf->entity . ")",
                    "DELETE FROM " . MAIN_DB_PREFIX . "document_model WHERE nom = 'generic_" . strtolower($myTmpObjectKey) . "_odt' AND type = '" . strtolower($myTmpObjectKey) . "' AND entity = " . $conf->entity,
                    "INSERT INTO " . MAIN_DB_PREFIX . "document_model (nom, type, entity) VALUES('generic_" . strtolower($myTmpObjectKey) . "_odt', '" . strtolower($myTmpObjectKey) . "', " . $conf->entity . ")"
                ));
            }
        }

        return $this->_init($sql, $options);
    }

    /**
     *  Function called when module is disabled.
     *  Remove from database constants, boxes and permissions from Dolibarr database.
     *  Data directories are not deleted
     *
     *  @param      string	$options    Options when enabling module ('', 'noboxes')
     *  @return     int                 1 if OK, 0 if KO
     */
    public function remove($options = '')
    {
        $sql = array();
        return $this->_remove($sql, $options);
    }
}
