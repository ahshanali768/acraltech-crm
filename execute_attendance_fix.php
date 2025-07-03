<?php
/**
 * Attendance Table Fix Executor
 * This script reads and executes the SQL fix for the agent_attendance table
 */

echo "<h2>🔧 Attendance Table Fix Executor</h2>";

// Check if we're in the right directory
if (!file_exists('bootstrap/app.php')) {
    echo "<p style='color: red;'>❌ Error: This script must be run from the Laravel root directory.</p>";
    echo "<p>Current directory: " . getcwd() . "</p>";
    exit;
}

try {
    // Bootstrap Laravel
    require_once 'bootstrap/app.php';
    $app = require_once 'bootstrap/app.php';
    $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();
    
    echo "<p style='color: green;'>✅ Laravel bootstrapped successfully</p>";
    
    // Read the SQL fix file
    $sqlFile = 'copy_paste_fix.sql';
    if (!file_exists($sqlFile)) {
        echo "<p style='color: red;'>❌ SQL fix file not found: $sqlFile</p>";
        exit;
    }
    
    $sql = file_get_contents($sqlFile);
    echo "<p style='color: blue;'>📄 SQL fix file loaded: $sqlFile</p>";
    
    // Split SQL into individual statements
    $statements = array_filter(array_map('trim', explode(';', $sql)));
    
    echo "<h3>🚀 Executing SQL Statements:</h3>";
    echo "<div style='background: #f5f5f5; padding: 10px; margin: 10px 0; border-radius: 5px;'>";
    
    foreach ($statements as $statement) {
        if (empty($statement) || strpos($statement, '--') === 0) {
            continue;
        }
        
        try {
            echo "<p><strong>Executing:</strong> " . substr($statement, 0, 80) . "...</p>";
            
            $result = \Illuminate\Support\Facades\DB::statement($statement);
            
            if ($result) {
                echo "<p style='color: green;'>✅ Success</p>";
            } else {
                echo "<p style='color: orange;'>⚠️ No result returned</p>";
            }
            
        } catch (Exception $e) {
            echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
        }
    }
    
    echo "</div>";
    
    // Check the table structure
    echo "<h3>📋 Current agent_attendance Table Structure:</h3>";
    try {
        $columns = \Illuminate\Support\Facades\DB::select("SHOW COLUMNS FROM agent_attendance");
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Default</th></tr>";
        foreach ($columns as $column) {
            echo "<tr>";
            echo "<td>" . $column->Field . "</td>";
            echo "<td>" . $column->Type . "</td>";
            echo "<td>" . $column->Null . "</td>";
            echo "<td>" . ($column->Default ?? 'NULL') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ Could not fetch table structure: " . $e->getMessage() . "</p>";
    }
    
    // Check record count
    echo "<h3>📊 Attendance Records:</h3>";
    try {
        $count = \Illuminate\Support\Facades\DB::table('agent_attendance')->count();
        echo "<p><strong>Total attendance records:</strong> $count</p>";
        
        if ($count > 0) {
            $recent = \Illuminate\Support\Facades\DB::table('agent_attendance')
                ->orderBy('created_at', 'desc')
                ->limit(3)
                ->get();
            
            echo "<table border='1' style='border-collapse: collapse; width: 100%;'>";
            echo "<tr><th>ID</th><th>User ID</th><th>Date</th><th>Login Time</th><th>Status</th></tr>";
            foreach ($recent as $record) {
                echo "<tr>";
                echo "<td>" . ($record->id ?? 'N/A') . "</td>";
                echo "<td>" . $record->user_id . "</td>";
                echo "<td>" . $record->date . "</td>";
                echo "<td>" . ($record->login_time ?? 'N/A') . "</td>";
                echo "<td>" . ($record->status ?? 'N/A') . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ Could not fetch attendance records: " . $e->getMessage() . "</p>";
    }
    
    // Clear Laravel caches
    echo "<h3>🧹 Clearing Laravel Caches:</h3>";
    try {
        \Artisan::call('cache:clear');
        echo "<p style='color: green;'>✅ Cache cleared</p>";
        
        \Artisan::call('config:clear');
        echo "<p style='color: green;'>✅ Config cache cleared</p>";
        
        \Artisan::call('view:clear');
        echo "<p style='color: green;'>✅ View cache cleared</p>";
        
    } catch (Exception $e) {
        echo "<p style='color: red;'>❌ Cache clear error: " . $e->getMessage() . "</p>";
    }
    
    echo "<h2 style='color: green;'>🎉 Attendance Fix Completed!</h2>";
    echo "<p><strong>Next Steps:</strong></p>";
    echo "<ul>";
    echo "<li>Test the attendance page: <a href='/admin/attendance' target='_blank'>/admin/attendance</a></li>";
    echo "<li>Login as admin if redirected</li>";
    echo "<li>Verify the page loads without 500 errors</li>";
    echo "<li>Check that attendance records are displayed</li>";
    echo "<li><strong>Delete this script file for security!</strong></li>";
    echo "</ul>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Fatal Error: " . $e->getMessage() . "</p>";
    echo "<p><strong>File:</strong> " . $e->getFile() . "</p>";
    echo "<p><strong>Line:</strong> " . $e->getLine() . "</p>";
}
?>
