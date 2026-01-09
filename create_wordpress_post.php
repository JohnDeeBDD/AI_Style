<?php

/**
 * WordPress Post Creation Script
 * 
 * This script reads configuration from localhost_wordpress_api_config.json
 * and creates a new post using the WordPress REST API via cURL.
 */

// Read configuration from JSON file
$configFile = 'localhost_wordpress_api_config.json';

if (!file_exists($configFile)) {
    die("Error: Configuration file '{$configFile}' not found.\n");
}

$configContent = file_get_contents($configFile);
$config = json_decode($configContent, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    die("Error: Invalid JSON in configuration file: " . json_last_error_msg() . "\n");
}

// Validate required configuration fields
$requiredFields = ['site', 'username', 'application_password'];
foreach ($requiredFields as $field) {
    if (!isset($config[$field]) || empty($config[$field])) {
        die("Error: Missing required field '{$field}' in configuration file.\n");
    }
}

/**
 * Function to make cURL requests
 */
function makeApiRequest($url, $config, $method = 'GET', $data = null) {
    $curl = curl_init();
    
    $curlOptions = [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Authorization: Basic ' . base64_encode($config['username'] . ':' . $config['application_password'])
        ],
        CURLOPT_TIMEOUT => 30,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_SSL_VERIFYPEER => false, // For localhost development
        CURLOPT_VERBOSE => false
    ];
    
    if ($method === 'POST') {
        $curlOptions[CURLOPT_POST] = true;
        if ($data) {
            $curlOptions[CURLOPT_POSTFIELDS] = json_encode($data);
        }
    }
    
    curl_setopt_array($curl, $curlOptions);
    
    $response = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $error = curl_error($curl);
    
    curl_close($curl);
    
    if ($error) {
        die("cURL Error: {$error}\n");
    }
    
    return [
        'response' => $response,
        'http_code' => $httpCode,
        'data' => json_decode($response, true)
    ];
}

// Base API URL
$baseApiUrl = rtrim($config['site'], '/') . '/wp-json/wp/v2';

// Step 1: Check if "TEST" category exists
echo "🔍 Checking if 'TEST' category exists...\n";
$categoriesUrl = $baseApiUrl . '/categories?search=TEST';
$categoryResult = makeApiRequest($categoriesUrl, $config);

$testCategoryId = null;

if ($categoryResult['http_code'] >= 200 && $categoryResult['http_code'] < 300) {
    $categories = $categoryResult['data'];
    
    // Look for exact match
    foreach ($categories as $category) {
        if (strtoupper($category['name']) === 'TEST') {
            $testCategoryId = $category['id'];
            echo "✅ Found existing 'TEST' category with ID: {$testCategoryId}\n";
            break;
        }
    }
}

// Step 2: Create "TEST" category if it doesn't exist
if ($testCategoryId === null) {
    echo "📝 Creating 'TEST' category...\n";
    $createCategoryUrl = $baseApiUrl . '/categories';
    $categoryData = [
        'name' => 'TEST',
        'description' => 'Test category created by script'
    ];
    
    $createResult = makeApiRequest($createCategoryUrl, $config, 'POST', $categoryData);
    
    if ($createResult['http_code'] >= 200 && $createResult['http_code'] < 300) {
        $testCategoryId = $createResult['data']['id'];
        echo "✅ Created 'TEST' category with ID: {$testCategoryId}\n";
    } else {
        echo "❌ Failed to create 'TEST' category.\n";
        echo "HTTP Status Code: {$createResult['http_code']}\n";
        echo "Response: {$createResult['response']}\n";
        die("Cannot proceed without category.\n");
    }
}

// Step 3: Create multiple posts with the TEST category
$numberOfPosts = 50;
echo "\n📝 Creating {$numberOfPosts} posts...\n";
echo "Assigning to category: TEST (ID: {$testCategoryId})\n\n";

$postsUrl = $baseApiUrl . '/posts';
$successCount = 0;
$errorCount = 0;

for ($i = 1; $i <= $numberOfPosts; $i++) {
    echo "Creating post {$i}/{$numberOfPosts}... ";
    
    $postData = [
        'title' => "TEST POST #{$i}",
        'content' => "LORUM IPSUM - This is test post number {$i} created by the automated script.",
        'status' => 'publish',
        'categories' => [$testCategoryId]
    ];

    $postResult = makeApiRequest($postsUrl, $config, 'POST', $postData);

    // Handle the response
    if ($postResult['http_code'] >= 200 && $postResult['http_code'] < 300) {
        $responseData = $postResult['data'];
        $successCount++;
        
        if (isset($responseData['id'])) {
            echo "✅ Success! Post ID: {$responseData['id']}\n";
        } else {
            echo "✅ Success!\n";
        }
        
    } else {
        $errorCount++;
        echo "❌ Error (HTTP {$postResult['http_code']})\n";
        
        $responseData = $postResult['data'];
        if ($responseData && isset($responseData['message'])) {
            echo "   Error Message: {$responseData['message']}\n";
        }
    }
    
    // Add a small delay to avoid overwhelming the server
    usleep(100000); // 0.1 second delay
}

echo "\n" . str_repeat("=", 50) . "\n";
echo "📊 SUMMARY:\n";
echo "✅ Successfully created: {$successCount} posts\n";
echo "❌ Failed to create: {$errorCount} posts\n";
echo "📝 Total attempted: {$numberOfPosts} posts\n";
echo str_repeat("=", 50) . "\n";

?>