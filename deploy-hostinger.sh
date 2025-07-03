#!/bin/bash

# 🚀 Hostinger CRM Deployment Script
# Author: GitHub Copilot
# Date: $(date)
# Server: acraltech.site (Hostinger)

set -e  # Exit on any error

# Colors for output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Configuration
DOMAIN="acraltech.site"
USER="u806021370"
HOST="46.202.161.86"
PORT="65002"
DOMAIN_PATH="/home/u806021370/domains/acraltech.site/public_html"
SOURCE_PATH="/home/u806021370/crm-source"
GIT_REPO="https://github.com/yourusername/your-crm-repo.git"  # Update this with your actual repo

# Database credentials (already set up in Hostinger)
DB_NAME="u806021370_acraltech"
DB_USER="u806021370_acraltech"
DB_PASS="Health@768"

echo -e "${BLUE}🚀 Starting CRM Deployment to Hostinger...${NC}"
echo -e "${BLUE}Domain: ${DOMAIN}${NC}"
echo -e "${BLUE}Server: ${HOST}${NC}"
echo "----------------------------------------"

# Function to run remote commands
run_remote() {
    ssh -p ${PORT} -o StrictHostKeyChecking=no ${USER}@${HOST} "$1"
}

# Function to upload files
upload_files() {
    echo -e "${YELLOW}📤 Uploading files to server...${NC}"
    rsync -avz --delete -e "ssh -p ${PORT}" \
        --exclude='node_modules' \
        --exclude='.git' \
        --exclude='.env' \
        --exclude='storage/logs/*' \
        --exclude='storage/app/public/*' \
        ./ ${USER}@${HOST}:${DOMAIN_PATH}/
}

# Check SSH connection
echo -e "${YELLOW}🔐 Testing SSH connection...${NC}"
if run_remote "echo 'SSH connection successful!'"; then
    echo -e "${GREEN}✅ SSH connection established${NC}"
else
    echo -e "${RED}❌ SSH connection failed${NC}"
    exit 1
fi

# Option 1: Deploy from local files
deploy_from_local() {
    echo -e "${YELLOW}📦 Deploying from local files...${NC}"
    
    # Build assets locally if needed
    if [ -f "package.json" ]; then
        echo -e "${YELLOW}🏗️ Building assets locally...${NC}"
        npm install
        npm run build
    fi
    
    # Install PHP dependencies
    if [ -f "composer.json" ]; then
        echo -e "${YELLOW}📦 Installing PHP dependencies...${NC}"
        composer install --no-dev --optimize-autoloader
    fi
    
    # Upload files
    upload_files
    
    # Set up Laravel on server
    setup_laravel_remote
}

# Option 2: Deploy via Git (if repository is available)
deploy_from_git() {
    echo -e "${YELLOW}📥 Deploying from Git repository...${NC}"
    
    # Clone or update repository on server
    run_remote "
        if [ ! -d '${SOURCE_PATH}' ]; then
            echo 'Cloning repository...'
            git clone ${GIT_REPO} ${SOURCE_PATH}
        else
            echo 'Updating repository...'
            cd ${SOURCE_PATH}
            git pull origin main
        fi
    "
    
    # Build and deploy on server
    run_remote "
        cd ${SOURCE_PATH}
        
        # Install PHP dependencies
        if command -v composer &> /dev/null; then
            composer install --no-dev --optimize-autoloader
        fi
        
        # Build assets if Node.js is available
        if command -v npm &> /dev/null; then
            npm install
            npm run build
        fi
        
        # Sync files to web directory
        rsync -av --exclude='public' --exclude='.git' --exclude='node_modules' ${SOURCE_PATH}/ ${DOMAIN_PATH}/
        rsync -av ${SOURCE_PATH}/public/ ${DOMAIN_PATH}/
    "
    
    setup_laravel_remote
}

# Set up Laravel environment on server
setup_laravel_remote() {
    echo -e "${YELLOW}⚙️ Setting up Laravel environment...${NC}"
    
    run_remote "
        cd ${DOMAIN_PATH}
        
        # Set up .env if it doesn't exist
        if [ ! -f '.env' ]; then
            echo 'Creating .env file...'
            cp .env.example .env
            echo 'Please update .env with your actual configuration!'
        fi
        
        # Set correct permissions
        chmod -R 755 storage bootstrap/cache
        chmod 644 .env
        
        # Generate app key if needed
        if ! grep -q 'APP_KEY=base64:' .env; then
            php artisan key:generate --no-interaction
        fi
        
        # Run Laravel setup commands
        php artisan config:clear
        php artisan cache:clear
        php artisan view:clear
        php artisan route:clear
        
        # Run migrations (be careful in production!)
        echo 'Running database migrations...'
        php artisan migrate --force --no-interaction
        
        # Cache optimizations
        php artisan config:cache
        php artisan route:cache
        php artisan view:cache
        
        # Create storage link
        php artisan storage:link
        
        echo 'Laravel setup completed!'
    "
}

# Create .htaccess file
create_htaccess() {
    echo -e "${YELLOW}📝 Creating .htaccess file...${NC}"
    
    run_remote "cat > ${DOMAIN_PATH}/.htaccess << 'EOF'
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>

# Security Headers
<IfModule mod_headers.c>
    Header always set X-Frame-Options \"SAMEORIGIN\"
    Header always set X-Content-Type-Options \"nosniff\"
    Header always set X-XSS-Protection \"1; mode=block\"
</IfModule>
EOF"
}

# Main deployment
main() {
    echo -e "${BLUE}Choose deployment method:${NC}"
    echo "1) Deploy from local files"
    echo "2) Deploy from Git repository"
    echo "3) Just run Laravel setup commands"
    echo "4) Create .htaccess only"
    read -p "Enter your choice (1-4): " choice
    
    case $choice in
        1)
            deploy_from_local
            ;;
        2)
            echo -e "${YELLOW}Please update GIT_REPO variable in this script first!${NC}"
            read -p "Continue with Git deployment? (y/N): " confirm
            if [[ $confirm =~ ^[Yy]$ ]]; then
                deploy_from_git
            fi
            ;;
        3)
            setup_laravel_remote
            ;;
        4)
            create_htaccess
            ;;
        *)
            echo -e "${RED}Invalid choice${NC}"
            exit 1
            ;;
    esac
    
    create_htaccess
    
    echo ""
    echo -e "${GREEN}✅ Deployment completed successfully!${NC}"
    echo -e "${GREEN}🌐 Your CRM should be available at: https://${DOMAIN}${NC}"
    echo ""
    echo -e "${YELLOW}📋 Post-deployment checklist:${NC}"
    echo "1. Update .env file with actual database credentials"
    echo "2. Test the website: https://${DOMAIN}"
    echo "3. Check admin panel access"
    echo "4. Verify email settings"
    echo "5. Set up Cloudflare DNS"
}

# Run the deployment
main "$@"
