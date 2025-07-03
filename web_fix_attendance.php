<!DOCTYPE html>
<html>
<head>
    <title>Agent Attendance Table Fix</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .success { color: #28a745; }
        .error { color: #dc3545; }
        .info { color: #007bff; }
        .warning { color: #ffc107; }
        pre { background: #f8f9fa; padding: 10px; border-radius: 4px; overflow-x: auto; }
        .btn { padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .btn:hover { background: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔧 Agent Attendance Table Fix</h1>
        <p>This script will add missing columns to the agent_attendance table to fix the 500 error on the attendance page.</p>
        
        <?php
        if (isset($_POST['execute_fix'])) {
            echo "<h2>🚀 Executing Fix...</h2>";
            
            try {
                // Include Laravel bootstrap
                require_once __DIR__ . '/bootstrap/app.php';
                
                $app = require_once __DIR__ . '/bootstrap/app.php';
                $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();
                
                echo "<div class='info'>✓ Laravel bootstrap successful</div>";
                
                // Check if table exists
                if (!\Illuminate\Support\Facades\Schema::hasTable('agent_attendance')) {
                    echo "<div class='error'>❌ agent_attendance table does not exist!</div>";
                    exit;
                }
                
                echo "<div class='info'>✓ agent_attendance table found</div>";
                
                // Define columns to add
                $columnsToAdd = [
                    'login_time' => ['type' => 'timestamp', 'nullable' => true],
                    'logout_time' => ['type' => 'timestamp', 'nullable' => true],
                    'latitude' => ['type' => 'decimal', 'precision' => 10, 'scale' => 8, 'nullable' => true],
                    'longitude' => ['type' => 'decimal', 'precision' => 11, 'scale' => 8, 'nullable' => true],
                    'address' => ['type' => 'text', 'nullable' => true],
                    'logout_latitude' => ['type' => 'decimal', 'precision' => 10, 'scale' => 8, 'nullable' => true],
                    'logout_longitude' => ['type' => 'decimal', 'precision' => 11, 'scale' => 8, 'nullable' => true],
                    'logout_address' => ['type' => 'text', 'nullable' => true],
                    'status' => ['type' => 'string', 'length' => 50, 'default' => 'present']
                ];
                
                $addedColumns = [];
                $existingColumns = [];
                
                foreach ($columnsToAdd as $columnName => $config) {
                    if (!\Illuminate\Support\Facades\Schema::hasColumn('agent_attendance', $columnName)) {
                        \Illuminate\Support\Facades\Schema::table('agent_attendance', function (\Illuminate\Database\Schema\Blueprint $table) use ($columnName, $config) {
                            switch ($config['type']) {
                                case 'timestamp':
                                    $column = $table->timestamp($columnName);
                                    break;
                                case 'decimal':
                                    $column = $table->decimal($columnName, $config['precision'], $config['scale']);
                                    break;
                                case 'text':
                                    $column = $table->text($columnName);
                                    break;
                                case 'string':
                                    $column = $table->string($columnName, $config['length']);
                                    if (isset($config['default'])) {
                                        $column->default($config['default']);
                                    }
                                    break;
                            }
                            
                            if (isset($config['nullable']) && $config['nullable']) {
                                $column->nullable();
                            }
                        });
                        
                        $addedColumns[] = $columnName;
                        echo "<div class='success'>✓ Added column: {$columnName}</div>";
                    } else {
                        $existingColumns[] = $columnName;
                        echo "<div class='warning'>⚠ Column already exists: {$columnName}</div>";
                    }
                }
                
                // Add sample data if table is empty
                $recordCount = \Illuminate\Support\Facades\DB::table('agent_attendance')->count();
                
                if ($recordCount == 0) {
                    // Get first user ID
                    $firstUser = \Illuminate\Support\Facades\DB::table('users')->first();
                    
                    if ($firstUser) {
                        $sampleData = [
                            [
                                'user_id' => $firstUser->id,
                                'date' => now()->format('Y-m-d'),
                                'login_time' => now()->subHours(8),
                                'logout_time' => now()->subHour(),
                                'latitude' => 40.7128,
                                'longitude' => -74.0060,
                                'address' => 'New York, NY',
                                'logout_latitude' => 40.7580,
                                'logout_longitude' => -73.9855,
                                'logout_address' => 'Times Square, NY',
                                'status' => 'present',
                                'created_at' => now(),
                                'updated_at' => now()
                            ],
                            [
                                'user_id' => $firstUser->id,
                                'date' => now()->subDay()->format('Y-m-d'),
                                'login_time' => now()->subHours(32),
                                'logout_time' => now()->subHours(25),
                                'latitude' => 40.7128,
                                'longitude' => -74.0060,
                                'address' => 'New York, NY',
                                'logout_latitude' => 40.7580,
                                'logout_longitude' => -73.9855,
                                'logout_address' => 'Times Square, NY',
                                'status' => 'present',
                                'created_at' => now(),
                                'updated_at' => now()
                            ]
                        ];
                        
                        \Illuminate\Support\Facades\DB::table('agent_attendance')->insert($sampleData);
                        echo "<div class='success'>✓ Added " . count($sampleData) . " sample attendance records</div>";
                    } else {
                        echo "<div class='warning'>⚠ No users found, skipping sample data</div>";
                    }
                } else {
                    echo "<div class='info'>✓ Table already has {$recordCount} records</div>";
                }
                
                // Clear Laravel caches
                \Artisan::call('cache:clear');
                \Artisan::call('config:clear');
                \Artisan::call('view:clear');
                echo "<div class='success'>✓ Laravel caches cleared</div>";
                
                // Show final table structure
                $columns = \Illuminate\Support\Facades\DB::select("SHOW COLUMNS FROM agent_attendance");
                echo "<h3>📋 Updated Table Structure:</h3>";
                echo "<pre>";
                foreach ($columns as $column) {
                    echo $column->Field . " (" . $column->Type . ")" . ($column->Null == 'YES' ? ' NULL' : ' NOT NULL') . "\n";
                }
                echo "</pre>";
                
                echo "<div class='success'><h2>✅ Fix Completed Successfully!</h2></div>";
                echo "<p><strong>Next steps:</strong></p>";
                echo "<ul>";
                echo "<li>Test the attendance page: <a href='/admin/attendance' target='_blank'>/admin/attendance</a></li>";
                echo "<li>Delete this fix file for security</li>";
                echo "</ul>";
                
            } catch (Exception $e) {
                echo "<div class='error'><h2>❌ Error occurred:</h2>";
                echo "<p><strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
                echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . "</p>";
                echo "<p><strong>Line:</strong> " . $e->getLine() . "</p>";
                echo "</div>";
            }
        } else {
        ?>
            <h2>📋 What this fix will do:</h2>
            <ul>
                <li>Add missing columns: login_time, logout_time, latitude, longitude, address, logout_latitude, logout_longitude, logout_address, status</li>
                <li>Add sample attendance data if table is empty</li>
                <li>Clear Laravel caches</li>
                <li>Show updated table structure</li>
            </ul>
            
            <form method="post">
                <button type="submit" name="execute_fix" class="btn">🚀 Execute Fix</button>
            </form>
            
            <h2>🔍 Current Status</h2>
            <p><strong>Issue:</strong> Attendance page (/admin/attendance) returns 500 error</p>
            <p><strong>Cause:</strong> Missing columns in agent_attendance table</p>
            <p><strong>Solution:</strong> Add the missing columns that the model expects</p>
        <?php } ?>
    </div>
</body>
</html>
