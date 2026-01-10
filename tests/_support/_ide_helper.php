<?php
/**
 * IDE Helper for Codeception WebDriver methods
 * This file provides type hints for Intelephense to recognize WebDriver methods
 */

namespace Helper {
    /**
     * @method \Codeception\Module\WPWebDriver getModule(string $name)
     */
    class Acceptance extends \Codeception\Module
    {
        // This class extends the actual Helper\Acceptance class
        // and provides type hints for IDE support
    }
}

namespace Codeception\Module {
    /**
     * WebDriver module stub for IDE support
     */
    class WebDriver extends \Codeception\Module
    {
        /**
         * Execute JavaScript code
         * @param string $script JavaScript code to execute
         * @return mixed Result of the JavaScript execution
         */
        public function executeJS($script) {}
        
        /**
         * Wait for a specified amount of time
         * @param int|float $timeout Time to wait in seconds
         * @return void
         */
        public function wait($timeout) {}
        
        /**
         * Click on an element
         * @param string $selector CSS selector or XPath
         * @return void
         */
        public function click($selector) {}
        
        /**
         * Fill a form field
         * @param string $field Field selector
         * @param string $value Value to fill
         * @return void
         */
        public function fillField($field, $value) {}
        
        /**
         * See text on the page
         * @param string $text Text to look for
         * @return void
         */
        public function see($text) {}
        
        /**
         * Go to a specific URL
         * @param string $url URL to navigate to
         * @return void
         */
        public function amOnPage($url) {}
        
        /**
         * Reconfigure the module
         * @param array $config Configuration array
         * @return void
         */
        public function _reconfigure($config) {}
        
        /**
         * Restart the module
         * @return void
         */
        public function _restart() {}
        
    }
    
    /**
     * WPWebDriver module stub for IDE support
     */
    class WPWebDriver extends WebDriver
    {
        /**
         * Login as admin user
         * @param int $timeout Timeout in seconds
         * @param int $maxAttempts Maximum login attempts
         * @return void
         */
        public function loginAsAdmin($timeout = 10, $maxAttempts = 5) {}
        
        /**
         * Login as specific user
         * @param string $username Username
         * @param string $password Password
         * @param int $timeout Timeout in seconds
         * @param int $maxAttempts Maximum login attempts
         * @return void
         */
        public function loginAs($username, $password, $timeout = 10, $maxAttempts = 5) {}
        
        /**
         * Go to admin page
         * @param string $page Admin page path
         * @return void
         */
        public function amOnAdminPage($page) {}
        
        /**
         * Go to WordPress page
         * @param string $page Page path
         * @return void
         */
        public function amOnPage($page) {}
    }
}