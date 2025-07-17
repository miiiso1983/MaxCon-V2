<?php
/**
 * Script to fix accounting routes in views
 * Run: php fix-accounting-routes.php
 */

echo "🔧 Fixing accounting routes in views...\n";

// Define the mapping of old routes to new routes
$routeMapping = [
    'tenant.accounting.chart-of-accounts' => 'tenant.inventory.accounting.chart-of-accounts',
    'tenant.accounting.cost-centers' => 'tenant.inventory.accounting.cost-centers',
    'tenant.accounting.journal-entries' => 'tenant.inventory.accounting.journal-entries',
    'tenant.accounting.reports' => 'tenant.inventory.accounting.reports',
];

// Define the directories to search
$directories = [
    'resources/views/tenant/accounting/chart-of-accounts',
    'resources/views/tenant/accounting/cost-centers',
    'resources/views/tenant/accounting/journal-entries',
    'resources/views/tenant/accounting/reports',
];

$totalFiles = 0;
$totalReplacements = 0;

foreach ($directories as $directory) {
    if (!is_dir($directory)) {
        echo "❌ Directory not found: $directory\n";
        continue;
    }
    
    $files = glob($directory . '/*.blade.php');
    
    foreach ($files as $file) {
        echo "📄 Processing: $file\n";
        $content = file_get_contents($file);
        $originalContent = $content;
        $fileReplacements = 0;
        
        // Replace each route mapping
        foreach ($routeMapping as $oldRoute => $newRoute) {
            $pattern = '/route\(\s*[\'"]' . preg_quote($oldRoute, '/') . '([^\'"]*)[\'"]\s*([^)]*)\)/';
            $replacement = "route('$newRoute$1'$2)";
            
            $newContent = preg_replace($pattern, $replacement, $content);
            if ($newContent !== $content) {
                $matches = preg_match_all($pattern, $content);
                $fileReplacements += $matches;
                $content = $newContent;
            }
        }
        
        // Write back if changes were made
        if ($content !== $originalContent) {
            file_put_contents($file, $content);
            echo "  ✅ Updated with $fileReplacements replacements\n";
            $totalReplacements += $fileReplacements;
        } else {
            echo "  ℹ️  No changes needed\n";
        }
        
        $totalFiles++;
    }
}

echo "\n🎉 Route fixing completed!\n";
echo "📊 Summary:\n";
echo "  - Files processed: $totalFiles\n";
echo "  - Total replacements: $totalReplacements\n";
echo "\n✅ All accounting routes should now work correctly!\n";
?>
