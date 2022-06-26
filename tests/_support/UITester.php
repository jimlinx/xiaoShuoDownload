<?php


/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 * @method void waitForElement($element, $timeout = 10)
 * @method string grabAttributeFrom($cssOrXpath, $attribute)
 * @method void restart()
 *
 * @SuppressWarnings(PHPMD)
*/
class UITester extends \Codeception\Actor
{
    use _generated\UITesterActions;

    /**
     * Define custom actions here
     */
}
