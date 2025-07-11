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

    public function switchBetweenLinkedAnchorPosts($I){
        // Get the current linked post ID from CacbotData
        $currentLinkedPostId = $I->executeJS('return cacbotData.get("linked_post_id")');
        
        // Find all sidebar links with data-post-id attributes
        $sidebarLinks = $I->executeJS('
            const links = document.querySelectorAll(".anchor-post-list li[data-post-id]");
            return Array.from(links).map(link => {
                return {
                    id: link.getAttribute("data-post-id"),
                    element: link
                };
            });
        ');
        
        // Find a link with a different post ID than the current one
        $I->executeJS("
            const currentId = '$currentLinkedPostId';
            const links = document.querySelectorAll('.anchor-post-list li[data-post-id]');
            
            for (const link of links) {
                const linkId = link.getAttribute('data-post-id');
                if (linkId !== currentId) {
                    // Click the link with a different post ID
                    link.querySelector('a').click();
                    return true;
                }
            }
            
            return false;
        ");

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
