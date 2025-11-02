#!/bin/bash

# ---------- সেটিং ----------
DATE=$(date +%Y%m%d-%H%M%S)
PROJECT_DIR="$(pwd)"  # বর্তমান ফোল্ডার
BACKUP_ROOT="./backups"  # লোকালে
BACKUP_DIR="$BACKUP_ROOT/firojatech-backup-$DATE"

# কন্টেইনার নাম (docker-compose থেকে)
MYSQL_CONTAINER="firojatech-system-mysql-1"
PHP_CONTAINER="firojatech-system-php-1"

# ---------- ফোল্ডার তৈরি ----------
mkdir -p "$BACKUP_DIR"
echo "Backup started: $BACKUP_DIR"

# ---------- ১. public ফোল্ডার ব্যাকআপ ----------
if [ -d "./public" ]; then
    tar -czf "$BACKUP_DIR/public.tar.gz" -C ./public .
    echo "public/ backed up"
else
    echo "Warning: ./public folder not found"
fi

# ---------- ২. কনফিগ ফাইল ----------
cp docker-compose.yml "$BACKUP_DIR/" 2>/dev/null || echo "docker-compose.yml not found"
cp init.sql "$BACKUP_DIR/" 2>/dev/null || echo "init.sql not found"
cp .env "$BACKUP_DIR/" 2>/dev/null || echo ".env not found"

# ---------- ৩. MySQL ডাটাবেস ডাম্প ----------
echo "Dumping MySQL database..."
if docker ps --format "table {{.Names}}" | grep -q "$MYSQL_CONTAINER"; then
    docker exec "$MYSQL_CONTAINER" mysqldump -u firoja -pfiroja123 firojatech_db > "$BACKUP_DIR/database.sql"
    echo "Database dumped: database.sql"
else
    echo "Error: MySQL container '$MYSQL_CONTAINER' not running"
    exit 1
fi

# ---------- ৪. ফাইনাল ZIP ----------
echo "Creating final backup archive..."
tar -czf "$BACKUP_ROOT/firojatech-full-back