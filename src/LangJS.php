<?php
namespace AwkwardIdeas\LangJS;

class LangJS{
    public function __construct(){

    }

    public static function BuildLangFiles($destination){
        $langFileFolderRoot = resource_path('lang');
        $langScriptPath = public_path($destination);
        self::LogToFile("Getting File List", $langScriptPath);
        $langFiles = self::GetLangFiles($langFileFolderRoot);
        self::LogToFile("Parsing To JSON", $langScriptPath);
        $json = self::ParseFilesIntoJSON($langFiles);
        self::LogToFile("Saving To File", $langScriptPath);
        self::EmbedJSONInScript($json, $langScriptPath);
    }

    public static function GetLangFiles($langFileFolderRoot){
        return self::DirectoryToArray($langFileFolderRoot);
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
                    $result[$value] = self::DirectoryToArray($dir . DIRECTORY_SEPARATOR . $value);
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
        $langData = self::ParseDirectory(resource_path('lang')."/", $filesArray);

        return json_encode($langData);
    }

    public static function ParseDirectory($pathToArray, $dirArray){
        $dirData = array();
        foreach($dirArray as $key=>$entry){
            if(is_array($entry)){
                $pathToArray .= "$key/";
                $dirData[$key]=self::ParseDirectory($pathToArray, $entry);
            }else{
                $dirData[str_replace(".php","",$entry)]=self::ParseFile($pathToArray, $entry);
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

    public static function EmbedJSONInScript($json, $destination){
        $langJS = include "LangJS.js.php";
        $langJS = str_replace("%JSON%",$json,$langJS);
        $fp = fopen($destination, 'w');
        fwrite($fp, $langJS);
        fclose($fp);
    }

    public static function LogToFile($logMessage, $destination){
        $fp = fopen($destination, 'w');
        fwrite($fp, $logMessage);
        fclose($fp);
    }
}