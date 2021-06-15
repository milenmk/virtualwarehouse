CREATE TABLE IF NOT EXISTS llx_virtualstock_module (
    rowid integer AUTO_INCREMENT PRIMARY KEY,
    date_added timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    fk_product INT,
    qty INT,
    fk_user INT
) ENGINE=InnoDB;
ALTER TABLE llx_virtualstock_module ADD CONSTRAINT vsuser_received FOREIGN KEY (fk_user) REFERENCES llx_user (rowid) ON DELETE CASCADE ON UPDATE RESTRICT;
ALTER TABLE llx_virtualstock_module ADD CONSTRAINT vsproduct_received FOREIGN KEY (fk_product) REFERENCES llx_product (rowid) ON DELETE CASCADE ON UPDATE RESTRICT;
CREATE TRIGGER `virtualstock_decrease_qty` BEFORE INSERT ON `llx_virtualstock_module` FOR EACH ROW UPDATE llx_product_stock SET llx_product_stock.reel=llx_product_stock.reel-NEW.qty WHERE llx_product_stock.fk_product=NEW.fk_product;
CREATE TRIGGER `virtualstock_update` BEFORE UPDATE ON `llx_virtualstock_module` FOR EACH ROW UPDATE llx_product_stock SET llx_product_stock.reel=llx_product_stock.reel+OLD.qty-NEW.qty WHERE llx_product_stock.fk_product=OLD.fk_product;
CREATE TRIGGER `virtualstock_increase` BEFORE DELETE ON `llx_virtualstock_module` FOR EACH ROW UPDATE llx_product_stock SET llx_product_stock.reel=llx_product_stock.reel+OLD.qty WHERE llx_product_stock.fk_product=OLD.fk_product;
