# VIRTUALWAREHOUSE FOR [DOLIBARR ERP CRM](https://www.dolibarr.org)

## Features

This module helps you manage your stock to show REAL quantity, when you have items in your Sales representatives.

When a record is created, the QTY set to the Sales representative is removed from the stock in Dolibarr, thus showing in product card the real stock you have in your warehouse.

main page of the module helps you track products and their Qty you have in your sales representatives.

![Screenshot virtualwarehouse](img/image_1.png "VirtualWarehouse")
![Screenshot virtualwarehouse](img/image_2.png "VirtualWarehouse")
![Screenshot virtualwarehouse](img/image_3.png "VirtualWarehouse")

Other external modules are available on [Dolistore.com](https://www.dolistore.com).

## USAGE

1. Limit users in module list

As you may have a lot of internal users, to reduce names in list table it is advisable to move all you sales representatives in a separate group. Then, in module settings enter this group ID.
This will filter names in both list table and add record dropdown. 

## WORKFLOW

1. Create record -> QTY is removed from Dolibarr stock
2. Update record -> if you decrease the QTY (i.e. products are returned from the Sales reperesentative or you have made a mistake) -> Dolibarr stock is increased with the difference
                 -> if you increase the QTY (i.e. more products are given to the Sales reperesentative or you have made a mistake) -> Dolibarr stock is decreased with the difference
3. Delete record -> QTY is returned and Dolibarr stock is increased


## Translations

Translations can be completed manually by editing files into directories *langs*.


## Installation

### From the ZIP file and GUI interface

- If you get the module in a zip file (like when downloading it from the market place [Dolistore](https://www.dolistore.com)), go into
menu ```Home - Setup - Modules - Deploy external module``` and upload the zip file.

Note: If this screen tell you there is no custom directory, check your setup is correct:

- In your Dolibarr installation directory, edit the ```htdocs/conf/conf.php``` file and check that following lines are not commented:

    ```php
    //$dolibarr_main_url_root_alt ...
    //$dolibarr_main_document_root_alt ...
    ```

- Uncomment them if necessary (delete the leading ```//```) and assign a sensible value according to your Dolibarr installation

    For example :

    - UNIX:
        ```php
        $dolibarr_main_url_root_alt = '/custom';
        $dolibarr_main_document_root_alt = '/var/www/Dolibarr/htdocs/custom';
        ```

    - Windows:
        ```php
        $dolibarr_main_url_root_alt = '/custom';
        $dolibarr_main_document_root_alt = 'C:/My Web Sites/Dolibarr/htdocs/custom';
        ```

### From a GIT repository

- Clone the repository in ```$dolibarr_main_document_root_alt/virtualwarehouse```

```sh
cd ....../custom
git clone git@github.com:gitlogin/virtualwarehouse.git virtualwarehouse
```

### <a name="final_steps"></a>Final steps

From your browser:

  - Log into Dolibarr as a super-administrator
  - Go to "Setup" -> "Modules"
  - You should now be able to find and enable the module

## Licenses

### Main code

GPLv3 or (at your option) any later version. See file COPYING for more information.

### Documentation

All texts and readmes are licensed under GFDL.
