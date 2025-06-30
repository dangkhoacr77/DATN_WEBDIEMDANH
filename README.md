chạy từng lệnh dưới đây
composer install
cp .env.example .env    
php artisan key:generate
php artisan migrate

php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
