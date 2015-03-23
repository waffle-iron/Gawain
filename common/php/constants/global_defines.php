<?php

// Global definitions used through the whole application

/* ========= */
/*   PATHS   */
/* ========= */

/**
 * Root folder path
 */
define('ROOT_DIR', realpath(dirname(__FILE__) . '/../../../'));

/* -------------------------------------------------------------------- */

/**
 * REST API folder path
 */
define('RESTAPI_DIR', ROOT_DIR . '/rest-api/');


/**
 * Common elements folder path
 */
define('COMMON_DIR', ROOT_DIR . '/common/');


/**
 * Modules folder path
 */
define('MODULES_DIR', ROOT_DIR . '/modules/');

/* -------------------------------------------------------------------- */

/**
 * Configuration file folder path
 */
define('CONFIG_DIR', COMMON_DIR . 'config/');


/**
 * PHP source files folder path
 */
define('PHP_DIR', COMMON_DIR . 'php/');


/**
 * SQL scripts folder path
 */
define('SQL_DIR', COMMON_DIR . 'sql/');

/* -------------------------------------------------------------------- */

/**
 * PHP Abstract Classes folder path
 */
define('PHP_ABSTRACTS_DIR', PHP_DIR . 'abstracts/');


/**
 * PHP Concrete Classes folder path
 */
define('PHP_CLASSES_DIR', PHP_DIR . 'classes/');


/**
 * PHP Constants definitions folder path
 */
define('PHP_CONSTANTS_DIR', PHP_DIR . 'constants/');


/**
 * PHP Functions folder path
 */
define('PHP_FUNCTIONS_DIR', PHP_DIR . 'functions/');

/* ==================================================================== */


/* ============== */
/*   LOG LEVELS   */
/* ============== */

define('LOG_FATAL_ERROR',	'FATAL ERROR');
define('LOG_ERROR',			'ERROR');
define('LOG_WARNING',		'WARNING');
define('LOG_INFO',			'INFO');
define('LOG_DEBUG',			'DEBUG');


?>