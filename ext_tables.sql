#
# Additional fields for table 'tt_content'
#
CREATE TABLE tt_content (
	# IRRE relation
	tx_displaycontrolleradvanced_providergroup int(11) DEFAULT '0' NOT NULL,
	tx_displaycontroller_consumer int(11) DEFAULT '0' NOT NULL
);

#
# Relation table between controller and all components
#
CREATE TABLE tx_displaycontrolleradvanced_components_mm (
	uid_local int(11) DEFAULT '0' NOT NULL,
	uid_foreign int(11) DEFAULT '0' NOT NULL,
	tablenames varchar(100) DEFAULT '' NOT NULL,
	sorting int(11) DEFAULT '0' NOT NULL,
	component varchar(100) DEFAULT '' NOT NULL,
	rank tinyint(4) DEFAULT '1' NOT NULL,
	local_table varchar(255) DEFAULT '' NOT NULL,
#	local_field varchar(255) DEFAULT '' NOT NULL,
	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);

#
# Store providers in relation Relation table between controller and all components
#
CREATE TABLE tx_displaycontrolleradvanced_providergroup (

	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	t3ver_oid int(11) DEFAULT '0' NOT NULL,
	t3ver_id int(11) DEFAULT '0' NOT NULL,
	t3ver_wsid int(11) DEFAULT '0' NOT NULL,
	t3ver_label varchar(30) DEFAULT '' NOT NULL,
	t3ver_state tinyint(4) DEFAULT '0' NOT NULL,
	t3ver_stage tinyint(4) DEFAULT '0' NOT NULL,
	t3ver_count int(11) DEFAULT '0' NOT NULL,
	t3ver_tstamp int(11) DEFAULT '0' NOT NULL,
	t3_origuid int(11) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	title varchar(255) DEFAULT '' NOT NULL,

	# parent id
	content int(11) DEFAULT '0' NOT NULL,

	tx_displaycontroller_provider int(11) DEFAULT '0' NOT NULL,
	tx_displaycontroller_filtertype varchar(6) DEFAULT '' NOT NULL,
	tx_displaycontroller_datafilter int(11) DEFAULT '0' NOT NULL,
	tx_displaycontroller_emptyfilter varchar(3) DEFAULT '' NOT NULL,
	tx_displaycontroller_provider2 int(11) DEFAULT '0' NOT NULL,
	tx_displaycontroller_emptyprovider2 varchar(3) DEFAULT '' NOT NULL,
	tx_displaycontroller_datafilter2 int(11) DEFAULT '0' NOT NULL,
	tx_displaycontroller_emptyfilter2 varchar(3) DEFAULT '' NOT NULL,
	PRIMARY KEY (uid),
	KEY parent (pid),
	KEY foreign_key_content (content)
);

