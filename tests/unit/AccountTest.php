<?php


class AccountTest extends \Codeception\TestCase\WPTestCase
{

    public function setUp(): void
    {
        parent::setUp();
        require_once('/var/www/html/wp-content/plugins/cacbot/src/Cacbot/autoloader.php');
        \Cacbot\Constants::define();
    }

    /**
     * @test
     * it should be instantiable
     */
    public function isShouldExist()
    {
        $Account = new \CacbotMothership\Account;
    }

}