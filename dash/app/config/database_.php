<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------
| DATABASE CONNECTIVITY SETTINGS
| -------------------------------------------------------------------
| This file will contain the settings needed to access your database.
|
| For complete instructions please consult the 'Database Connection'
| page of the User Guide.
|
| -------------------------------------------------------------------
| EXPLANATION OF VARIABLES
| -------------------------------------------------------------------
|
|	['hostname'] The hostname of your database server.
|	['username'] The username used to connect to the database
|	['password'] The password used to connect to the database
|	['database'] The name of the database you want to connect to
|	['dbdriver'] The database type. ie: mysql.  Currently supported:
				 mysql, mysqli, postgre, odbc, mssql, sqlite, oci8
|	['dbprefix'] You can add an optional prefix, which will be added
|				 to the table name when using the  Active Record class
|	['pconnect'] TRUE/FALSE - Whether to use a persistent connection
|	['db_debug'] TRUE/FALSE - Whether database errors should be displayed.
|	['cache_on'] TRUE/FALSE - Enables/disables query caching
|	['cachedir'] The path to the folder where cache files should be stored
|	['char_set'] The character set used in communicating with the database
|	['dbcollat'] The character collation used in communicating with the database
|				 NOTE: For MySQL and MySQLi databases, this setting is only used
| 				 as a backup if your server is running PHP < 5.2.3 or MySQL < 5.0.7
|				 (and in table creation queries made with DB Forge).
| 				 There is an incompatibility in PHP with mysql_real_escape_string() which
| 				 can make your site vulnerable to SQL injection if you are using a
| 				 multi-byte character set and are running versions lower than these.
| 				 Sites using Latin-1 or UTF-8 database character set and collation are unaffected.
|	['swap_pre'] A default table prefix that should be swapped with the dbprefix
|	['autoinit'] Whether or not to automatically initialize the database.
|	['stricton'] TRUE/FALSE - forces 'Strict Mode' connections
|							- good for ensuring strict SQL while developing
|
| The $active_group variable lets you choose which connection group to
| make active.  By default there is only one group (the 'default' group).
|
| The $active_record variables lets you determine whether or not to load
| the active record class
*/

date_default_timezone_set("Asia/Colombo");

$active_group = 'account';
$active_record = TRUE;

$db['account']['hostname'] = 'localhost';
$db['account']['username'] = 'root';
$db['account']['password'] = 'smtk';
$db['account']['database'] = 'seetha';
$db['account']['dbdriver'] = 'mysql';
$db['account']['dbprefix'] = '';
$db['account']['pconnect'] = TRUE;
$db['account']['db_debug'] = TRUE;
$db['account']['cache_on'] = FALSE;
$db['account']['cachedir'] = '';
$db['account']['char_set'] = 'utf8';
$db['account']['dbcollat'] = 'utf8_general_ci';
$db['account']['swap_pre'] = '';
$db['account']['autoinit'] = TRUE;
$db['account']['stricton'] = FALSE;

$active_group = 'company';
$active_record = TRUE;

$db['company']['hostname'] = 'localhost';
$db['company']['username'] = 'root';
$db['company']['password'] = 'smtk';
$db['company']['database'] = 'seetha';
$db['company']['dbdriver'] = 'mysql';
$db['company']['dbprefix'] = '';
$db['company']['pconnect'] = TRUE;
$db['company']['db_debug'] = TRUE;
$db['company']['cache_on'] = FALSE;
$db['company']['cachedir'] = '';
$db['company']['char_set'] = 'utf8';
$db['company']['dbcollat'] = 'utf8_general_ci';
$db['company']['swap_pre'] = '';
$db['company']['autoinit'] = TRUE;
$db['company']['stricton'] = FALSE;

//------------------------- Company Databases -------------------------------//

$active_group = 'seetha';
$active_record = TRUE;

$db['seetha']['hostname'] = 'localhost';
$db['seetha']['username'] = 'root';
$db['seetha']['password'] = 'smtk';
$db['seetha']['database'] = 'seetha';
$db['seetha']['dbdriver'] = 'mysql';
$db['seetha']['dbprefix'] = '';
$db['seetha']['pconnect'] = TRUE;
$db['seetha']['db_debug'] = TRUE;
$db['seetha']['cache_on'] = FALSE;
$db['seetha']['cachedir'] = '';
$db['seetha']['char_set'] = 'utf8';
$db['seetha']['dbcollat'] = 'utf8_general_ci';
$db['seetha']['swap_pre'] = '';
$db['seetha']['autoinit'] = TRUE;
$db['seetha']['stricton'] = FALSE;

//$active_group = 'janasiri2';
//$active_record = TRUE;
//
//$db['janasiri2']['hostname'] = 'localhost';
//$db['janasiri2']['username'] = 'softmast_root';
//$db['janasiri2']['password'] = 'sa';
//$db['janasiri2']['database'] = 'janasiri_server';
//$db['janasiri2']['dbdriver'] = 'mysql';
//$db['janasiri2']['dbprefix'] = '';
//$db['janasiri2']['pconnect'] = TRUE;
//$db['janasiri2']['db_debug'] = TRUE;
//$db['janasiri2']['cache_on'] = FALSE;
//$db['janasiri2']['cachedir'] = '';
//$db['janasiri2']['char_set'] = 'utf8';
//$db['janasiri2']['dbcollat'] = 'utf8_general_ci';
//$db['janasiri2']['swap_pre'] = '';
//$db['janasiri2']['autoinit'] = TRUE;
//$db['janasiri2']['stricton'] = FALSE;

//------------------------- End Company DB ---------------------------------//
$active_group = 'default';
$active_record = TRUE;

$db['default']['hostname'] = 'localhost';
$db['default']['username'] = 'root';
$db['default']['password'] = 'smtk';
$db['default']['database'] = 'seetha';
$db['default']['dbdriver'] = 'mysql';
$db['default']['dbprefix'] = '';
$db['default']['pconnect'] = TRUE;
$db['default']['db_debug'] = TRUE;
$db['default']['cache_on'] = FALSE;
$db['default']['cachedir'] = '';
$db['default']['char_set'] = 'utf8';
$db['default']['dbcollat'] = 'utf8_general_ci';
$db['default']['swap_pre'] = '';
$db['default']['autoinit'] = TRUE;
$db['default']['stricton'] = FALSE;


/* End of file database.php */
/* Location: ./application/config/database.php */