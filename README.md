# فروشگاه آنلاین

فروشگاه پوشاک با Laravel 12، صفحه فروشگاه راست‌چین، مدیریت محصولات و موجودی، سبد خرید، سفارش، کد تخفیف، فاکتور و اتصال درگاه زرین‌پال.

## نیازمندی‌ها

- PHP 8.2 یا بالاتر با افزونه‌های متداول Laravel و PDO MySQL
- Composer 2
- Node.js و npm
- MySQL 8 یا MariaDB سازگار

## نصب و اجرای محلی

```bash
composer install
cp .env.example .env
php artisan key:generate
npm ci
npm run build
```

در `.env` اتصال دیتابیس را تنظیم کنید، سپس اجرا کنید:

```bash
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

برای مشاهده فرانت‌اند، آدرس `http://127.0.0.1:8000` را باز کنید. داده‌های آزمایشی فروشگاه در seeder مستقل `TestShopSeeder` هستند و به صورت پیش‌فرض وارد نمی‌شوند؛ برای محیط توسعه می‌توانید از `php artisan db:seed --class=TestShopSeeder` استفاده کنید.

## انتشار روی سرور

1. فایل `.env` را روی سرور بسازید و مقادیر `APP_ENV=production`، `APP_DEBUG=false`، `APP_URL`، تنظیمات دیتابیس و ایمیل را وارد کنید. فایل `.env` را در مخزن یا بسته عمومی قرار ندهید.
2. روی سرور اجرا کنید:

   ```bash
   composer install --no-dev --prefer-dist --optimize-autoloader
   npm ci
   npm run build
   php artisan key:generate
   php artisan migrate --force
   php artisan storage:link
   php artisan optimize
   ```

   اگر `APP_KEY` از قبل برای همین نصب تنظیم شده، آن را حفظ کنید و دوباره تولید نکنید.
3. ریشه وب‌سرور را روی پوشه `public/` قرار دهید. پوشه‌های `storage/` و `bootstrap/cache/` باید برای کاربر PHP قابل نوشتن باشند. فقط فایل‌های build شده در `public/build/` برای اجرای فرانت‌اند لازم‌اند؛ فایل موقت `public/hot` مربوط به حالت توسعه است و نباید منتشر شود.
4. بعد از ساخت حساب مالک فروشگاه، دسترسی مدیر را با ایمیل همان حساب فعال کنید:

   ```bash
   php artisan app:make-admin owner@example.com
   ```

5. برای پرداخت واقعی، `ZARINPAL_PAYMENT_MODE=normal`، شناسه پذیرنده `ZARINPAL_MERCHANT_ID` و نشانی عمومی HTTPS در `ZARINPAL_CALLBACK_URL` را تنظیم کنید. حالت پیش‌فرض درگاه sandbox است و برای دریافت وجه واقعی مناسب نیست.

## مسیرهای پنل

- فروشگاه: `/`
- ورود و ثبت‌نام: `/login` و `/register`
- پنل مدیر: `/admin/dashboard`

کاربر عادی با ورود به `/admin` دسترسی نمی‌گیرد؛ ابتدا باید با فرمان `app:make-admin` نقش مدیر برای حسابش ثبت شود.
