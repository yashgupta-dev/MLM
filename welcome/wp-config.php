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
define( 'DB_NAME', 'winne4vg_wp605' );

/** MySQL database username */
define( 'DB_USER', 'winne4vg_wp605' );

/** MySQL database password */
define( 'DB_PASSWORD', 'b!3b8@Sp22' );

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
define( 'AUTH_KEY',         'atgiugw8ztxa0vqorq949irwpsuwl3eleao3l9n0anzpk1ajiiybwxsdvh7iaffk' );
define( 'SECURE_AUTH_KEY',  'emsnxuevjpbtqa849c05s5ukllwzx7hvxtbrksnhzjgdmkdu5gahjymn46xlu68z' );
define( 'LOGGED_IN_KEY',    'cl4yswobwwp99ikw5tlruta478f5vbs5vgwaxyqsf1qoghknoxuqf83gkkxg7tcc' );
define( 'NONCE_KEY',        'dkgu6l6pxjm8j0tt7cfhgjbru6uxqfitpddagnp6rv2xqamkzxj3ixibdk4ejxdv' );
define( 'AUTH_SALT',        'gjyu3zkiwglcibflk6tnwyoymbqg63pcpgn2zkd0rjgjc0vkxeiouyk21v6qcsvs' );
define( 'SECURE_AUTH_SALT', 'nzav6bowwjnvucmasb5numwthggkg3lrifigtyvq8qro5v7wl4vgjbjckmqf1crt' );
define( 'LOGGED_IN_SALT',   'nlfsq7b5mcsiqscjvnzflwc1tfewlq4rtjf4xmz6cvqkeugx7rbt4aajzq7ubxb3' );
define( 'NONCE_SALT',       'avzm41gys98drrwbyng5d6xr9xnsflmav7ztofqleop2yhscvftfel1lze8ssbvv' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wpht_';

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
