<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ', 'rb');
define('FOPEN_READ_WRITE', 'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE', 'ab');
define('FOPEN_READ_WRITE_CREATE', 'a+b');
define('FOPEN_WRITE_CREATE_STRICT', 'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
define('SHOW_DEBUG_BACKTRACE', FALSE);

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
define('EXIT_SUCCESS', 0); // no errors
define('EXIT_ERROR', 1); // generic error
define('EXIT_CONFIG', 3); // configuration error
define('EXIT_UNKNOWN_FILE', 4); // file not found
define('EXIT_UNKNOWN_CLASS', 5); // unknown class
define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
define('EXIT_USER_INPUT', 7); // invalid user input
define('EXIT_DATABASE', 8); // database error
define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code

define('productname', 'KPK.LHKPN');
define('productkey', '62742696007bc942b0');
define('ID_ROLE_ADMAPP', '1'); // USER ADMIN APLIKASI
define('ID_ROLE_AK', '2'); // USER ADMIN KPK
define('ID_ROLE_AI', '3'); // USER ADMIN INSTANSI
define('ID_ROLE_UI', '4'); // USER INSTANSI
define('ID_ROLE_PN', '5'); // USER PENYELENGGARA NEGARA
define('ID_ROLE_DE', '6'); // USER DATA ENTRY
define('ID_ROLE_VER', '7'); // USER VERIFIKATOR


define('ICON_pribadi','<i class="fa fa-user"></i>');
define('ICON_jabatan','<i class="fa fa-shield"></i>');
define('ICON_keluarga','<i class="fa fa-group"></i>');
define('ICON_harta','<i class="fa fa-university"></i>');
define('ICON_penerimaankas','<i class="fa fa-chevron-down"></i>');
define('ICON_pengeluarankas','<i class="fa fa-chevron-right"></i>');
define('ICON_lampiran','<i class="fa fa-paperclip"></i>');
define('ICON_fasilitas','<i class="fa fa-glass"></i>');
define('ICON_final','<i class="fa fa-list"></i>');
define('ICON_hartatidakbergerak','<i class="fa fa-home"></i>');
define('ICON_hartabergerak','<i class="fa fa-car"></i>');
define('ICON_hartabergerakperabot','<i class="fa fa-television"></i>');
define('ICON_suratberharga','<i class="fa fa-file-text-o"></i>');
define('ICON_kas','<i class="fa fa-briefcase"></i>');
define('ICON_hartalainnya','<i class="fa fa-trophy"></i>');
define('ICON_hutang','<i class="fa fa-credit-card"></i>');
define('ICON_klarifikasi','<i class="fa fa-calendar"></i>');


define('ICON_pelepasanharta','<i class="fa fa-paperclip"></i>');
define('ICON_penerimaanhibah','<i class="fa fa-paperclip"></i>');
define('ICON_penerimaanfasilitas','<i class="fa fa-paperclip"></i>');
define('ICON_suratkuasamengumumkan','<i class="fa fa-paperclip"></i>');
define('ICON_dokumenpendukung','<i class="fa fa-paperclip"></i>');


