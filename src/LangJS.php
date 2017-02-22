<?php
namespace AwkwardIdeas\LangJS;

class LangJS{
    public function __construct(){

    }

    public static function BuildLangFiles($destination){
        $langFileFolderRoot = resource_path('lang');
        $langScriptPath = public_path($destination);

        $langFiles = self::GetLangFiles($langFileFolderRoot);

        $json = self::ParseFilesIntoJSON($langFiles);

        self::SaveJSONToFile($json, $destination);
    }

    public static function GetLangFiles($langFileFolderRoot){
        $files = self::DirectoryToArray($langFileFolderRoot);
    }

    public static function DirectoryToArray($dir){
        $result = array();

        $cdir = scandir($dir);
        foreach ($cdir as $key => $value)
        {
            if (!in_array($value,array(".","..")))
            {
                if (is_dir($dir . DIRECTORY_SEPARATOR . $value))
                {
                    $result[$value] = dirToArray($dir . DIRECTORY_SEPARATOR . $value);
                }
                else
                {
                    $result[] = $value;
                }
            }
        }

        return $result;
    }

    public static function ParseFilesIntoJSON($filesArray){
        $langData = array();


        $langData = self::ParseDirectory(resource_path('lang'), $filesArray);

        return json_encode($langData);
    }

    public static function ParseDirectory($pathToArray, $dirArray){
        $dirData = array();

        foreach($dirArray as $key=>$entry){
            if(is_array($entry)){
                $pathToArray .= "$key/";
                $dirData[$key]=self::ParseDirectory($pathToArray, $entry);
            }else{
                $dirData[$key]=self::ParseFile($pathToArray, $entry);
            }
        }
        return $dirData;
    }

    public static function ParseFile($pathToFile, $file){
        return include $pathToFile.$file;
    }

    public static function SaveJSONToFile($json, $destination){
        $fp = fopen($destination, 'w');
        fwrite($fp, $json);
        fclose($fp);
    }
}