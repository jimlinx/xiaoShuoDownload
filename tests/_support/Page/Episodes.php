<?php

namespace Page;

use Helper\Utility;
use UITester;

class Episodes
{
    public static UITester $I;

    public static string $ximalayaVideoSrc = "//video/source";
    public static string $tenDownload_main_dl_btn = "//a[@class='btn_download']";
    public static string $tenDownload_episode_360p_btn = "(//tr[descendant::td[text()='360p']]//a)[1]";

    public static int $parallelDownload = 20;

    public static function downloadFromJsonFile($path, $name): void
    {
        echo "\n";
        $jsonArray = Utility::readJsonFile($path);
        $total = count($jsonArray);
        $count = 1;

        $subCount = 1;
        $urlList = [];
        foreach ($jsonArray as $index => $url) {
            self::$I->amOnUrl($url);
            self::$I->waitForElement(self::$tenDownload_episode_360p_btn, 30);

            # Parse episode URL
            $downloadUrl = self::$I->grabAttributeFrom(self::$tenDownload_episode_360p_btn, 'href');

            echo "Index[$index][$count | $total]\n";
            $urlList[$index] = $downloadUrl;

            if ($subCount == self::$parallelDownload || $count == $total) {
                Utility::curlMultiDownload($name, $urlList);
                $subCount = 1;
                $urlList = [];
            }
//            Utility::curlConfigDownload($downloadUrl, $name . "_" . $index . ".mp4");
            $count++;
            $subCount++;
        }
    }

    /**
     * https://10downloader.com/playlist?v=https%3A%2F%2Fwww.youtube.com%2Fplaylist%3Flist%3DPLRdhGgJyo5jpBs6zdPBt3rQbZKLdaRsWu
     * @param $url
     * @param $name
     * @param string $baseUrl
     * @return void
     */
    public static function tenDownloader($url, $name, string $baseUrl = "https://10downloader.com"): void
    {
        echo "\n";
        $entries = [];
        self::$I->amOnUrl($url);

        $totalCount = count(self::$I->grabMultiple(self::$tenDownload_main_dl_btn, 'href'));
        echo "Total Episodes: $totalCount\n";

        for ($i = 1; $i <= $totalCount; $i++) {
            $xpath = "(//a[@class='btn_download'])[$i]/../../td[@class='playlist__video--title']";
            $title = trim(self::$I->grabTextFrom($xpath));
            $regex = "!(\d+)!";
            $index = $title;
            preg_match($regex, $title, $match);
            if ($match != null) {
                $episode = str_pad($match[1], 4, '0', STR_PAD_LEFT);
                $index = $episode;
            }
            $xpath = "(//a[@class='btn_download'])[$i]";
            $href = self::$I->grabAttributeFrom($xpath, 'href');
            $entries[$index] = $baseUrl . $href;
            echo "[$index]: " . $entries[$index] . "\n\n";
        }

        Utility::writeToFile("$name.json", json_encode($entries, JSON_PRETTY_PRINT));
    }

    /**
     * http://aitinghua.cn/
     * https://mobile.ximalaya.com/mobile/redirect/free/play/136783856/0
     * @param $start
     * @param $end
     * @param $base
     * @return void
     */
    public static function ximalayaParseSrc($start, $end, $base): void
    {
        for ($i = $start; $i <= $end; $i++) {
            $episodeUrl = $base . "/$i";
            echo "Parsing episode [$i]\n";
            self::$I->amOnUrl($episodeUrl);
            self::$I->waitForElement(self::$ximalayaVideoSrc, 30);
            $src = self::$I->grabAttributeFrom(self::$ximalayaVideoSrc, 'src');
            echo "Source: $src\n";
            self::$I->restart();

            Utility::saveUrl($src, "file_" . str_pad($i, 4, '0', STR_PAD_LEFT) . ".m4a");
        }
    }
}