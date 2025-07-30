<?php
namespace Helper;

/**
 * Acceptance Helper
 *
 * @method \Codeception\Module\WPWebDriver getModule(string $name) Get a module instance
 */
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

    public function cUrlWP_SiteToCreatePost($post_title, $post_content){
        // Read configuration from JSON file
        $config_file = __DIR__ . '/../../../localhost_wordpress_api_config.json';
        if (!file_exists($config_file)) {
            throw new \Exception("Configuration file not found: $config_file");
        }
        
        $config = json_decode(file_get_contents($config_file), true);
        if (!$config) {
            throw new \Exception("Failed to parse configuration file");
        }
        
        // Prepare the API endpoint
        $api_url = rtrim($config['site'], '/') . '/wp-json/wp/v2/posts';
        
        // Prepare post data
        $post_data = [
            'title' => $post_title,
            'content' => $post_content,
            'status' => 'publish'
        ];
        
        // Initialize cURL
        $ch = curl_init();
        
        // Set cURL options
        curl_setopt_array($ch, [
            CURLOPT_URL => $api_url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($post_data),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Basic ' . base64_encode($config['username'] . ':' . $config['application_password'])
            ],
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false, // For localhost testing
            CURLOPT_SSL_VERIFYHOST => false  // For localhost testing
        ]);
        
        // Execute the request
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($ch);
        
        curl_close($ch);
        
        // Check for cURL errors
        if ($curl_error) {
            throw new \Exception("cURL error: $curl_error");
        }
        
        // Check HTTP response code
        if ($http_code !== 201) {
            throw new \Exception("HTTP error $http_code. Response: $response");
        }
        
        // Parse response
        $response_data = json_decode($response, true);
        if (!$response_data || !isset($response_data['id'])) {
            throw new \Exception("Invalid response format: $response");
        }
        
        $post_id = $response_data['id'];

        return $post_id;
    }

    public function cUrlWP_SiteToDeletePost($post_id){
        // Read configuration from JSON file
        $config_file = __DIR__ . '/../../../localhost_wordpress_api_config.json';
        if (!file_exists($config_file)) {
            throw new \Exception("Configuration file not found: $config_file");
        }
        
        $config = json_decode(file_get_contents($config_file), true);
        if (!$config) {
            throw new \Exception("Failed to parse configuration file");
        }
        
        // Prepare the API endpoint for deleting a specific post with force delete
        $api_url = rtrim($config['site'], '/') . '/wp-json/wp/v2/posts/' . $post_id . '?force=true';
        
        // Initialize cURL
        $ch = curl_init();
        
        // Set cURL options for DELETE request
        curl_setopt_array($ch, [
            CURLOPT_URL => $api_url,
            CURLOPT_CUSTOMREQUEST => 'DELETE',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Basic ' . base64_encode($config['username'] . ':' . $config['application_password'])
            ],
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false, // For localhost testing
            CURLOPT_SSL_VERIFYHOST => false  // For localhost testing
        ]);
        
        // Execute the request
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($ch);
        
        curl_close($ch);
        
        // Check for cURL errors
        if ($curl_error) {
            throw new \Exception("cURL error: $curl_error");
        }
        
        // Check HTTP response code (200 for successful deletion with response data, 204 for no content)
        if ($http_code !== 200 && $http_code !== 204) {
            throw new \Exception("HTTP error $http_code. Response: $response");
        }
        
        // Parse response if there is content
        if ($http_code === 200 && !empty($response)) {
            $response_data = json_decode($response, true);
            if (!$response_data) {
                throw new \Exception("Invalid response format: $response");
            }
            return $response_data;
        }
        
        // Return true for successful deletion with no content (204)
        return true;
    }

    /**
     * Get category ID by slug, create if it doesn't exist
     * @param string $category_slug The category slug
     * @param string $category_name The category name (optional, defaults to slug)
     * @return int The category ID
     */
    public function cUrlWP_SiteToGetOrCreateCategory($category_slug, $category_name = null) {
        if ($category_name === null) {
            $category_name = ucfirst($category_slug);
        }
        
        // Read configuration from JSON file
        $config_file = __DIR__ . '/../../../localhost_wordpress_api_config.json';
        if (!file_exists($config_file)) {
            throw new \Exception("Configuration file not found: $config_file");
        }
        
        $config = json_decode(file_get_contents($config_file), true);
        if (!$config) {
            throw new \Exception("Failed to parse configuration file");
        }
        
        // First, try to get existing category by slug
        $api_url = rtrim($config['site'], '/') . '/wp-json/wp/v2/categories?slug=' . urlencode($category_slug);
        
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $api_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Basic ' . base64_encode($config['username'] . ':' . $config['application_password'])
            ],
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false
        ]);
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($ch);
        curl_close($ch);
        
        if ($curl_error) {
            throw new \Exception("cURL error: $curl_error");
        }
        
        if ($http_code === 200) {
            $categories = json_decode($response, true);
            if (!empty($categories) && isset($categories[0]['id'])) {
                return $categories[0]['id'];
            }
        }
        
        // Category doesn't exist, create it
        $api_url = rtrim($config['site'], '/') . '/wp-json/wp/v2/categories';
        $category_data = [
            'name' => $category_name,
            'slug' => $category_slug
        ];
        
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $api_url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($category_data),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Basic ' . base64_encode($config['username'] . ':' . $config['application_password'])
            ],
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false
        ]);
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($ch);
        curl_close($ch);
        
        if ($curl_error) {
            throw new \Exception("cURL error: $curl_error");
        }
        
        if ($http_code !== 201) {
            throw new \Exception("HTTP error $http_code creating category. Response: $response");
        }
        
        $response_data = json_decode($response, true);
        if (!$response_data || !isset($response_data['id'])) {
            throw new \Exception("Invalid response format: $response");
        }
        
        return $response_data['id'];
    }

    /**
     * Create a post with categories
     * @param string $post_title The post title
     * @param string $post_content The post content
     * @param array $category_ids Array of category IDs to assign to the post
     * @return int The created post ID
     */
    public function cUrlWP_SiteToCreatePostWithCategories($post_title, $post_content, $category_ids = []) {
        // Read configuration from JSON file
        $config_file = __DIR__ . '/../../../localhost_wordpress_api_config.json';
        if (!file_exists($config_file)) {
            throw new \Exception("Configuration file not found: $config_file");
        }
        
        $config = json_decode(file_get_contents($config_file), true);
        if (!$config) {
            throw new \Exception("Failed to parse configuration file");
        }
        
        // Prepare the API endpoint
        $api_url = rtrim($config['site'], '/') . '/wp-json/wp/v2/posts';
        
        // Prepare post data
        $post_data = [
            'title' => $post_title,
            'content' => $post_content,
            'status' => 'publish'
        ];
        
        // Add categories if provided
        if (!empty($category_ids)) {
            $post_data['categories'] = $category_ids;
        }
        
        // Initialize cURL
        $ch = curl_init();
        
        // Set cURL options
        curl_setopt_array($ch, [
            CURLOPT_URL => $api_url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($post_data),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Basic ' . base64_encode($config['username'] . ':' . $config['application_password'])
            ],
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false, // For localhost testing
            CURLOPT_SSL_VERIFYHOST => false  // For localhost testing
        ]);
        
        // Execute the request
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($ch);
        
        curl_close($ch);
        
        // Check for cURL errors
        if ($curl_error) {
            throw new \Exception("cURL error: $curl_error");
        }
        
        // Check HTTP response code
        if ($http_code !== 201) {
            throw new \Exception("HTTP error $http_code. Response: $response");
        }
        
        // Parse response
        $response_data = json_decode($response, true);
        if (!$response_data || !isset($response_data['id'])) {
            throw new \Exception("Invalid response format: $response");
        }
        
        $post_id = $response_data['id'];

        return $post_id;
    }

    /**
     * Add an approved comment to a post via WordPress REST API
     * @param int $postID The ID of the post to comment on
     * @param array $commentData Array containing comment data (content, author_name, author_email, etc.)
     * @return int The created comment ID
     */
    public function cUrlWP_SiteToAddComment($postID, $commentData) {
        // Read configuration from JSON file
        $config_file = __DIR__ . '/../../../localhost_wordpress_api_config.json';
        if (!file_exists($config_file)) {
            throw new \Exception("Configuration file not found: $config_file");
        }
        
        $config = json_decode(file_get_contents($config_file), true);
        if (!$config) {
            throw new \Exception("Failed to parse configuration file");
        }
        
        // Prepare the API endpoint
        $api_url = rtrim($config['site'], '/') . '/wp-json/wp/v2/comments';
        
        // Prepare comment data with required fields
        $comment_data = [
            'post' => $postID,
            'content' => $commentData['content'] ?? '',
            'status' => 'approved' // Set comment as approved
        ];
        
        // Add optional fields if provided
        if (isset($commentData['author_name'])) {
            $comment_data['author_name'] = $commentData['author_name'];
        }
        
        if (isset($commentData['author_email'])) {
            $comment_data['author_email'] = $commentData['author_email'];
        }
        
        if (isset($commentData['author_url'])) {
            $comment_data['author_url'] = $commentData['author_url'];
        }
        
        if (isset($commentData['parent'])) {
            $comment_data['parent'] = $commentData['parent'];
        }
        
        // Initialize cURL
        $ch = curl_init();
        
        // Set cURL options
        curl_setopt_array($ch, [
            CURLOPT_URL => $api_url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($comment_data),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Basic ' . base64_encode($config['username'] . ':' . $config['application_password'])
            ],
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false, // For localhost testing
            CURLOPT_SSL_VERIFYHOST => false  // For localhost testing
        ]);
        
        // Execute the request
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($ch);
        
        curl_close($ch);
        
        // Check for cURL errors
        if ($curl_error) {
            throw new \Exception("cURL error: $curl_error");
        }
        
        // Check HTTP response code
        if ($http_code !== 201) {
            throw new \Exception("HTTP error $http_code. Response: $response");
        }
        
        // Parse response
        $response_data = json_decode($response, true);
        if (!$response_data || !isset($response_data['id'])) {
            throw new \Exception("Invalid response format: $response");
        }
        
        $comment_id = $response_data['id'];

        return $comment_id;
    }

}
