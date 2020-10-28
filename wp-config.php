<?php
$local_host = "$_SERVER[HTTP_HOST]";

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

define('FS_METHOD', 'direct');
// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'kof_staging');

if(strpos($local_host,'localhost') !== false) {
	/** MySQL database username */
	define( 'DB_USER', 'root' );

	/** MySQL database password */
	define( 'DB_PASSWORD', '' );
}
else{
	/** MySQL database username */
	define( 'DB_USER', 'root' );

	/** MySQL database password */
	define( 'DB_PASSWORD', 'Oodless' );
}


/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'OS4SFsCu}07kWc*RVZ!-![%SE0Kbmu9g_,e6w3CxZcEpwMkLE?BTG6tXRE*:hHuk' );
define( 'SECURE_AUTH_KEY',  'wlj|@Ta?p!c%zb9oJPzbn`zXXE%3i#C)/.cx86nPIX$Qy&wSv:pB}Y06#iGj@55E' );
define( 'LOGGED_IN_KEY',    'dE-rIx7>|2)9I(Q7-a} CLGd2`Kk3A*sS8&*d)Cl+.x8fZI;n9cu1z?xONnWw7fl' );
define( 'NONCE_KEY',        '=TAvd.alfjB f T=j8q`0+C<5#]|,~v?(MGcQ|GK6ciY&aUM~B1|.4eA*FqOGvOi' );
define( 'AUTH_SALT',        '(`P1Ongb&]L+z;Y,(UqtGj$ieq?(vFPva`#+&?^BG$PvFa)`?brkm4$8{qHfIrc(' );
define( 'SECURE_AUTH_SALT', '6(CU ;$#}lAX8nl_H,Y>.L`+M!aGS<F:o2@*od!x2(#!mu0ieAt</[eHd{O`bPy1' );
define( 'LOGGED_IN_SALT',   'W1@gtt(yzTj5i=H1z]i&I|W6mz^;62<RUtSD26V4E][4t.QZJJOgdu{{1ugFk$[k' );
define( 'NONCE_SALT',       '<dt$08BRuIKA-j~l#R(Ugf> JodHYQ!sn/Iga^) ga&V~QRt6q+{{{6Pc3ro{6Pk' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

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
	
ini_set('display_errors','Off');
ini_set('error_reporting', 0 );
define('WP_DEBUG', false);
define('WP_DEBUG_DISPLAY', false);
define( 'WPML_ST_SYNC_TRANSLATION_FILES', true );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
