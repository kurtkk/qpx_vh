#
# Additional field "header-position" in table 'tt_content'
#
CREATE TABLE tt_content (
	header_position varchar(255) DEFAULT '' NOT NULL,
   header_style varchar(255) DEFAULT '' NOT NULL,

   spaceBefore smallint(5) unsigned NOT NULL default '0',
   spaceAfter smallint(5) unsigned NOT NULL default '0',
);
