<?php
/*
 ___  ___    __    _   _  _  _  ____  _  _           _  _  ____  _  _    ___  ___ 
(___)(___)  (  )  ( )_( )( )/ )(  _ \( \( )   ___   ( )/ )(  _ \( )/ )  (___)(___)
 ___  ___    )(__  ) _ (  )  (  )___/ )  (   (___)   )  (  )___/ )  (    ___  ___ 
(___)(___)  (____)(_) (_)(_)\_)(__)  (_)\_)         (_)\_)(__)  (_)\_)  (___)(___)
*/
/** 
 * Helper kpk_helper
 * 
 * @author Gunaones - PT.Mitreka Solusi Indonesia
 * @package Helper
 */

class kpk{
	public static function mail_send($to, $subject, $message, $from = null, $attach = null, $cc = null){
        $CI =& get_instance();
        
        // mail($to, $subject, $message, $headers);
        if ($from != null) {
            $from = $from;
        }else{
            $from =  $CI->config->item('EMAIL_FROM');
        }
        
        $subject = ng::text_filter($subject);

        $header  = $CI->config->item('mail_server');
        $config['protocol'] = "smtp";
        $config['smtp_host'] = $CI->config->item('smtp_host'); 
        $config['smtp_port'] = $CI->config->item('smtp_port');
        $config['smtp_user'] = $CI->config->item('smtp_user');
        $config['smtp_pass'] = $CI->config->item('smtp_pass');
        $config['mailtype'] = 'html';
        $config['charset'] = 'utf-8';
        $config['newline'] = "\r\n";
        $config['wordwrap'] = TRUE;

        $CI->email->initialize($config);
        $CI->email->from($from, "Aplikasi LHKPN");
        $CI->email->to($to);
        $CI->email->subject($subject);
        $CI->email->message($message);

        if(!is_null($attach)) {
            $CI->email->attach($attach);
        }
        if(!is_null($cc)) {
            $CI->email->cc($cc);
        }
        if (!$CI->email->send()) {
            // show_error($CI->email->print_debugger());
            return false;
        }
        else {
            return true;
        }
	}
}