<?php
    /**
    ** @author:andrew
    **/
namespace IpaySecure;
final class Utils{
    public static function validatePhpInput($raw_input, array $required_params){
        $res_obj = null;
        var_dump($raw_input);
        if($raw_input){
            foreach($required_params as $param){
                if(!property_exists($raw_input, $param) || empty($raw_input->$param) || !(is_string($raw_input->$param) || is_int($raw_input->$param))){
                    die($param . ' is required');
                    return false;
                }
                else{
                    $res_obj[$param] = $raw_input->$param;
                }
            }
            return (object) $res_obj;
        }
        else{
            throw new Exception('The following parameters are required ' . json_encode($required_params));
            return false;
        }
    }
    public static function formatError(\Exception $e, $error_desc){
        $message = ((json_decode($e->getMessage()) == null) ? $e->getMessage() : json_decode($e->getMessage()));
        $err_obj = (object) ['Desc' => $error_desc,
            'Line' => $e->getLine(),
            'File' => basename($e->getFile()),
            'Message' => $message,
            'StackTrace' => $e->getTraceAsString()
        ];
        return json_encode($err_obj);
    }
    public static function array_to_xml($array, &$xml_user_info) {
        foreach($array as $key => $value) {
            if(is_array($value)) {
                if(!is_numeric($key)){
                    $subnode = $xml_user_info->addChild("$key");
                    self::array_to_xml($value, $subnode);
                }else{
                    $subnode = $xml_user_info->addChild("item$key");
                    self::array_to_xml($value, $subnode);
                }
            }else {
                $xml_user_info->addChild("$key",htmlspecialchars("$value"));
            }
        }
    }
    public static function logger(array $logs, $dirname){
        //this line can be replaced with typehint of string if php>=7.1
        if(!is_string($dirname)){
            throw new \InvalidArgumentException('dirname must be a string');
        }
        else{
            // $logs	= (is_array($logs))? json_encode($logs, JSON_PRETTY_PRINT): (string)$logs;
            $logs = json_encode($logs);
            
            $dir = $dirname;

            $base_dir = dirname(__dir__).'/';

            $save_dir = $base_dir.$dirname;

            $dir_exists = (file_exists($save_dir) && is_dir($save_dir));

            if(!$dir_exists){
                if(!mkdir($save_dir, 0755, true)){
                    throw new \Exception('Unable to create directory');
                }
            }
            $dir = $save_dir;

            file_put_contents($dir."/".date("Y-m-d").'.log', $logs."\n", FILE_APPEND | LOCK_EX);
        }
    }
}
?>