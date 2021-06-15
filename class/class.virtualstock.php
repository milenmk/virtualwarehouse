<?php

/**
 * This Dolibarr plugin helps you track your product stock when you have employeed carring products with them
 *
 * @date           File created on Mon Jun 14 2021 11:21:37
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

require_once DOL_DOCUMENT_ROOT . '/core/class/commonobject.class.php';

class VirtualStock extends CommonObject
{
    public $db;
    public $userGroup = null;

    /**
     * Constructor
     *
     * @param DoliDB $db Database handler
     */
    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * Fetch from llx_user all internal (employee) users id, firstname and lastname
     *
     * @param int $userGroup is the ID of the Sales Representatives User Group
     * @return object affected selected row
     */
    public function getSalesRepresentatives($userGroup = null)
    {
        $sql  = 'SELECT ';
        $sql .= 'u.rowid, u.firstname, u.lastname ';
        $sql .= 'FROM ';
        $sql .= '' . MAIN_DB_PREFIX . 'user u Inner Join ';
        $sql .= '' . MAIN_DB_PREFIX . 'usergroup_user uu on u.rowid = uu.fk_user ';
        $sql .= 'WHERE ';
        $sql .= 'u.employee = 1 ';
        if (!empty($userGroup)) {
            $sql .= 'AND uu.fk_usergroup = ' . $this->db->escape($userGroup) . ' ';
        }
        $resql = $this->db->query($sql);
        $liste = array();
        if ($resql) {
            $i = 0;
            while ($obj = $this->db->fetch_object($resql)) {
                $liste[$i]['rowid'] = $obj->rowid;
                $liste[$i]['firstname'] = $obj->firstname;
                $liste[$i]['lastname'] = $obj->lastname;
                $i++;
            }
            $this->db->free($resql);
        } else {
            dol_print_error($this->db);
            return null;
        }
        return $liste;
    }

    /**
     * Fetch from llx_user all products rowis and name
     *
     * @return void
     */
    public function getProductsList()
    {
        $sql  = 'SELECT rowid, label ';
        $sql .= 'FROM ';
        $sql .= '' . MAIN_DB_PREFIX . 'product';
        $resql = $this->db->query($sql);
        $resql = $this->db->query($sql);
        $liste = array();
        if ($resql) {
            $i = 0;
            while ($obj = $this->db->fetch_object($resql)) {
                $liste[$i]['rowid'] = $obj->rowid;
                $liste[$i]['label'] = $obj->label;
                $i++;
            }
            $this->db->free($resql);
        } else {
            dol_print_error($this->db);
            return null;
        }
        return $liste;
    }

    /**
     * Insert record into llx_virtualstock_module
     *
     * @param integer $user
     * @param integer $product
     * @param integer $qty
     * @return void
     */
    public function insertVirtualStock(int $user, int $product, int $qty)
    {
        global $langs;

        $sql  = 'INSERT ';
        $sql .= 'INTO ' . MAIN_DB_PREFIX . 'virtualstock_module (fk_user, fk_product, qty) ';
        $sql .= 'VALUES ("' . $this->db->escape($user) . '", "' . $this->db->escape($product) . '", "' . $this->db->escape($qty) . '")';

        $resql = $this->db->query($sql);
        if ($resql) {
            $this->db->commit();
            $this->db->free($resql);
            return print '<br><strong style="color: red;">' . $langs->trans('RecordCreated') . '!</strong><br><br><meta http-equiv="refresh" content="1;url=agenda.php">';
        } else {
            $this->db->rollback();
            dol_print_error($this->db);
            return -1;
        }
    }

    /**
     * Fetch all rcords from llx_virtualstock_module
     *
     * @return void
     */
    public function getVirtualStock()
    {
        $sql  = 'Select ';
        $sql .= 'vsm.rowid, ';
        $sql .= 'vsm.date_added, ';
        $sql .= 'vsm.fk_user, ';
        $sql .= 'vsm.fk_product, ';
        $sql .= 'u.firstname, ';
        $sql .= 'u.lastname, ';
        $sql .= 'p.label, ';
        $sql .= 'vsm.qty ';
        $sql .= 'From ';
        $sql .= '' . MAIN_DB_PREFIX . 'virtualstock_module vsm Inner Join ';
        $sql .= '' . MAIN_DB_PREFIX . 'user u On vsm.fk_user = u.rowid Inner Join ';
        $sql .= '' . MAIN_DB_PREFIX . 'product p On vsm.fk_product = p.rowid ';
        $sql .= 'ORDER BY vsm.rowid DESC';
        $resql = $this->db->query($sql);
        $liste = array();
        if ($resql) {
            $i = 0;
            while ($obj = $this->db->fetch_object($resql)) {
                $liste[$i]['rowid'] = $obj->rowid;
                $liste[$i]['date_added'] = $obj->date_added;
                $liste[$i]['fk_user'] = $obj->fk_user;
                $liste[$i]['fk_product'] = $obj->fk_product;
                $liste[$i]['firstname'] = $obj->firstname;
                $liste[$i]['lastname'] = $obj->lastname;
                $liste[$i]['product'] = $obj->label;
                $liste[$i]['qty'] = $obj->qty;
                $i++;
            }
            $this->db->free($resql);
        } else {
            dol_print_error($this->db);
            return null;
        }
        return $liste;
    }

    /**
     * Update record from llx_virtualstock_module
     *
     * @param integer $user
     * @param integer $product
     * @param integer $qty
     * @param integer $rowid
     * @return void
     */
    public function updateVirtualStock(int $user, int $product, int $qty, int $rowid)
    {
        global $langs;

        $sql  = 'UPDATE ' . MAIN_DB_PREFIX . 'virtualstock_module ';
        $sql .= 'SET ';
        $sql .= 'fk_user =' . $this->db->escape($user) . ', ';
        $sql .= 'fk_product =' . $this->db->escape($product) . ', ';
        $sql .= 'qty =' . $this->db->escape($qty) . ' ';
        $sql .= 'WHERE ';
        $sql .= 'rowid = ' . $this->db->escape($rowid) . '';
        $resql = $this->db->query($sql);
        if ($resql) {
            $this->db->commit();
            $this->db->free($resql);
            return print '<br><strong style="color: red;">' . $langs->trans('RecordUpdated') . '!</strong><br><br><meta http-equiv="refresh" content="1;url=agenda.php">';
        } else {
            $this->db->rollback();
            dol_print_error($this->db);
            return -1;
        }
    }

    /**
     * Delete record from llx_virtualstock_module
     *
     * @param integer $rowid
     * @return void
     */
    public function deleteVirtualStock(int $rowid)
    {
        global $langs;

        $sql  = 'DELETE ';
        $sql .= 'FROM ';
        $sql .= '' . MAIN_DB_PREFIX . 'virtualstock_module ';
        $sql .= 'WHERE ';
        $sql .= 'rowid = ' . $this->db->escape($rowid) . '';
        $resql = $this->db->query($sql);
        if ($resql) {
            $this->db->commit();
            $this->db->free($resql);
            return print '<br><strong style="color: red;">' . $langs->trans('RecordDeleted') . '!</strong><br><br><meta http-equiv="refresh" content="0">';
        } else {
            $this->db->rollback();
            dol_print_error($this->db);
            return -1;
        }
    }

    /**
     * Fetch username from llx_user
     *
     * @param int $rowid
     * @return int
     */
    public function getUsername(int $rowid): array
    {
        $sql  = 'SELECT firstname, lastname ';
        $sql .= 'FROM ';
        $sql .= '' . MAIN_DB_PREFIX . 'user ';
        $sql .= 'WHERE ';
        $sql .= 'rowid = ' . $this->db->escape($rowid) . '';

        $resql = $this->db->query($sql);
        if ($resql) {
            if ($this->db->num_rows($resql) > 0) {
                $res = $this->db->fetch_array($resql);
                $this->firstname = $res['firstname'];
                $this->firstname = $res['lastname'];
                $this->db->free($resql);
                return $res;
            } else {
                $this->db->free($resql);
                return -1;
            }
        } else {
            dol_print_error($this->db);
            return -1;
        }
    }

    /**
     * Fetch product name from llx_user
     *
     * @param string $rowid
     * @return void
     */
    public function getProductName(int $rowid)
    {
        $sql  = 'SELECT label ';
        $sql .= 'FROM ';
        $sql .= '' . MAIN_DB_PREFIX . 'product ';
        $sql .= 'WHERE ';
        $sql .= 'rowid = ' . $this->db->escape($rowid) . '';
        $resql = $this->db->query($sql);
        if ($resql) {
            if ($this->db->num_rows($resql) > 0) {
                $res = $this->db->fetch_array($resql);
                $this->label = $res['label'];
                $this->db->free($resql);
                return $res;
            } else {
                $this->db->free($resql);
                return -1;
            }
        } else {
            dol_print_error($this->db);
            return -1;
        }
    }

    /**
     * Fetch qty on stok for each Sales Representatives from llx_virtualstock_module
     *
     * @param integer $user
     * @param integer $product
     * @return void
     */
    public function getVirtualStockQty(int $user, int $product)
    {
        $sql  = 'SELECT ';
        $sql .= 'COALESCE(Sum(qty), 0) As qty_sum ';
        $sql .= 'FROM ';
        $sql .= '' . MAIN_DB_PREFIX . 'virtualstock_module ';
        $sql .= 'WHERE ';
        $sql .= 'fk_product = ' . $this->db->escape($product) . ' AND ';
        $sql .= 'fk_user = ' . $this->db->escape($user) . ' ';
        $sql .= 'GROUP BY fk_user';
        $resql = $this->db->query($sql);
        if ($resql) {
            if ($this->db->num_rows($resql) > 0) {
                $res = $this->db->fetch_array($resql);
                $this->db->free($resql);
                return $res['qty_sum'];
            } else {
                return 0;
            }
        } else {
            dol_print_error($this->db);
            return -1;
        }
    }
}
