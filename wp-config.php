<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link https://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wordpress');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         '5rpoqeM~J>@(ru<lt_J!F|go`2m@10JXfJ7HtMDc[`{Fq9I)=(XEaS>GbmWgttJM');
define('SECURE_AUTH_KEY',  '?n$@V_r+@?o?+@$52J2gs:R8Ed$u7rOB{fL-b12n03)ocN3Q@IAg;VIxPbk?6^Sk');
define('LOGGED_IN_KEY',    '<Re*P9J&VzmZ00zg{!CfZjP#qIP trUVJpa-2Z2=0q@?5Hy<w@5,462BsS3U,w&e');
define('NONCE_KEY',        'K7Ftfr4$mk|j$)Q#hhtA=iU4C=@4.X=qv*Lk28*jKw-Rr.|:Ii[aR(yRXk%`CX+D');
define('AUTH_SALT',        'o!JzM`90 r1R#k9QCxZwRB*&{v]zD2r.;L@mHPmWNC7-Ao@4)gwm{5:&?>p|zP~G');
define('SECURE_AUTH_SALT', '|M3ljx(be)}j+OaUIN5ew42Gh%d~70IWu~5N%<r#:[|-.&e8CH!Hz7$;wT<z]6IO');
define('LOGGED_IN_SALT',   '%ipmm&[LL01_v!LvnSGi>2iu|2BShqb_Wd8?`J(}`YG.n-&[*qXE,8cnh+^_,@6-');
define('NONCE_SALT',       '#&yvk4?BxwCA`vITGD7EQgX |OR#cGwaIh8YVtD&jM34Q(;G 5tOFO(>m9wD%?Sf');

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
