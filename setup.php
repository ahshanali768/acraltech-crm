<?php
/**
 * 🚀 CRM Setup Script for Hostinger
 * Upload this file to your public_html root and visit: https://acraltech.site/setup.php
 * This will set up Laravel on your server
 */

// Security check - only run this script once
if (file_exists('.setup_completed')) {
    die('⚠️ Setup already completed! Delete .setup_completed file to run again.');
}

echo "<h2>🚀 Setting up CRM on Hostinger...</h2>";
echo "<pre>";

try {
    // Check if Laravel is properly uploaded
    if (!file_exists('vendor/autoload.php')) {
        die("❌ Error: vendor/autoload.php not found. Please upload composer dependencies.\n");
    }

    if (!file_exists('bootstrap/app.php')) {
        die("❌ Error: bootstrap/app.php not found. Please check Laravel installation.\n");
    }

    // Check .env file
    if (!file_exists('.env')) {
        if (file_exists('.env.example')) {
            copy('.env.example', '.env');
            echo "📋 Created .env from .env.example\n";
        } else {
            die("❌ Error: No .env file found. Please create .env file with your settings.\n");
        }
    }

    // Load Laravel
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';

    // Get console kernel
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

    echo "🔧 Laravel loaded successfully!\n\n";

    // 1. Generate Application Key
    echo "🔑 Generating application key...\n";
    $kernel->call('key:generate', ['--force' => true]);
    echo "✅ App key generated\n\n";

    // 2. Test database connection
    echo "🗄️ Testing database connection...\n";
    try {
        $pdo = new PDO(
            'mysql:host=localhost;dbname=u806021370_acraltech',
            'u806021370_acraltech',
            'Health@768'
        );
        echo "✅ Database connection successful\n\n";
    } catch (Exception $e) {
        echo "❌ Database connection failed: " . $e->getMessage() . "\n";
        echo "Please check your database credentials in .env file\n\n";
    }

    // 3. Run migrations
    echo "🗄️ Running database migrations...\n";
    $kernel->call('migrate', ['--force' => true]);
    echo "✅ Migrations completed\n\n";

    // 4. Cache configuration
    echo "⚙️ Caching configuration...\n";
    $kernel->call('config:cache');
    echo "✅ Config cached\n\n";

    // 5. Cache routes
    echo "🛣️ Caching routes...\n";
    $kernel->call('route:cache');
    echo "✅ Routes cached\n\n";

    // 6. Cache views
    echo "👁️ Caching views...\n";
    $kernel->call('view:cache');
    echo "✅ Views cached\n\n";

    // 7. Create storage link
    echo "🔗 Creating storage symbolic link...\n";
    $kernel->call('storage:link');
    echo "✅ Storage link created\n\n";

    // 8. Set up admin user (optional)
    echo "👤 Creating admin user...\n";
    try {
        $kernel->call('make:user', [
            'name' => 'Admin',
            'email' => 'admin@acraltech.site',
            'password' => 'admin123',
            '--admin' => true
        ]);
        echo "✅ Admin user created (admin@acraltech.site / admin123)\n";
    } catch (Exception $e) {
        echo "ℹ️ Admin user creation skipped (may already exist)\n";
    }

    // 9. Check file permissions
    echo "\n🔒 Checking file permissions...\n";
    
    $directories = ['storage', 'bootstrap/cache'];
    foreach ($directories as $dir) {
        if (is_dir($dir)) {
            chmod($dir, 0755);
            echo "✅ Set permissions for $dir\n";
        }
    }

    // 10. Mark setup as completed
    file_put_contents('.setup_completed', date('Y-m-d H:i:s'));

    echo "\n🎉 Setup completed successfully!\n\n";
    echo "📋 Next steps:\n";
    echo "1. Delete this setup.php file for security\n";
    echo "2. Visit your site: https://acraltech.site\n";
    echo "3. Admin panel: https://acraltech.site/admin\n";
    echo "4. Login with: admin@acraltech.site / admin123\n\n";
    
    echo "🔒 For security, this script will not run again.\n";

} catch (Exception $e) {
    echo "❌ Error during setup: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "</pre>";
?>

<style>
body { 
    font-family: monospace; 
    background: #1a1a1a; 
    color: #00ff00; 
    padding: 20px; 
}
h2 { color: #ffff00; }
pre { 
    background: #000; 
    padding: 15px; 
    border: 1px solid #333; 
    border-radius: 5px;
}
</style>
