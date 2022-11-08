DROP TABLE IF EXISTS `nx_base`;
# END
CREATE TABLE `nx_base`(
	`id` varchar(20) NOT NULL DEFAULT 'nxvim',
	`title` varchar(50) NOT NULL,
	`status` varchar(8) NOT NULL,
	`theme` varchar(100) NOT NULL,
	`docs` varchar(100) NOT NULL
);
# END
DROP TABLE IF EXISTS `nx_users`; 
# END
CREATE TABLE `nx_users`(
	`id` int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
	`name` varchar(100) NOT NULL,
	`email` varchar(100) NOT NULL,
	`password` varchar(64) NOT NULL,
	`status` varchar(20) NOT NULL,
	`role` varchar(20) NOT NULL,
	`date` int(25) NOT NULL,
	`key` varchar(16) NOT NULL,
	`rec` varchar(32) NOT NULL,
	`2fa_status` varchar(8) NOT NULL DEFAULT 'inactive',
	`2fa_key` varchar(16) NOT NULL
);
# END
DROP TABLE IF EXISTS `nx_captcha`;
# END
CREATE TABLE `nx_captcha`(
	`id` varchar(20) NOT NULL DEFAULT 'captcha',
	`type` varchar(20) NOT NULL,
	`site_key` varchar(100) NOT NULL,
	`secret_key` varchar(100) NOT NULL,
	`status` varchar(8) NOT NULL
);
# END
DROP TABLE IF EXISTS `nx_smtp`;
# END
CREATE TABLE `nx_smtp` (
	`id` varchar(20) NOT NULL DEFAULT 'smtp',
	`hostname` varchar(100) NOT NULL,
	`username` varchar(100) NOT NULL,
	`password` varchar(100) NOT NULL,
	`port` int(8) NOT NULL,
	`from` varchar(100) NOT NULL,
	`status` varchar(8) NOT NULL
);
# END
DROP TABLE IF EXISTS `nx_emails`;
# END
CREATE TABLE `nx_emails` (
	`id` varchar(40) NOT NULL,
	`subject` varchar(500) NOT NULL,
	`content` varchar(5000) NOT NULL,
	`docs` varchar(500) NOT NULL
);
# END
DROP TABLE IF EXISTS `nx_ticket`;
# END
CREATE TABLE `nx_ticket` (
	`id` int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
	`subject` varchar(500) NOT NULL,
	`content` varchar(5000) NOT NULL,
	`status` varchar(20) NOT NULL,
	`date` varchar(20) NOT NULL,
	`for` varchar(16) NOT NULL,
	`key` varchar(16) NOT NULL
);
# END
DROP TABLE IF EXISTS `nx_reply`;
# END
CREATE TABLE `nx_reply` (
	`id` int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
	`content` varchar(5000) NOT NULL,
	`date` varchar(20) NOT NULL,
	`for` varchar(16) NOT NULL,
	`key` varchar(16) NOT NULL
);
# END
DROP TABLE IF EXISTS `nx_ssl`;
# END
CREATE TABLE `nx_ssl` (
	`id` int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
	`pid` varchar(20) NOT NULL DEFAULT '0',
	`provider` varchar(20) NOT NULL,
	`domain` varchar(100) NOT NULL,
	`key` varchar(16) NOT NULL,
	`for` varchar(16) NOT NULL
);
# END
DROP TABLE IF EXISTS `nx_gogetssl`;
# END
CREATE TABLE `nx_gogetssl` (
	`id` varchar(20) NOT NULL DEFAULT 'gogetssl',
	`username` varchar(100) NOT NULL,
	`password` varchar(100) NOT NULL,
	`status` varchar(8) NOT NULL
);
# END
DROP TABLE IF EXISTS `nx_mofh`;
# END
CREATE TABLE `nx_mofh` (
	`id` varchar(20) NOT NULL DEFAULT 'mofh',
	`username` varchar(256) NOT NULL,
	`password` varchar(256) NOT NULL,
	`cpanel_url` varchar(100) NOT NULL,
	`ns_1` varchar(100) NOT NULL,
	`ns_2` varchar(100) NOT NULL,
	`plan` varchar(50) NOT NULL
);
# END
DROP TABLE IF EXISTS `nx_mofh_ext`;
# END
CREATE TABLE `nx_mofh_ext` (
	`id` int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
	`domain` varchar(100) NOT NULL
);
# END
DROP TABLE IF EXISTS `nx_hosting`;
# END
CREATE TABLE `nx_hosting` (
	`id` int(11) PRIMARY KEY AUTO_INCREMENT NOT NULL,
	`username` varchar(100) NOT NULL,
	`password` varchar(100) NOT NULL,
	`domain` varchar(100) NOT NULL,
	`main` varchar(100) NOT NULL,
	`status` varchar(100) NOT NULL,
	`sql` varchar(100) NOT NULL DEFAULT 'sqlxxx',
	`key` varchar(8) NOT NULL,
	`for` varchar(16) NOT NULL,
	`time` int(20) NOT NULL,
	`label` varchar(150) NOT NULL
);
# END
DROP TABLE IF EXISTS `nx_sitepro`;
# END
CREATE TABLE `nx_sitepro` (
	`id` varchar(20) NOT NULL DEFAULT 'sitepro',
	`username` varchar(100) NOT NULL,
	`password` varchar(100) NOT NULL,
	`status` varchar(8) NOT NULL
);
# END
INSERT INTO `nx_base` (`title`, `status`, `theme`, `docs`) VALUES ('Web Host', 'active', 'default', 'https://forum.mofh-r.eu.org');
# END
INSERT INTO `nx_captcha` (`type`, `site_key`, `secret_key`, `status`) VALUES ('google', 'site key', 'secret key', 'inactive');
# END
INSERT INTO `nx_smtp` (`hostname`, `username`, `password`, `port`, `from`, `status`) VALUES ('smtp.example.com', 'username', 'password', 587, 'jhon@example.com', 'inactive');
# END
INSERT INTO `nx_emails` (`id`, `subject`, `content`, `docs`) VALUES ('new_user', 'Action Required', '{site_name}, {site_url}, {user_name}, {user_email}, {activation_url}', '{site_name}, {site_url}, {user_name}, {user_email}, {activation_url}'), ('forget_password', 'Forget Password', '{user_name} {user_email} {reset_url} {site_name} {site_url}', '{user_name} {user_email} {reset_url} {site_name} {site_url}'), ('new_ticket', 'Ticket Created', '{site_name}, {site_url}, {ticket_url}, {ticket_id}, {user_name}', '{site_name}, {site_url}, {ticket_url}, {ticket_id}, {user_name}'), ('reply_ticket', 'Ticket Reply Received', '{site_name}, {site_url}, {ticket_url}, {ticket_id}, {user_name}', '{site_name}, {site_url}, {ticket_url}, {ticket_id}, {user_name}'), ('account_created', 'Account Created', '{site_name}, {site_url}, {account_username}, {account_password}, {account_domain}, {main_domain}, {cpanel_domain}, {sql_server}, {nameserver_1}, {nameserver_2}, {ftp_server}, {account_label}, {user_name}, {user_email}','{site_name}, {site_url}, {account_username}, {account_password}, {account_domain}, {main_domain}, {cpanel_domain}, {sql_server}, {nameserver_1}, {nameserver_2}, {account_label}, {ftp_server}, {user_name}, {user_email}'), ('account_suspended', 'Account Suspended', '{site_name}, {site_url}, {account_username}, {user_name}, {user_email}, {some_reason}', '{site_name}, {site_url}, {account_username}, {user_name}, {user_email}, {some_reason}'), ('account_reactivated', 'Account Reactivated', '{site_name}, {site_url}, {account_username}, {user_name}, {user_email}', '{site_name}, {site_url}, {account_username}, {user_name}, {user_email}'), ('account_deleted', 'Account Deleted', '{site_name}, {site_url}, {account_username}, {user_name}, {user_email}', '{site_name}, {site_url}, {account_username}, {user_name}, {user_email}'), ('new_ssl', 'SSL Requested', '{site_name}, {site_url}, {ssl_url}, {user_name}, {ssl_id}', '{site_name}, {site_url}, {ssl_url}, {user_name}, {ssl_id}');
# END
INSERT INTO `nx_gogetssl` (`username`, `password`, `status`) VALUES ('username', 'password', 'inactive');
# END
INSERT INTO `nx_mofh` (`username`, `password`, `cpanel_url`, `ns_1`, `ns_2`, `plan`) VALUES ('username', 'password', 'https://cpanel.byethost.com', 'ns1.byethost.com', 'ns2.byethost.com', 'free');
# END
INSERT INTO `nx_mofh_ext` (`domain`) VALUES ('.byethost.com');
# END
INSERT INTO `nx_sitepro` (`username`, `password`, `status`) VALUES ('username', 'password', 'inactive');