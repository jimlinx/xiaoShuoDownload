<?php

namespace Helper;

use Page\Episodes;

class Utility
{
    public static function dfCurl($url, $fileName, $path = "/home/jimlin/Downloads"): void
    {
        $ch = curl_init($url);
        $saveFilePath = "$path/$fileName";
        $fp = fopen($saveFilePath, 'wb');
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);
    }

    public static function readJsonFile($path)
    {
        $data = file_get_contents($path);
        return json_decode($data, true);
    }

    public static function curlDownload($url, $fileName, $path = "/home/jimlin/Downloads"): void
    {
        $cmd = "curl -L -o $path$fileName $url";
        echo "Command: $cmd\n";
        shell_exec($cmd);
    }

    public static function curlConfigDownload($url, $fileName, $path = "/home/jimlin/Downloads"): void
    {
        $content = "output = \"$path/$fileName\"\n";
        $content .= "user-agent = \"superagent/1.0\"\n";
        $content .= "url = \"$url\"\n";
        $content .= "-L\n";
        $content .= "-Z";
        self::writeToFile("config.txt", $content);
        shell_exec("curl --config $path/config.txt");
    }

    public static function curlMultiDownload($name, $urls, $path = "/home/jimlin/Downloads"): void
    {
        $content = "";
        foreach($urls as $episode => $url) {
            $content .= "$path/$name" . "_$episode.mp4 $url\n";
        }
        $content = substr_replace($content, "", -1);
        self::writeToFile("list.txt", $content);

        $cmd = "xargs -n2 -P " . Episodes::$parallelDownload . " curl -L -o < $path/list.txt";
        shell_exec($cmd);
    }

    public static function saveUrl($url, $fileName, $path = "/home/jimlin/Downloads"): void
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
}