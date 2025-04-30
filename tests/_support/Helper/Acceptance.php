<?php
namespace Helper;

class Acceptance extends \Codeception\Module{

    private $hostname = "";

    /*
     * Updates the WPWebDriver module configuration to switch the active domain for the WordPress installation under test.
     * This function allows you to dynamically switch the test environment's URL. You can set it to "localhost"
     * for local testing, or to the IP address of a remote node or the mothership server. The function accepts
     * an associative array that specifies the new configuration parameters, such as the base URL.
     *
     * Example:
     *   $I->reconfigureThisVariable(["url" => "http://3.14.55.132"]);
     *   You can then login to that site with "Codeception" and "password"
     *
     * @param array $array An associative array with the new configuration options for WPWebDriver.
     *                     The 'url' key is commonly used to specify the WordPress site's URL.
     * @return void This function does not return a value.
     */
    public function reconfigureThisVariable($array){
        $this->getModule('WPWebDriver')->_reconfigure($array);
        $this->getModule('WPWebDriver')->_restart();
    }


    public function _beforeSuite($settings = []){
        $this->hostname = shell_exec("hostname");
    }

    public function pauseInTerminal(){
        echo "Press ENTER to continue: ";
        $handle = fopen ("php://stdin","r");
        $line = fgets($handle);
        fclose($handle);
        echo "\n";
    }

    public function get_config(){
        return $this->getModule('WPWebDriver')->_getConfig();
    }

}
