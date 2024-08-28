CREATE TABLE tx_easyshop_domain_model_product (
	name VARCHAR(255) DEFAULT '' NOT NULL,
	net_price decimal(6,2) DEFAULT '0.00' NOT NULL,
	gross_price decimal(6,2) DEFAULT '0.00' NOT NULL,
	vat decimal(4,2) DEFAULT '19.00' NOT NULL,
	currency char(3) DEFAULT 'EUR' NOT NULL,
	description TEXT,
	details TEXT,
	categories int(11) DEFAULT '0' NOT NULL,
	images int(11) unsigned DEFAULT '0',
);
