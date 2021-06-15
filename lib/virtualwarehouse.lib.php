<?php

/**
 * This Dolibarr plugin helps you track your product stock when you have employeed carring products with them
 *
 * @date           File created on Tue Jun 15 2021 17:26:48
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

/* Copyright (C) 2021 SuperAdmin
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */


/**
 * Prepare admin pages header
 *
 * @return array
 */
function virtualwarehouseAdminPrepareHead()
{
    global $langs, $conf;

    $langs->load("virtualwarehouse@virtualwarehouse");

    $h = 0;
    $head = array();

    $head[$h][0] = dol_buildpath("/virtualwarehouse/admin/setup.php", 1);
    $head[$h][1] = $langs->trans("Settings");
    $head[$h][2] = 'settings';
    $h++;

    /*
    $head[$h][0] = dol_buildpath("/virtualwarehouse/admin/myobject_extrafields.php", 1);
    $head[$h][1] = $langs->trans("ExtraFields");
    $head[$h][2] = 'myobject_extrafields';
    $h++;
    */

    $head[$h][0] = dol_buildpath("/virtualwarehouse/admin/about.php", 1);
    $head[$h][1] = $langs->trans("About");
    $head[$h][2] = 'about';
    $h++;

    // Show more tabs from modules
    // Entries must be declared in modules descriptor with line
    //$this->tabs = array(
    //	'entity:+tabname:Title:@virtualwarehouse:/virtualwarehouse/mypage.php?id=__ID__'
    //); // to add new tab
    //$this->tabs = array(
    //	'entity:-tabname:Title:@virtualwarehouse:/virtualwarehouse/mypage.php?id=__ID__'
    //); // to remove a tab
    complete_head_from_modules($conf, $langs, null, $head, $h, 'virtualwarehouse');

    return $head;
}
