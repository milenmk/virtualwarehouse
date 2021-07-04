<?php

/**
 * This Dolibarr plugin helps you track your product stock when you have employeed carring products with them
 *
 * @date           File created on Mon Jun 14 2021 11:26:43
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


/* Copyright (C) 2001-2005 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2004-2015 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2005-2012 Regis Houssin        <regis.houssin@inodbox.com>
 * Copyright (C) 2015      Jean-François Ferry	<jfefe@aternatik.fr>
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

// Load Dolibarr environment
$res = 0;
// Try main.inc.php into web root known defined into CONTEXT_DOCUMENT_ROOT (not always defined)
if (!$res && !empty($_SERVER["CONTEXT_DOCUMENT_ROOT"])) $res = @include $_SERVER["CONTEXT_DOCUMENT_ROOT"] . "/main.inc.php";
// Try main.inc.php into web root detected using web root calculated from SCRIPT_FILENAME
$tmp = empty($_SERVER['SCRIPT_FILENAME']) ? '' : $_SERVER['SCRIPT_FILENAME'];
$tmp2 = realpath(__FILE__);
$i = strlen($tmp) - 1;
$j = strlen($tmp2) - 1;
while ($i > 0 && $j > 0 && isset($tmp[$i]) && isset($tmp2[$j]) && $tmp[$i] == $tmp2[$j]) {
    $i--;
    $j--;
}
if (!$res && $i > 0 && file_exists(substr($tmp, 0, ($i + 1)) . "/main.inc.php")) $res = @include substr($tmp, 0, ($i + 1)) . "/main.inc.php";
if (!$res && $i > 0 && file_exists(dirname(substr($tmp, 0, ($i + 1))) . "/main.inc.php")) $res = @include dirname(substr($tmp, 0, ($i + 1))) . "/main.inc.php";
// Try main.inc.php using relative path
if (!$res && file_exists("../main.inc.php")) $res = @include "../main.inc.php";
if (!$res && file_exists("../../main.inc.php")) $res = @include "../../main.inc.php";
if (!$res && file_exists("../../../main.inc.php")) $res = @include "../../../main.inc.php";
if (!$res) die("Include of main fails");

require_once DOL_DOCUMENT_ROOT . '/core/class/html.formfile.class.php';
require_once DOL_DOCUMENT_ROOT . '/core/class/html.formfile.class.php';
require_once "./class/class.virtualstock.php";
require './vendor/autoload.php';

// Load translation files required by the page
$langs->loadLangs(array("virtualwarehouse@virtualwarehouse"));

$action = GETPOST('action', 'aZ09');


// Security check
//if (! $user->rights->virtualwarehouse->myobject->read) accessforbidden();
$socid = GETPOST('socid', 'int');
if (isset($user->socid) && $user->socid > 0) {
    $action = '';
    $socid = $user->socid;
}

$max = 5;
$now = dol_now();


/*
 * Actions
 */

// None

//Define module parameters
$SalesRepUserGroup = $conf->global->SalesRepUserGroup == '' ? null : $conf->global->SalesRepUserGroup;

/*
 * View
 */

$form = new Form($db);
$formfile = new FormFile($db);
$object = new VirtualStock($db);

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

$loader = new FilesystemLoader('./tpl');
$twig = new Environment($loader, [
    'debug' => true,
]);
$twig->addExtension(new \Twig\Extension\DebugExtension());

llxHeader("", $langs->trans("UpdateStockToSalesRepresentative"));

print load_fiche_titre($langs->trans("UpdateStockToSalesRepresentative"), '', 'virtualwarehouse.png@virtualwarehouse');

$id = GETPOST('recordId', 'int');
$user = GETPOST('fk_user', 'int');
$product = GETPOST('fk_product', 'int');
$qty = GETPOST('qty', 'int');

$username = $object->getUsername($user);
$productname = $object->getProductName($product);

echo $twig->render('form.editrecord.html.twig', ['obj' => $object, 'userid' => $user, 'productid' => $product, 'recordid' => $id, 'lang' => $langs, 'user' => $username, 'product' => $productname, 'qty' => $qty]);

// End of page
llxFooter();
$db->close();
