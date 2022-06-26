<?php
namespace Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class UI extends \Codeception\Module
{
    public function restart()
    {
        $this->getModule('WebDriver')->_restart();
    }
}
