chạy từng lệnh dưới đây
composer install
cp .env.example .env    
php artisan key:generate
php artisan migrate












ko cần chạy lệnh dưới đây
php artisan make:migration create_luot_truy_cap_table
php artisan make:middleware GhiNhanLuotTruyCap
nếu ko đăng kí đc:
SESSION_DOMAIN=
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear