<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فروشگاه آنلاین</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, sans-serif; background: #f5f5f5; color: #333; }

        header {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white; padding: 20px 40px;
            display: flex; justify-content: space-between; align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }
        header h1 { font-size: 1.5rem; }
        .cart-icon { font-size: 1.3rem; cursor: pointer; position: relative; }
        .cart-count {
            background: #ff4757; color: white; border-radius: 50%;
            padding: 2px 7px; font-size: 0.75rem; position: absolute; top: -8px; left: -10px;
        }

        .search-bar {
            padding: 20px 40px; background: white;
            display: flex; gap: 10px; border-bottom: 1px solid #eee;
        }
        .search-bar input {
            flex: 1; padding: 10px 15px; border: 2px solid #ddd; border-radius: 8px;
            font-size: 1rem; outline: none; transition: border 0.3s;
        }
        .search-bar input:focus { border-color: #667eea; }
        .search-bar select {
            padding: 10px 15px; border: 2px solid #ddd; border-radius: 8px;
            font-size: 1rem; background: white; cursor: pointer;
        }

        .products {
            display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 20px; padding: 30px 40px; max-width: 1400px; margin: 0 auto;
        }

        .product-card {
            background: white; border-radius: 12px; overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08); transition: transform 0.2s, box-shadow 0.2s;
        }
        .product-card:hover { transform: translateY(-4px); box-shadow: 0 8px 25px rgba(0,0,0,0.15); }
        .product-card img {
            width: 100%; height: 220px; object-fit: cover; background: #eee;
        }
        .product-info { padding: 15px; }
        .product-info h3 { font-size: 1rem; margin-bottom: 8px; }
        .product-info .price { color: #667eea; font-size: 1.15rem; font-weight: bold; }
        .product-info .old-price { text-decoration: line-through; color: #aaa; font-size: 0.85rem; margin-left: 8px; }
        .product-info .rating { color: #f1c40f; margin: 8px 0; font-size: 0.9rem; }
        .btn-add {
            width: 100%; padding: 10px; margin-top: 10px;
            background: linear-gradient(135deg, #667eea, #764ba2); color: white;
            border: none; border-radius: 8px; font-size: 0.95rem; cursor: pointer;
            transition: opacity 0.2s;
        }
        .btn-add:hover { opacity: 0.9; }
        .btn-add:active { transform: scale(0.98); }

        .badge {
            display: inline-block; padding: 3px 8px; border-radius: 4px;
            font-size: 0.7rem; font-weight: bold; color: white; margin-bottom: 5px;
        }
        .badge-sale { background: #ff4757; }
        .badge-new { background: #2ed573; }

        .toast {
            position: fixed; bottom: 30px; left: 50%; transform: translateX(-50%);
            background: #333; color: white; padding: 12px 24px; border-radius: 8px;
            opacity: 0; transition: opacity 0.3s; pointer-events: none; z-index: 100;
        }
        .toast.show { opacity: 1; }

        footer {
            text-align: center; padding: 20px; color: #888; font-size: 0.85rem;
            margin-top: 30px;
        }

        @media (max-width: 600px) {
            header { padding: 15px 20px; }
            .search-bar { padding: 15px 20px; flex-direction: column; }
            .products { padding: 20px; }
        }
    </style>
</head>
<body>

<header>
    <h1>فروشگاه آنلاین</h1>
    <div class="cart-icon" onclick="showCart()">
        <span>&#128722;</span>
        <span class="cart-count" id="cartCount">0</span>
    </div>
</header>

<div class="search-bar">
    <input type="text" id="searchInput" placeholder="جستجوی محصول..." oninput="filterProducts()">
    <select id="categoryFilter" onchange="filterProducts()">
        <option value="all">همه دسته‌ها</option>
        <option value="electronics">الکترونیکی</option>
        <option value="clothing">پوشاک</option>
        <option value="home">خانه و آشپزخانه</option>
        <option value="sports">ورزشی</option>
    </select>
</div>

<div class="products" id="products"></div>

<div class="toast" id="toast"></div>

<footer>فروشگاه آنلاین &copy; ۱۴۰۵</footer>

<script>
const products = [
    { id: 1, name: "هدفون بی‌سیم مدل X1", price: 899000, oldPrice: 1200000, category: "electronics", badge: "sale", rating: 4.5, img: "https://picsum.photos/seed/headphone/400/300" },
    { id: 2, name: "کفش ورزشی مردانه", price: 650000, category: "sports", badge: "new", rating: 4.2, img: "https://picsum.photos/seed/shoes/400/300" },
    { id: 3, name: "ساعت هوشمند اپل واچ", price: 4500000, oldPrice: 5200000, category: "electronics", badge: "sale", rating: 4.8, img: "https://picsum.photos/seed/smartwatch/400/300" },
    { id: 4, name: "پیراهن مردانه کلاسیک", price: 320000, category: "clothing", rating: 4.0, img: "https://picsum.photos/seed/shirt/400/300" },
    { id: 5, name: "قابلمه چدنی ۳ تکه", price: 780000, category: "home", badge: "new", rating: 4.6, img: "https://picsum.photos/seed/cooking/400/300" },
    { id: 6, name: "لپ‌تاپ مک‌بوک پرو", price: 62000000, category: "electronics", rating: 4.9, img: "https://picsum.photos/seed/laptop/400/300" },
    { id: 7, name: "کیف ورزشی آدیداس", price: 420000, category: "sports", rating: 4.3, img: "https://picsum.photos/seed/bag/400/300" },
    { id: 8, name: "بخاری برقی اتاق", price: 1150000, oldPrice: 1400000, category: "home", badge: "sale", rating: 4.1, img: "https://picsum.photos/seed/heater/400/300" },
    { id: 9, name: "کاپشن مردانه زمستانه", price: 980000, category: "clothing", badge: "new", rating: 4.4, img: "https://picsum.photos/seed/jacket/400/300" },
    { id: 10, name: "اسپیکر بلوتوثی JBL", price: 1350000, category: "electronics", rating: 4.7, img: "https://picsum.photos/seed/speaker/400/300" },
    { id: 11, name: "شلوار جین مردانه", price: 380000, category: "clothing", rating: 4.0, img: "https://picsum.photos/seed/jeans/400/300" },
    { id: 12, name: "تردمیل خانگی", price: 8500000, oldPrice: 10000000, category: "sports", badge: "sale", rating: 4.5, img: "https://picsum.photos/seed/treadmill/400/300" },
];

let cart = [];

function formatPrice(p) { return p.toLocaleString('fa-IR') + ' تومان'; }

function renderStars(r) {
    const full = Math.floor(r);
    const half = r % 1 >= 0.5 ? 1 : 0;
    return '★'.repeat(full) + (half ? '☆' : '') + ' ' + r;
}

function renderProducts(list) {
    document.getElementById('products').innerHTML = list.map(p => `
        <div class="product-card">
            ${p.badge ? `<span class="badge badge-${p.badge}">${p.badge === 'sale' ? 'تخفیف' : 'جدید'}</span>` : ''}
            <img src="${p.img}" alt="${p.name}" loading="lazy">
            <div class="product-info">
                <h3>${p.name}</h3>
                <div class="rating">${renderStars(p.rating)}</div>
                <div>
                    <span class="price">${formatPrice(p.price)}</span>
                    ${p.oldPrice ? `<span class="old-price">${formatPrice(p.oldPrice)}</span>` : ''}
                </div>
                <button class="btn-add" onclick="addToCart(${p.id})">افزودن به سبد خرید</button>
            </div>
        </div>
    `).join('');
}

function filterProducts() {
    const q = document.getElementById('searchInput').value.toLowerCase();
    const cat = document.getElementById('categoryFilter').value;
    const filtered = products.filter(p => {
        const matchSearch = p.name.includes(q) || q === '';
        const matchCat = cat === 'all' || p.category === cat;
        return matchSearch && matchCat;
    });
    renderProducts(filtered);
}

function addToCart(id) {
    cart.push(id);
    document.getElementById('cartCount').textContent = cart.length;
    showToast('محصول به سبد خرید اضافه شد');
}

function showToast(msg) {
    const t = document.getElementById('toast');
    t.textContent = msg;
    t.classList.add('show');
    setTimeout(() => t.classList.remove('show'), 2000);
}

function showCart() {
    showToast(`تعداد محصولات در سبد: ${cart.length}`);
}

renderProducts(products);
</script>
</body>
</html>