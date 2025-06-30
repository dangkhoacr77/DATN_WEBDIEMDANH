b1:chạy từng lệnh dưới đây
composer install
cp .env.example .env    
php artisan key:generate
php artisan migrate

b2: chạy all bên dưới
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
