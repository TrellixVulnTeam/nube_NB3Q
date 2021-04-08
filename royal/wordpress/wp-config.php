<?php
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

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', '' );

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
define( 'AUTH_KEY',         'muZb BulFhG{1pLdS5Zlw|1VJ{+)+d)V-mzw$6g@@&BbeGc/Ldk#R5,cx`?Z2*Vq' );
define( 'SECURE_AUTH_KEY',  'l}F^mo0ptwi j|fjyn^vf=Ox8]5cz~/j%he bHO+<&y}C*$EG5{Ux}]ICaype$5S' );
define( 'LOGGED_IN_KEY',    'Hg;pOje$yPjXAVg[^cVHM#?JabN1O`.Va]U !,gv[2Qq]UiMIZ.85<0+^YK]X?aQ' );
define( 'NONCE_KEY',        'OB7s&>!mg5^.5U[6+= 2MRIWNE&eppvusNVUDa0D&xN-m(M^S[3)^Fhs[$uw3*k.' );
define( 'AUTH_SALT',        'B*(s4BLTq{sp*,}1+:e&9hSg%LQ42`9*4q3LEE:wIID:eGq5H/$HOrTN$3D{zu*=' );
define( 'SECURE_AUTH_SALT', '?__2kW+VY3^v.ar.EB0x/jS8o8q#hJDFN[oct|6;4Vv{GY ?R:hcplpq5ua=r1TN' );
define( 'LOGGED_IN_SALT',   '163Qc]e3D/;]ik:`VVI`mz02 eoLR#01WsQ.y,I+{wyt,kbv_6oLr7B6jt lAu~q' );
define( 'NONCE_SALT',       'jhl8W@,xTpnCa8 ~B|Vt-ZGugJ{<jcPIL$az1}xk=]:It;mIX-[q#=PUV0&g&D^j' );

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
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
