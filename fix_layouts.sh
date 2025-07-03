#!/bin/bash

# Fix Admin Layout
echo "Fixing admin layout..."
ssh -p 65002 u806021370@46.202.161.86 "cd /home/u806021370/domains/acraltech.site/public_html && 
cat > temp_admin_fix.php << 'EOF'
<?php
\$content = file_get_contents('resources/views/layouts/admin.blade.php');

// Insert Vite directives after Tailwind config and before Alpine.js
\$search = '    </script>
    
    <!-- Alpine.js -->';
\$replace = '    </script>
    
    <!-- Vite Assets -->
    @vite([\'resources/css/app.css\', \'resources/js/app.js\'])
    
    <!-- Alpine.js -->';

\$updated = str_replace(\$search, \$replace, \$content);

if (\$updated !== \$content) {
    file_put_contents('resources/views/layouts/admin.blade.php', \$updated);
    echo \"Admin layout updated successfully!\\n\";
} else {
    echo \"No changes needed for admin layout.\\n\";
}
EOF

php temp_admin_fix.php
rm temp_admin_fix.php
"

# Fix Publisher Layout  
echo "Fixing publisher layout..."
ssh -p 65002 u806021370@46.202.161.86 "cd /home/u806021370/domains/acraltech.site/public_html && 
cat > temp_publisher_fix.php << 'EOF'
<?php
\$content = file_get_contents('resources/views/layouts/publisher.blade.php');

// Insert Vite directives after Tailwind config and before Alpine.js
\$search = '    </script>
    
    <!-- Alpine.js -->';
\$replace = '    </script>
    
    <!-- Vite Assets -->
    @vite([\'resources/css/app.css\', \'resources/js/app.js\'])
    
    <!-- Alpine.js -->';

\$updated = str_replace(\$search, \$replace, \$content);

if (\$updated !== \$content) {
    file_put_contents('resources/views/layouts/publisher.blade.php', \$updated);
    echo \"Publisher layout updated successfully!\\n\";
} else {
    echo \"No changes needed for publisher layout.\\n\";
}
EOF

php temp_publisher_fix.php
rm temp_publisher_fix.php
"

# Fix Pay Per Call Layout
echo "Fixing pay per call layout..."
ssh -p 65002 u806021370@46.202.161.86 "cd /home/u806021370/domains/acraltech.site/public_html && 
cat > temp_ppc_fix.php << 'EOF'
<?php
\$content = file_get_contents('resources/views/layouts/pay_per_call.blade.php');

// Insert Vite directives after Tailwind config and before Alpine.js
\$search = '    </script>
    
    <!-- Alpine.js -->';
\$replace = '    </script>
    
    <!-- Vite Assets -->
    @vite([\'resources/css/app.css\', \'resources/js/app.js\'])
    
    <!-- Alpine.js -->';

\$updated = str_replace(\$search, \$replace, \$content);

if (\$updated !== \$content) {
    file_put_contents('resources/views/layouts/pay_per_call.blade.php', \$updated);
    echo \"Pay per call layout updated successfully!\\n\";
} else {
    echo \"No changes needed for pay per call layout.\\n\";
}
EOF

php temp_ppc_fix.php
rm temp_ppc_fix.php
"

echo "Clearing Laravel caches..."
ssh -p 65002 u806021370@46.202.161.86 "cd /home/u806021370/domains/acraltech.site/public_html && 
php artisan view:clear
php artisan config:clear
php artisan route:clear
php artisan cache:clear
"

echo "All layouts fixed! The modern CSS should now load properly."
