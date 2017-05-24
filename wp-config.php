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
define('DB_NAME', 'donation');

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
define('AUTH_KEY',         'TU;;wKSw h =^*#mzGsUi>3{}r:_aMq&gMlUd;M{uUw_[(z1|Dc-2GgqmOgux*{k');
define('SECURE_AUTH_KEY',  '~IZig[d:?TXL2bALpdk;QQ}~?=v,7Z1:;p8YXs;McgK^^DS(Lt-RM%%ZBG%$mIB-');
define('LOGGED_IN_KEY',    '$xU/SW^@NaRv+)9(Sn;F&!CfhZ)WHesrg%FOxBhu_%Jvt;?xG?UDAlySbc?eC]c=');
define('NONCE_KEY',        's_/BU%E1Z,2U&*qwdlZgT!Z8ucYE0Z?mRdm+5ocOr}|bCCx)4AyRYia=gP/`<;Ih');
define('AUTH_SALT',        '[[,0`!-pV?_nxo<)+d%Zll?fBC..C+!83l:&,v<K^i_e2y^qkZLaLslf^2F6UVFK');
define('SECURE_AUTH_SALT', '(XGX`h7IUx5D|A`ml{M+l9vWd~?9?F)UVl~pfU_uyTO$b6FRLQH:G(MIrM9((MaJ');
define('LOGGED_IN_SALT',   'M|E|W]66Ge{H1ZPfv>zDW2^,1K4YwEyJ^rj%HDm|7N5^pIt`GRy0`&l[i9_t!- 2');
define('NONCE_SALT',       'm7}@P{e+=gq!N21]#hn^({B8yi+o|{sAALxr[^l{d)),_[J]:EIxU>EiT!WG@/+<');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'dn_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);
define('DISALLOW_FILE_MODS', false);
define('DISALLOW_FILE_EDIT', false);


/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */

require_once(ABSPATH . 'wp-settings.php');
