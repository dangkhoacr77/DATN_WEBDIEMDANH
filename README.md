b1:chạy từng lệnh dưới đây
composer install
cp .env.example .env    
php artisan key:generate
php artisan migrate
php artisan storage:link

b2: chạy all bên dưới
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear



php artisan serve --host=0.0.0.0 --port=8000
chạy cmd trỏ tới thư mục ngrok chạy: ngrok http 8000
lấy cái Forwarding