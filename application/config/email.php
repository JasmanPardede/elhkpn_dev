<?php

defined('BASEPATH') OR exit('No direct script access allowed.');

$config['useragent']        = 'PHPMailer';              // Mail engine switcher: 'CodeIgniter' or 'PHPMailer'
$config['protocol']         = 'smtp';                   // 'mail', 'sendmail', or 'smtp'
$config['mailpath']         = '/usr/sbin/sendmail';
$config['_smtp_auth']        = true;
$config['starttls']         = FALSE;
//$config['smtp_host']        = 'mail.kpk.go.id';
$config['smtp_host']        = '192.168.1.4';
//$config['smtp_host']        = '10.10.0.2';
$config['smtp_user']        = 'anonymous';
$config['smtp_pass']        = '';
$config['smtp_port']        = 25; //default 465
//$config['smtp_port']        = 587; //default 465
//$config['smtp_timeout']     = 100;                        // (in seconds)
$config['smtp_crypto']      = ''; //default ssl                    // '' or 'tls' or 'ssl'
$config['smtp_debug']       = 0;                      // PHPMailer's SMTP debug info level: 0 = off, 1 = commands, 2 = commands and data, 3 = as 2 plus connection status, 4 = low level data output.
$config['wordwrap']         = true;
$config['wrapchars']        = 76;
$config['mailtype']         = 'html';                   // 'text' or 'html'
$config['charset']          = 'utf-8';
$config['validate']         = true;
$config['priority']         = 3;                        // 1, 2, 3, 4, 5; on PHPMailer useragent NULL is a possible option, it means that X-priority header is not set at all, see https://github.com/PHPMailer/PHPMailer/issues/449
$config['crlf']             = "\n";                     // "\r\n" or "\n" or "\r"
$config['newline']          = "\n";                     // "\r\n" or "\n" or "\r"
$config['bcc_batch_mode']   = false;
$config['bcc_batch_size']   = 200;
$config['encoding']         = '8bit';
