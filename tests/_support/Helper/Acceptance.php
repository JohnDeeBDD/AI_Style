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
        
        // Initialize zoom enforcement for the entire test suite
        // Zoom enforcement removed from _beforeSuite to avoid WebDriver null error
    }
    
    /**
     * Ensure 100% zoom before each individual test
     */
    public function _before(\Codeception\TestInterface $test) {
        // Zoom enforcement removed from _before; must be called explicitly after navigation in each test
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

    /**
     * Ensures the browser is set to 100% zoom level (desktop default)
     * This method should be called at the beginning of tests to ensure consistent zoom state
     */
    public function ensureDesktop100Zoom() {
        if (!\AcceptanceConfig::ZOOM_ENFORCEMENT_ENABLED) {
            return;
        }

        $wpWebDriver = $this->getModule('WPWebDriver');
        if ($wpWebDriver === null) {
            codecept_debug('Zoom enforcement skipped: WPWebDriver module not available.');
            return;
        }

        $wpWebDriver->executeJS('
            // Reset zoom using multiple methods for maximum compatibility
            document.body.style.zoom = "1.0";
            document.body.style.transform = "scale(1.0)";
            document.documentElement.style.zoom = "1.0";
            
            // Also reset any CSS zoom that might be applied
            const allElements = document.querySelectorAll("*");
            allElements.forEach(el => {
                if (el.style.zoom && el.style.zoom !== "1" && el.style.zoom !== "1.0") {
                    el.style.zoom = "1.0";
                }
            });
        ');
        
        // Wait for zoom to settle
        $this->getModule('WPWebDriver')->wait(\AcceptanceConfig::ZOOM_RESET_DELAY / 1000);
    }

    /**
     * Sets a specific zoom level for testing
     * @param float $zoomLevel The zoom level (e.g., 0.75 for 75%, 1.5 for 150%)
     */
    public function setZoomLevel($zoomLevel) {
        $this->getModule('WPWebDriver')->executeJS("
            document.body.style.zoom = '$zoomLevel';
            document.documentElement.style.zoom = '$zoomLevel';
        ");
        
        $this->getModule('WPWebDriver')->wait(\AcceptanceConfig::ZOOM_RESET_DELAY / 1000);
    }

    /**
     * Resets zoom to default 100% level
     */
    public function resetZoom() {
        $this->ensureDesktop100Zoom();
    }

    /**
     * Verifies the current zoom level
     * @param float $expectedZoom Expected zoom level
     */
    public function verifyZoomLevel($expectedZoom) {
        $currentZoom = $this->getModule('WPWebDriver')->executeJS('
            return document.body.style.zoom ||
                   getComputedStyle(document.body).zoom ||
                   "1";
        ');
        
        // Convert to float for comparison
        $currentZoomFloat = floatval($currentZoom);
        $expectedZoomFloat = floatval($expectedZoom);
        
        if (abs($currentZoomFloat - $expectedZoomFloat) > 0.01) {
            throw new \Exception("Zoom level mismatch. Expected: $expectedZoom, Actual: $currentZoom");
        }
    }

}
