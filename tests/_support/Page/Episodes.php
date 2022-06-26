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

    /**
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
        $urls = [];
        self::$I->amOnUrl($url);
        $downloadBtns = self::$I->grabMultiple(self::$tenDownload_main_dl_btn, 'href');
        foreach($downloadBtns as $downloadBtn) {
            $urls[] =  $baseUrl . "$downloadBtn\n";
        }

        $saveUrls = [];
        $total = count($urls);
        $count = 1;
        foreach($urls as $url) {
            self::$I->amOnUrl($url);
            self::$I->waitForElement(self::$tenDownload_episode_360p_btn, 30);
            $episodeTitle = self::$I->grabAttributeFrom(self::$tenDownload_episode_360p_btn, 'download');

            # Parse episode number
            echo "[$total / $count] Episode Title: $episodeTitle\n";
            $episodeName = $episodeTitle;
            $regex = "!(\d+)!";
            preg_match($regex, $episodeTitle, $match);
            if ($match != null) {
                $episode = str_pad($match[1], 4, '0', STR_PAD_LEFT);
                $episodeName = $name . "_" . $episode;
            }
            # Parse episode URL
            $downloadUrl = self::$I->grabAttributeFrom(self::$tenDownload_episode_360p_btn, 'href');

            echo "$episodeName: $downloadUrl\n\n";
            $saveUrls[$episode] = [
                'name' => $episodeName,
                'url' => $downloadUrl
            ];
//            Utility::saveUrl($downloadUrl, $episodeName);
            $count++;

            if($count == 5)
                break;
        }

        Utility::writeToFile("$name.json", json_encode($saveUrls));
    }

    public static function downloadFromJsonFile($path)
    {
        $jsonObj = Utility::readJsonFile($path);
        foreach($jsonObj as $entry) {
            echo "name: " . $entry['name'] . "\n";
            echo "url: " . $entry['url'] . "\n\n";
        }
    }
}