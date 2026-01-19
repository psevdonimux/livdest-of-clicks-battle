#!/bin/bash
apt update && apt upgrade -y
apt install git php php-pdo mariadb -y
mysql_install_db
git clone https://github.com/USER/REPO.git clicker
cd clicker
cp .env.example .env
echo "Done! Edit clicker/.env with your database credentials"
