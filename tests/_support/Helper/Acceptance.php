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
    }
    
    public function _before(\Codeception\TestInterface $test) {

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
     * Get application password credentials from CacbotTester
     * @param string $username The username to get credentials for (default: "Codeception")
     * @param string $site_url The site URL (default: "http://localhost")
     * @return array Array with 'username', 'password', and 'site' keys
     */
    private function getCacbotCredentials($username = 'Codeception', $site_url = 'http://localhost') {
        // Prepare the CacbotTester API endpoint
        $api_url = rtrim($site_url, '/') . '/wp-json/cacbot-tester/v1/app-password?username=' . urlencode($username);
        
        // Initialize cURL to get credentials
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $api_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false, // For localhost testing
            CURLOPT_SSL_VERIFYHOST => false,  // For localhost testing
            CURLOPT_TIMEOUT => 30
        ]);
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($ch);
        curl_close($ch);
        
        if ($curl_error) {
            throw new \Exception("CacbotTester cURL error: $curl_error");
        }
        
        if ($http_code !== 200) {
            throw new \Exception("CacbotTester HTTP error $http_code. Response: $response");
        }
        
        $response_data = json_decode($response, true);
        if (!$response_data || !$response_data['ok']) {
            throw new \Exception("CacbotTester API error: " . ($response_data['message'] ?? 'Unknown error'));
        }
        
        return [
            'username' => $response_data['username'],
            'password' => $response_data['application_password'],
            'site' => $site_url
        ];
    }

    public function cUrlWP_SiteToCreatePost($post_title, $post_content){
        // Get dynamic credentials from CacbotTester
        $credentials = $this->getCacbotCredentials();
        
        // Prepare the API endpoint
        $api_url = rtrim($credentials['site'], '/') . '/wp-json/wp/v2/posts';
        
        // Prepare post data
        $post_data = [
            'title' => $post_title,
            'content' => $post_content,
            'status' => 'publish',
            'comment_status' => 'open'  // Enable comments on the post
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
                'Authorization: Basic ' . base64_encode($credentials['username'] . ':' . $credentials['password'])
            ],
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
        sleep(2);
        return $post_id;
    }

    public function cUrlWP_SiteToDeletePost($post_id){
        // Get dynamic credentials from CacbotTester
        $credentials = $this->getCacbotCredentials();
        
        // Prepare the API endpoint for deleting a specific post with force delete
        $api_url = rtrim($credentials['site'], '/') . '/wp-json/wp/v2/posts/' . $post_id . '?force=true';
        
        // Initialize cURL
        $ch = curl_init();
        
        // Set cURL options for DELETE request
        curl_setopt_array($ch, [
            CURLOPT_URL => $api_url,
            CURLOPT_CUSTOMREQUEST => 'DELETE',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Basic ' . base64_encode($credentials['username'] . ':' . $credentials['password'])
            ],
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
        
        // Get dynamic credentials from CacbotTester
        $credentials = $this->getCacbotCredentials();
        
        // First, try to get existing category by slug
        $api_url = rtrim($credentials['site'], '/') . '/wp-json/wp/v2/categories?slug=' . urlencode($category_slug);
        
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $api_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Basic ' . base64_encode($credentials['username'] . ':' . $credentials['password'])
            ],
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
        $api_url = rtrim($credentials['site'], '/') . '/wp-json/wp/v2/categories';
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
                'Authorization: Basic ' . base64_encode($credentials['username'] . ':' . $credentials['password'])
            ],
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
        // Get dynamic credentials from CacbotTester
        $credentials = $this->getCacbotCredentials();
        
        // Prepare the API endpoint
        $api_url = rtrim($credentials['site'], '/') . '/wp-json/wp/v2/posts';
        
        // Prepare post data
        $post_data = [
            'title' => $post_title,
            'content' => $post_content,
            'status' => 'publish',
            'comment_status' => 'open'  // Enable comments on the post
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
                'Authorization: Basic ' . base64_encode($credentials['username'] . ':' . $credentials['password'])
            ],
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
        // Get dynamic credentials from CacbotTester
        $credentials = $this->getCacbotCredentials();
        
        // Prepare the API endpoint
        $api_url = rtrim($credentials['site'], '/') . '/wp-json/wp/v2/comments';
        
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
        
        // Add user_id if provided (for testing different user types)
        if (isset($commentData['user_id'])) {
            $comment_data['author'] = $commentData['user_id'];
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
                'Authorization: Basic ' . base64_encode($credentials['username'] . ':' . $credentials['password'])
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
        sleep(2);
        return $comment_id;
    }

    /**
     * Determine if we're in mobile breakpoint (window width < 782px)
     * @return bool
     */
    public function isMobileBreakpoint() {
        try {
            $windowWidth = $this->getModule('WPWebDriver')->executeJS("return window.innerWidth;");
            codecept_debug("Window width: {$windowWidth}px");
            return $windowWidth < 782;
        } catch (\Exception $e) {
            codecept_debug("Failed to detect window width, assuming desktop: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Fetch application password for a user from CacbotTester API
     * @param string $username The username to fetch the application password for
     * @param bool $reset Whether to reset/regenerate the password (optional)
     * @return array The response data containing application password info
     */
    public function fetchAppPasswordFromCacbotTester($username, $reset = false) {
        // Prepare the API endpoint
        $api_url = \AcceptanceConfig::BASE_URL . '/wp-json/cacbot-tester/v1/app-password?username=' . urlencode($username);
        
        if ($reset) {
            $api_url .= '&reset=true';
        }
        
        // Initialize cURL
        $ch = curl_init();
        
        // Set cURL options for GET request
        curl_setopt_array($ch, [
            CURLOPT_URL => $api_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json'
            ],
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
        if ($http_code !== 200) {
            throw new \Exception("HTTP error $http_code. Response: $response");
        }
        
        // Parse response
        $response_data = json_decode($response, true);
        if (!$response_data) {
            throw new \Exception("Invalid response format: $response");
        }
        
        // Check if the API returned an error
        if (isset($response_data['ok']) && $response_data['ok'] === false) {
            throw new \Exception("API error: " . ($response_data['message'] ?? 'Unknown error'));
        }
        
        return $response_data;
    }


    public function cUrlWP_SiteToSetCacbotMeta($url, $username, $app_password, $post_id, $key, $data) {
/*
EXAMPLE:
    ─$ curl -X POST "http://localhost/wp-json/cacbot/v1/meta-data" \
  -u "Codeception:VhRgaijDzLl2pZkZBMrpHMXL" \
  -H "Content-Type: application/json" \
  -d '{
    "post_id": 720,
    "key": "_cacbot_interlocutor_user_id",
    "value": "1"
  }'
*/
        
        // Prepare the API endpoint
        $api_url = rtrim($url, '/') . '/wp-json/cacbot/v1/meta-data';
        
        // Prepare meta data
        $meta_data = [
            'post_id' => $post_id,
            'key' => $key,
            'value' => $data
        ];
        
        // Initialize cURL
        $ch = curl_init();
        
        // Set cURL options
        curl_setopt_array($ch, [
            CURLOPT_URL => $api_url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($meta_data),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Basic ' . base64_encode($username . ':' . $app_password)
            ],
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
        
        // Check HTTP response code (200 for successful update, 201 for creation)
        if ($http_code !== 200 && $http_code !== 201) {
            throw new \Exception("HTTP error $http_code. Response: $response");
        }
        
        // Parse response if there is content
        if (!empty($response)) {
            $response_data = json_decode($response, true);
            if ($response_data === null) {
                throw new \Exception("Invalid response format: $response");
            }
            return $response_data;
        }
        
        // Return true for successful operation with no content
        return true;
    }

}

