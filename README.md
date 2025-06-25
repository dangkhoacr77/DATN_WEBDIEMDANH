chạy từng lệnh dưới đây
composer install
cp .env.example .env    
php artisan key:generate
php artisan migrate












ko cần chạy lệnh dưới đây
php artisan make:migration create_luot_truy_cap_table
php artisan make:middleware GhiNhanLuotTruyCap