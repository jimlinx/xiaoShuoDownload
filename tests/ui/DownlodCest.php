<?php

use Page\Episodes;

class DownlodCest
{
    public function _before(UITester $I): void
    {
        Episodes::$I = $I;
    }


    public function parse(): void
    {
        Episodes::tenDownloader("https://10downloader.com/playlist?v=https%3A%2F%2Fwww.youtube.com%2Fplaylist%3Flist%3DPLRdhGgJyo5jpBs6zdPBt3rQbZKLdaRsWu", "ShengXu");
    }

    public function download(): void
    {
        Episodes::downloadFromJsonFile("/home/jimlin/Downloads/ShengXu.json");
    }
}
