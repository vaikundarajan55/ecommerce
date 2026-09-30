# ShopKart: E-commerce website + Admin panel (CodeIgniter 4.7 + Bootstrap 5 + MySQL)

Complete, working project. The CodeIgniter 4 framework is already included (no Composer needed).
Requirements: PHP 8.1+ (intl, mbstring, mysqli enabled), MySQL/MariaDB, Apache (XAMPP/WAMP) or PHP's built-in server.

## 1. Setup (5 minutes)
1. Create the database: import `database/ecommerce_ci4.sql` in phpMyAdmin (it creates `ecommerce_ci4` plus sample data).
2. Open `.env` and check the DB user/password (default: root / empty) and `app.baseURL`.
   (If `.env` is missing, copy `env.example.txt` to `.env`.)
3. Run it on XAMPP: keep this folder at `htdocs/ecommerce` (baseURL is `http://localhost/ecommerce/`) and open:
   - Website: http://localhost/ecommerce/
   - Admin panel: http://localhost/ecommerce/admin/
   (Different folder name? Change `app.baseURL` in `.env` to match.)
4. Make sure `writable/` and `public/uploads/` are writable.

## 2. Logins
| Area | URL | Email | Password |
|---|---|---|---|
| Admin panel | /admin/login | admin@example.com | admin123 |
| Customer | /login | user@example.com | user123 |

## 3. Folder structure
```
app/
  Config/Routes.php          all website + admin routes
  Config/Filters.php         csrf + adminAuth / userAuth / guest filters
  Controllers/
    Website/  Home, Shop, Cart, Auth, Checkout, Account
    Admin/  Auth, Dashboard, Banners, Categories, Subcategories,
            Products, Orders, Users, Enquiries, Contacts, Password
  Models/                    one model per table
  Libraries/Cart.php         session cart        Libraries/Uploader.php   image upload
  Filters/                   AdminAuth, UserAuth, GuestOnly
  Helpers/shop_helper.php    money(), img_url(), status_badge() ...
  Views/
    website/ layout, pages (home, about, contact), shop (list, view, cart),
             checkout (checkout, payment, success, failure),
             auth (login, register, forgot, reset),
             account (dashboard, orders, order_view, profile, password)
    admin/   layout, auth (login, password), dashboard, banners, categories,
             subcategories, products, orders, users, enquiries, contacts
    shared/invoice.php       printable invoice (used by admin and customer)
public/
  assets/website/ (css/site.css, js/site.js)   assets/admin/ (css/admin.css, js/admin.js)   assets/img (shared)
  uploads/ (products, banners, categories)     images uploaded from admin
database/ecommerce_ci4.sql   MySQL schema + sample data
system/                      CodeIgniter framework core
```

## 4. Features
Admin: login, dashboard (stats, 7-day chart, low stock, recent orders), banners, categories, subcategories,
products (image upload, sale price, stock, featured), order list (filter, search, status update, invoice),
user list (enable/disable/delete), enquiries, contact messages, change password, logout.

Website: home (banner slider), about, contact, shop list (category/subcategory filter, search, sort, pagination),
product view (with enquiry form), cart, checkout, dummy payment gateway, success and failure pages, login, register,
forgot/reset password, customer dashboard, profile, order list and detail, invoice, change password.

Animation: hero text entrance, floating shapes, scroll reveal (AOS), card hover lift, cart badge bump, animated login
pages, count-up dashboard numbers, chart animation, staggered table rows, payment spinner, drawn success/failure
icons, confetti on success. Reduced-motion setting is respected.

## 5. Notes
- Bootstrap, Bootstrap Icons, AOS, Chart.js and Google Fonts load from CDN (internet needed while browsing).
- Forgot password: no mail server is configured, so in development the reset link is shown on screen.
  For production, send it with CI's Email library (see `Auth::sendReset`).
- Dummy payment: the gateway page lets you pick Pay (success) or Simulate failed payment.
  Replace `Checkout::process()` with Razorpay/Stripe/PayU when you go live.
- Free shipping above Rs 999, otherwise Rs 49 (edit `app/Libraries/Cart.php`). Store name: `site_name()` in the helper.
- Before going live: set `CI_ENVIRONMENT = production` in `.env`, change the admin password, point the web root to `public/`.
