#!/bin/bash

# Database Deployment Script
# Run this on your hosting server via SSH

echo "=== Database Deployment Script ==="

# Database credentials (update these)
DB_HOST="localhost"
DB_USER="romantic_user"
DB_PASS="password123"
DB_NAME="romantic_web"

# Test connection
echo "Testing database connection..."
mysql -h $DB_HOST -u $DB_USER -p$DB_PASS -e "SELECT 1;" 2>/dev/null

if [ $? -eq 0 ]; then
    echo "✅ Database connection successful!"
    
    # Create database if not exists
    echo "Creating database..."
    mysql -h $DB_HOST -u $DB_USER -p$DB_PASS -e "CREATE DATABASE IF NOT EXISTS $DB_NAME;"
    
    # Import database structure
    echo "Importing database structure..."
    mysql -h $DB_HOST -u $DB_USER -p$DB_PASS $DB_NAME < database.sql
    
    echo "✅ Database setup completed!"
    
    # Test tables
    echo "Testing tables..."
    mysql -h $DB_HOST -u $DB_USER -p$DB_PASS $DB_NAME -e "
        SELECT 'Users:' as table_name, COUNT(*) as count FROM users
        UNION ALL
        SELECT 'Photos:' as table_name, COUNT(*) as count FROM photos
        UNION ALL
        SELECT 'Confessions:' as table_name, COUNT(*) as count FROM confessions;
    "
    
else
    echo "❌ Database connection failed!"
    echo "Please check your credentials in the script."
fi

echo "=== Deployment Complete ==="