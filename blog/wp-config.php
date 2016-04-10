<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'soonspoon_blog');

/** MySQL database username */
define('DB_USER', 'soonspoon');

/** MySQL database password */
define('DB_PASSWORD', 'soonspoon');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'iynm4L:r6RLB]nG|24?|xuo ZFFDT^=#^] qbP<)Aa_2TV5:gE-~D+Crnz8)1(4f');
define('SECURE_AUTH_KEY',  '9q;@qAQDD{+j>9`0zgDv}|_Y267=xKyuhe0lJ!Rawk0g~V)%P(=1In]-}WtHw4}9');
define('LOGGED_IN_KEY',    '_SXX4R4BqwT0c;56Az V<2>G@[w0h0,_/;oAXr7x*|Dy<1&^0|rm=LH!9<X37e1F');
define('NONCE_KEY',        '`$3xsHNGC`5%63Ds8(v,v5`<Z!1cx}7sjZ,hQ.`O*<;4LtNF%|#@gS-C8wV<u,K]');
define('AUTH_SALT',        'Jl0E$Fs$4=-UTathC)|-7,CL}oG+dE|7 jbB:m+~Cpp4r@j?&xaa_O|b]<Z2U,A(');
define('SECURE_AUTH_SALT', 'Nd&e^B-PKMpZH:LDk1Lk::K&vY>a-^h?N>;<J=}mP7NQ{XNn)>A$@DY;cla+7fIo');
define('LOGGED_IN_SALT',   'xy.AR.Y<iVKyfA<*b&CSb;[ow1l>~oROIi.yq-WT-BVU}pJ|tv}wmdVds3~_]2cM');
define('NONCE_SALT',       'WQKp`:TiI+9-{mD,[)7-E|Ij5)&d*]#fLpNB0&TC-1>+ {Y}_-[R5V51.hmhG$%~');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
