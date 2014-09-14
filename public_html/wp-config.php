<?php
/** Enable W3 Total Cache Edge Mode */
define('W3TC_EDGE_MODE', true); // Added by W3 Total Cache

/** Enable W3 Total Cache */
define('WP_CACHE', true); // Added by W3 Total Cache

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
define('DB_NAME', 'bingopaws');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', ':H7/Ca44dt');

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
define('AUTH_KEY',         'qfyGQZD[D1Iw_+|*+tTwD[o?@.g_ah+t/3nP lZvpu=0#cg;%t4@+4+}JCg[wmGg');
define('SECURE_AUTH_KEY',  '_Ex3_CV40~Z{^4_8Sl|qa/{c*Z@Jl5e%+eY]2q^!4zp0=xr(#:Q0e)?9GPu[W(`(');
define('LOGGED_IN_KEY',    'nEa6jH_~i&^00|}-+|~,8gcEugw0s8_+1bmw$6/S~8]$_${&u44kYmQ#TK`qG/K-');
define('NONCE_KEY',        'oUPFV>^a3)}-%LzF>-@7bWY`u^MZQGfEc2.OQf,XkO9qjZ1H+{nph<S1k)x{|Atx');
define('AUTH_SALT',        'B*]~-[a%K4t-!l?#<jvw`k9|M5p7UF? u,}wiES~u@uXh~!V!]2nG:Geh!0T`t6=');
define('SECURE_AUTH_SALT', '=||+DWf%g{!~.,z?[qzN(TWs:go9pspig3= gpSN_l9u[s]/EN0k}[1#w2^q[3qx');
define('LOGGED_IN_SALT',   '7>W10-(OI^3`pYeI2eW|r-F+TbDS .9~;{otI>0._YKat3Pbq+`$JTc(sK!-$kh4');
define('NONCE_SALT',       '}Wj% ^>-[]j?mgPR,>F/P,_VQm,  *^Hpmf6huA,sa*rlTU6?2*Y^YLg)|n&<,yw');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
