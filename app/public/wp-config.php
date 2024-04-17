<?php

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */
define('DISALLOW_FILE_EDIT', false);

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'local');

/** Database username */
define('DB_USER', 'root');

/** Database password */
define('DB_PASSWORD', 'root');

/** Database hostname */
define('DB_HOST', 'localhost');

/** Database charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The database collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',          '_9]0QXs0]>S 5mmu7W-un$);UKYz4&s,l%!aY@|P-w):>_F&|vWYq!bR2Ec&W/m[');
define('SECURE_AUTH_KEY',   'xf[{_Jh(3AYt|!+]x9X 9`RZ3+/ Xb>!W3)/5T /5c<9WQv?+a&xT`)od6m~BJ*^');
define('LOGGED_IN_KEY',     'cJhcL/Hln56P@)[jL[Nw&,+T;fHqj;GyT~OK*y?EUUJ-mXpm>ww!bfsF^(YSj6b1');
define('NONCE_KEY',         '_9l0U[ierlcdZp!9JffE9$pi,^0A|Nx#,Ex%wWR8);nz,OR)ckZ7m:xNY 96&/O}');
define('AUTH_SALT',         'Ji*%VS}Q;%,hWfPW!&In ;=9R<wO`dN?t0Rw:RgYea+2gM&=4*fQ f?!HTwiv/6%');
define('SECURE_AUTH_SALT',  ';|~0uoyc,`sqRD3o^GNk~U5qtUthy9Qo.kj{o]X-P,*aZFkxp]_l)l*6i/)ztgtB');
define('LOGGED_IN_SALT',    'x9 D>TMt,;ob y&Cqt;<V9Oo_gwu9!<2eRR<zpY/xuIT1%_mCtCG_-dbA+NTZ|tZ');
define('NONCE_SALT',        ' {VECw!|DF5n-/B+V}3kC0(P;27KWEB(b1*>am}0!tzq-$Qed_)YrkrUwz3.gN|-');
define('WP_CACHE_KEY_SALT', 'rCXhg+%168Xz_5h1b3DzS&1yr[vBDPCX;n0N6FI]=tG&a%4b6v7vg{WK;}jO0T%X');


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
if (!defined('WP_DEBUG')) {
	define('WP_DEBUG', false);
}

define('WP_ENVIRONMENT_TYPE', 'local');
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if (!defined('ABSPATH')) {
	define('ABSPATH', __DIR__ . '/');
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
