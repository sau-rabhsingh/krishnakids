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
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */
@ini_set( 'max_input_vars' , 5000 );
// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'krishna_kids' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

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
define( 'AUTH_KEY',         'L~$XOGaT9}?2a*&Z~7O0mSl.QOf01iM5JU6fKCOKge[FlM}R]x ^*,V)?cVfZMs_' );
define( 'SECURE_AUTH_KEY',  'ow[%|RU^&r`vvDWAt4}a{R[umuZ+hX.K%W5C6l5#mL)XI7s8}hO~C3+hJvSJeOPE' );
define( 'LOGGED_IN_KEY',    '`V{p%6AHr?GJpTVeC~i*Bf8Q/VZKx/N7=kj,2ZF4ewA*K6CqZn`Q+YW~ UXRM6EP' );
define( 'NONCE_KEY',        '9J=s#^+l|!}-xY+TwOdoRiCh-UW$0tGdxd%<Q^>?6x}4Ea$HTFkE6e=&x%D{H:!V' );
define( 'AUTH_SALT',        '=dpNimn~S#>]_JcZKoi@7qrA5M!<+@Diidm+$`!Tq^9OhY-)4{.z*M{eEfG#6%OR' );
define( 'SECURE_AUTH_SALT', '|3XaWu[}HYqIJiT4.7qSJwA102q1qC>a&q_c!L**Jw4?cu<{8&1*w*4a;h9$|seZ' );
define( 'LOGGED_IN_SALT',   'F!26<AE;n)m[=+jw[$!S%u179|N,aeHc5UuF+~C/B6uif<E5jRrAYwTsL[&EpP6i' );
define( 'NONCE_SALT',       '|.o<iNN$+{vvf>i8~AhETm!bU2F*rEh2oL3/WWKfkEbqr(J,i/fE<PQ*3@_F_tCq' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'kk_';

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
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
