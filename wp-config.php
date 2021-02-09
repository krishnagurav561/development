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
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress' );

/** MySQL database username */
define( 'DB_USER', 'wordpress' );

/** MySQL database password */
define( 'DB_PASSWORD', 'PAh374GQ' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          'NR3m{B0=RUy> 5h2lY#3RQ24[1Z3vAG&TZ)N30;CKUGNmE/HJyVC+C=.Z(wsQxZ6' );
define( 'SECURE_AUTH_KEY',   'd%ZkCd!~56+G7jy(djwdc?mZ5cF/oj4vG_paC:La+g-~vtt56xs!hFOS3?R{@]ys' );
define( 'LOGGED_IN_KEY',     'o[,?o__lFC9  ZEo&S{AhCvux$u>mtf/EtpSB{Gk12+xL9PP[YOqvCy|vp-gie5o' );
define( 'NONCE_KEY',         'sVXpxf?VDxHtg}?))MF8Xt.WivbvX!6LIU?,eHP4t-$^#ZwpC [e+MW.?T>D]&[P' );
define( 'AUTH_SALT',         'Q,mr)r98{0zoT0&kh_I7U_bn7#SMpi!Z4k~n{B],;E3P*~b8v]=Nfe4BnE?#gP #' );
define( 'SECURE_AUTH_SALT',  ';G1F%79;MeKF;Z8-+.HaWL,8aS=vT_kM8;vsc.bM7zL52erZ_<0(A>XXOm+<njU$' );
define( 'LOGGED_IN_SALT',    'x}V*N_5qLn0{S3A*&8P&U_t9_wYg$dOe7Z$?i,ycLiW]_3bLs?/1%:]HAm#Ln^^6' );
define( 'NONCE_SALT',        'kfdp7!c9]io,@I{-y!} eXR6ZYn]/2&B+}6@=QJVjS]]<O**C<D4qAz+C@ne#oiz' );
define( 'WP_CACHE_KEY_SALT', 'D{y`uX*m9FF)c+Z> /2]TstB#|rFj$GV@bh`0> @a>B+%vGjyIa8SCEnWkGa<IiB' );

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';




/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
