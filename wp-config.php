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
define( 'DB_NAME', 'ltc' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'ricorda' );

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
define( 'AUTH_KEY',          '1tlj0RB:H6L4-8z^D$l8zuq~neT}}NP,;9=JG[_iF`N!s&d/@^`CC` ]a*8e/D/n' );
define( 'SECURE_AUTH_KEY',   ';F`;;iGer+,HZGhjNt[clg|XE8]Wfh`cq+lL.(W %Z.C$J;:WDX5A6foRRL9s(:h' );
define( 'LOGGED_IN_KEY',     'YmnZq 96C]--JrReA#(PP]P%jkB6dzGn^[<epX|A>JN{L|)F{^B#}>Z&{p.jW4x/' );
define( 'NONCE_KEY',         '4&U`U3/l)FUBEu@^otHu&>3UxOQ#6Iy:ek4*Y$~w}S)T7GIqy`zy`Tm2/v5LWnE>' );
define( 'AUTH_SALT',         'Z/>}=L@W52u;lIu{FM7[mOD>NBRc)`{5*0] B},Uw)lXD+h#{jkX_6pX0jZW# D1' );
define( 'SECURE_AUTH_SALT',  'eSdX?|ZVQNsI0[aE?WTi8/_yMyp}[=q95<B1?tx>nu|@zm..zXy,Nt #;pZhUr4v' );
define( 'LOGGED_IN_SALT',    'K?M{ e2s);`=<;xS4XYZMXK<]HaUDf6@3p:#U`Oz=@ADd_MW8|bw:b%.eImk8^3 ' );
define( 'NONCE_SALT',        '?+A(;%IzRd(c(Cpkw)qNtk-JHTI(}6.gj3/fh|::MX0ME/?DM:q#NjuB[:l`Vjo+' );
define( 'WP_CACHE_KEY_SALT', '^8l86M}`@M-J@5rDr>slB{g3FZ.]w%tws$9iaIEgFiPhkH*_d8KdUD>MgQtCh!.T' );

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
