<?php
/**
 * Emergency fix for allowed_locations table
 * This script creates the missing table that's causing 500 errors on the settings page
 * Upload this file to the server and run it via web browser, then delete it for security
 */

// Only allow execution if explicitly requested
if (!isset($_GET['fix_allowed_locations']) || $_GET['fix_allowed_locations'] !== 'yes') {
    die('Add ?fix_allowed_locations=yes to the URL to run this fix');
}

// Load Laravel environment
require_once __DIR__ . '/vendor/autoload.php';

// Create Laravel app instance
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

try {
    echo "<h2>🔧 Creating allowed_locations table...</h2>";
    
    // Get database connection
    $db = \Illuminate\Support\Facades\DB::connection();
    
    // Check if table already exists
    $tableExists = $db->select("SHOW TABLES LIKE 'allowed_locations'");
    
    if (!empty($tableExists)) {
        echo "<p>✅ Table 'allowed_locations' already exists!</p>";
    } else {
        // Create the table (without foreign key constraint first to avoid issues)
        $sql = "
        CREATE TABLE `allowed_locations` (
          `id` bigint unsigned NOT NULL AUTO_INCREMENT,
          `name` varchar(255) NOT NULL,
          `address` text NOT NULL,
          `latitude` decimal(10,8) NOT NULL,
          `longitude` decimal(11,8) NOT NULL,
          `radius_meters` int NOT NULL DEFAULT '1000',
          `is_active` tinyint(1) NOT NULL DEFAULT '1',
          `location_type` varchar(255) NOT NULL DEFAULT 'office',
          `notes` text,
          `created_by` bigint unsigned NOT NULL,
          `created_at` timestamp NULL DEFAULT NULL,
          `updated_at` timestamp NULL DEFAULT NULL,
          PRIMARY KEY (`id`),
          KEY `allowed_locations_name_index` (`name`),
          KEY `allowed_locations_created_by_foreign` (`created_by`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ";
        
        $db->statement($sql);
        
        // Try to add foreign key constraint (if users table exists and has proper structure)
        try {
            $db->statement("ALTER TABLE `allowed_locations` ADD CONSTRAINT `allowed_locations_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE");
            echo "<p>✓ Foreign key constraint added</p>";
        } catch (Exception $e) {
            echo "<p>⚠️ Foreign key constraint skipped (users table may need adjustment)</p>";
        }
        
        $db->statement($sql);
        echo "<p>✅ Table 'allowed_locations' created successfully!</p>";
    }
    
    // Check if we have any data
    $count = $db->table('allowed_locations')->count();
    
    if ($count === 0) {
        echo "<h3>📝 Adding sample locations...</h3>";
        
        // Get admin user ID
        $adminUser = $db->table('users')->where('role', 'admin')->first();
        $adminId = $adminUser ? $adminUser->id : 1;
        
        // Insert sample data
        $locations = [
            [
                'name' => 'Main Office',
                'address' => '123 Business Street, City, State 12345',
                'latitude' => 40.7589000,
                'longitude' => -73.9851000,
                'radius_meters' => 100,
                'is_active' => 1,
                'location_type' => 'office',
                'notes' => 'Primary office location',
                'created_by' => $adminId,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Remote Hub A',
                'address' => '456 Tech Avenue, City, State 12346',
                'latitude' => 40.7614000,
                'longitude' => -73.9776000,
                'radius_meters' => 150,
                'is_active' => 1,
                'location_type' => 'remote',
                'notes' => 'Remote work hub for team A',
                'created_by' => $adminId,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Branch Office',
                'address' => '789 Corporate Blvd, City, State 12347',
                'latitude' => 40.7505000,
                'longitude' => -73.9934000,
                'radius_meters' => 200,
                'is_active' => 1,
                'location_type' => 'branch',
                'notes' => 'Secondary branch office',
                'created_by' => $adminId,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];
        
        foreach ($locations as $location) {
            try {
                $db->table('allowed_locations')->insert($location);
                echo "<p>✓ Added location: {$location['name']}</p>";
            } catch (Exception $e) {
                echo "<p>⚠️ Failed to add location: {$location['name']} - " . htmlspecialchars($e->getMessage()) . "</p>";
            }
        }
        
        echo "<p>✅ Sample locations added successfully!</p>";
    } else {
        echo "<p>✅ Table already contains {$count} location(s)</p>";
    }
    
    // Clear Laravel cache
    echo "<h3>🧹 Clearing Laravel cache...</h3>";
    \Illuminate\Support\Facades\Artisan::call('cache:clear');
    \Illuminate\Support\Facades\Artisan::call('config:clear');
    \Illuminate\Support\Facades\Artisan::call('view:clear');
    echo "<p>✅ Cache cleared!</p>";
    
    echo "<h2>🎉 Fix completed successfully!</h2>";
    echo "<p><strong>The settings page should now work properly.</strong></p>";
    echo "<p>🌐 <a href='/admin/settings' target='_blank'>Test the settings page</a></p>";
    echo "<p>⚠️ <strong>IMPORTANT:</strong> Delete this file (fix_allowed_locations.php) for security!</p>";
    
} catch (Exception $e) {
    echo "<h2>❌ Error occurred:</h2>";
    echo "<p style='color: red;'>" . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>Please check the Laravel logs for more details.</p>";
}
?>
