<?php
/**
 * Symfony Error Handler Debug Script
 * يساعد في تشخيص مشكلة highlight_file function
 */

echo "<h1>Symfony Error Handler Debug</h1>";
echo "<style>body{font-family:Arial;margin:20px;} .ok{color:green;} .error{color:red;} .warning{color:orange;}</style>";

// Check PHP version
echo "<h2>PHP Information</h2>";
echo "<p>PHP Version: <strong>" . phpversion() . "</strong></p>";

// Check if highlight_file function exists
echo "<h2>Function Availability</h2>";
if (function_exists('highlight_file')) {
    echo "<p class='ok'>✅ highlight_file function is available</p>";
} else {
    echo "<p class='error'>❌ highlight_file function is NOT available</p>";
    echo "<p class='warning'>This is the cause of the Symfony error!</p>";
}

if (function_exists('highlight_string')) {
    echo "<p class='ok'>✅ highlight_string function is available</p>";
} else {
    echo "<p class='error'>❌ highlight_string function is NOT available</p>";
}

// Check PHP extensions
echo "<h2>PHP Extensions</h2>";
$required_extensions = ['tokenizer', 'ctype', 'json', 'mbstring'];
foreach ($required_extensions as $ext) {
    if (extension_loaded($ext)) {
        echo "<p class='ok'>✅ {$ext} extension is loaded</p>";
    } else {
        echo "<p class='error'>❌ {$ext} extension is NOT loaded</p>";
    }
}

// Check if we're in safe mode
echo "<h2>PHP Configuration</h2>";
if (ini_get('safe_mode')) {
    echo "<p class='error'>❌ PHP Safe Mode is enabled (this can cause issues)</p>";
} else {
    echo "<p class='ok'>✅ PHP Safe Mode is disabled</p>";
}

// Check disabled functions
$disabled = ini_get('disable_functions');
if ($disabled) {
    echo "<p class='warning'>⚠️ Disabled functions: " . $disabled . "</p>";
    if (strpos($disabled, 'highlight_file') !== false) {
        echo "<p class='error'>❌ highlight_file is disabled in PHP configuration!</p>";
    }
} else {
    echo "<p class='ok'>✅ No functions are disabled</p>";
}

// Check Laravel environment
echo "<h2>Laravel Environment</h2>";
if (file_exists('../.env')) {
    echo "<p class='ok'>✅ .env file exists</p>";
    
    $env_content = file_get_contents('../.env');
    if (strpos($env_content, 'APP_DEBUG=true') !== false) {
        echo "<p class='warning'>⚠️ APP_DEBUG is set to true</p>";
        echo "<p>This enables detailed error reporting which requires highlight_file</p>";
    } else {
        echo "<p class='ok'>✅ APP_DEBUG is not set to true</p>";
    }
} else {
    echo "<p class='error'>❌ .env file not found</p>";
}

// Check Symfony version
echo "<h2>Symfony Components</h2>";
if (file_exists('../vendor/symfony/error-handler/composer.json')) {
    $composer = json_decode(file_get_contents('../vendor/symfony/error-handler/composer.json'), true);
    echo "<p>Symfony Error Handler Version: <strong>" . ($composer['version'] ?? 'Unknown') . "</strong></p>";
} else {
    echo "<p class='error'>❌ Symfony Error Handler not found</p>";
}

// Recommendations
echo "<h2>Recommendations</h2>";
echo "<div style='background:#f0f0f0;padding:15px;border-radius:5px;'>";

if (!function_exists('highlight_file')) {
    echo "<h3 class='error'>Critical Issue: highlight_file function missing</h3>";
    echo "<p><strong>Solutions:</strong></p>";
    echo "<ul>";
    echo "<li>Contact your hosting provider to enable the highlight_file function</li>";
    echo "<li>Check if PHP tokenizer extension is installed</li>";
    echo "<li>Verify that highlight_file is not in the disabled_functions list</li>";
    echo "<li>As a temporary fix, set APP_DEBUG=false in .env</li>";
    echo "</ul>";
    
    echo "<h3>Temporary Fix Commands:</h3>";
    echo "<pre style='background:#333;color:#fff;padding:10px;'>";
    echo "# Option 1: Disable debug mode\n";
    echo "sed -i 's/APP_DEBUG=true/APP_DEBUG=false/' .env\n\n";
    echo "# Option 2: Clear all caches\n";
    echo "php artisan config:clear\n";
    echo "php artisan route:clear\n";
    echo "php artisan view:clear\n";
    echo "php artisan cache:clear\n";
    echo "</pre>";
} else {
    echo "<h3 class='ok'>No critical issues found</h3>";
    echo "<p>The highlight_file function is available. The error might be intermittent.</p>";
    echo "<p>Try clearing the caches:</p>";
    echo "<pre style='background:#333;color:#fff;padding:10px;'>";
    echo "php artisan optimize:clear\n";
    echo "php artisan config:cache\n";
    echo "</pre>";
}

echo "</div>";

// Test highlight_file if available
if (function_exists('highlight_file')) {
    echo "<h2>Function Test</h2>";
    echo "<p>Testing highlight_file function:</p>";
    echo "<div style='border:1px solid #ccc;padding:10px;'>";
    
    // Create a simple test file
    $test_file = 'test_highlight.php';
    file_put_contents($test_file, "<?php\necho 'Hello World';\n");
    
    try {
        ob_start();
        highlight_file($test_file);
        $result = ob_get_clean();
        
        if ($result) {
            echo "<p class='ok'>✅ highlight_file function works correctly</p>";
            echo $result;
        } else {
            echo "<p class='error'>❌ highlight_file function returned empty result</p>";
        }
    } catch (Exception $e) {
        echo "<p class='error'>❌ Error testing highlight_file: " . $e->getMessage() . "</p>";
    } finally {
        if (file_exists($test_file)) {
            unlink($test_file);
        }
    }
    
    echo "</div>";
}

echo "<hr>";
echo "<p><small>Generated at: " . date('Y-m-d H:i:s') . "</small></p>";
echo "<p><small>Run this script by visiting: <a href='/debug-symfony.php'>/debug-symfony.php</a></small></p>";
?>
