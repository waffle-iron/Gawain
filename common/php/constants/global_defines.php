<?php
/**
 * Gawain
 * Copyright (C) 2016  Stefano Romanò (rumix87 (at) gmail (dot) com)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

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
 * Internationalization (i18n) file folder path
 */
define('I18N_DIR', CONFIG_DIR . 'i18n/');


/**
 * PHP source files folder path
 */
define('PHP_DIR', COMMON_DIR . 'php/');


/**
 * SQL scripts folder path
 */
define('SQL_DIR', COMMON_DIR . 'sql/');


/**
 * Javascript scripts folder path
 */
define('JS_DIR', COMMON_DIR . 'js/');


/**
 * Twig templates folder path
 */
define('TEMPLATES_DIR', COMMON_DIR . 'templates/');

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


/**
 * PHP Vendor libraries folder path
 */
define('PHP_VENDOR_DIR', PHP_DIR . 'vendor/');

/* ==================================================================== */

/**
 * Server name
 */
define('SERVER_NAME', basename(ROOT_DIR));
