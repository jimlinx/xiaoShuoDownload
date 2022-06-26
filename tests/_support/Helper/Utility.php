<?php

namespace Helper;

class Utility
{
    public static function saveUrl($url, $fileName, $path = "/home/jimlin/Downloads")
    {
        echo "Downloading [$fileName]...";
        file_put_contents("$path/$fileName", file_get_contents($url));
        echo "DONE!\n";
    }

    public static function writeToFile($fileName, $content): void
    {
        echo "Write To File: $fileName\n";
        $tmpFile = fopen("/home/jimlin/Downloads/" . $fileName, "w") or die ("Unable to open file!");
        fwrite($tmpFile, $content . "\n");
        fclose($tmpFile);
    }

    public static function readJsonFile($path)
    {
        $data = file_get_contents($path);
        return json_decode($data, true);
    }
}