<script src="https://cdn.tailwindcss.com"></script>
<link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Kanit:wght@300;400;500;600&display=swap"
    rel="stylesheet">
<style>
    body {
        font-family: 'Inter', 'Kanit', sans-serif;
        background-color: #f8fafc;
        /* gray-50 */
    }

    @media (min-width: 1024px) {
        body {
            padding-left: 16rem;
        }

        .admin-main-container {
            padding-top: 5rem;
        }
    }

    /* Fix layout overlap */
    .container {
        max-width: 100% !important;
        padding-left: 2rem !important;
        padding-right: 2rem !important;
        margin-top: 5rem !important;
        /* Space for top navbar */
        min-height: calc(100vh - 5.5rem);
    }

    .admin-main-content {
        padding-top: 1rem;
    }
</style>

<!-- Mobile Overlay -->
<div id="mobile-overlay"
    class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-20 hidden lg:hidden transition-opacity"></div>

<!-- Sidebar -->
<aside id="sidebar"
    class="fixed inset-y-0 left-0 w-64 bg-slate-900 text-slate-300 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out z-30 shadow-2xl flex flex-col border-r border-slate-800">
    <!-- Logo -->
    <div class="h-16 flex items-center justify-start px-6 border-b border-slate-800/80 bg-slate-900/50">
        <a href="<?= BASE_URL ?>/admin/products"
            class="text-xl font-bold flex items-center gap-3 text-white tracking-wide transition-all hover:scale-105 text-decoration-none">
            <div class="w-8 h-8 rounded-lg bg-blue-600 flex items-center justify-center shadow-lg shadow-blue-600/30 flex-shrink-0">
                <i class="bi bi-cpu-fill text-white"></i>
            </div>
            <span class="whitespace-nowrap">TECH Admin</span>
        </a>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 overflow-y-auto py-6 px-4 flex flex-col gap-1.5 scrollbar-thin scrollbar-thumb-slate-700">
        <p class="px-2 text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Main Menu</p>

        <?php
        $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $navItems = [
            ['url' => '/admin', 'icon' => 'bi-grid-1x2-fill', 'label' => 'แดชบอร์ด', 'match_exact' => true],
            ['url' => '/admin/products', 'icon' => 'bi-box-seam-fill', 'label' => 'จัดการสินค้า'],
            ['url' => '/admin/orders', 'icon' => 'bi-cart-check-fill', 'label' => 'คำสั่งซื้อ & ขนส่ง'],
            ['url' => '/admin/promocodes', 'icon' => 'bi-ticket-perforated-fill', 'label' => 'โค้ดส่วนลด'],
            ['url' => '/admin/reviews', 'icon' => 'bi-chat-quote-fill', 'label' => 'รีวิวลูกค้า'],
            ['url' => '/admin/banners', 'icon' => 'bi-images', 'label' => 'จัดการแบนเนอร์']
        ];

        foreach ($navItems as $item):
            $isActive = false;
            if (!empty($item['match_exact'])) {
                $isActive = rtrim($currentPath, '/') === rtrim(BASE_URL . $item['url'], '/');
            } else {
                $isActive = strpos($currentPath, BASE_URL . $item['url']) !== false;
            }
            ?>
            <a href="<?= BASE_URL ?><?= $item['url'] ?>"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group <?= $isActive ? 'bg-blue-600 text-white shadow-md shadow-blue-600/20' : 'hover:bg-slate-800 hover:text-white' ?>">
                <i
                    class="bi <?= $item['icon'] ?> text-lg <?= $isActive ? 'text-white' : 'text-slate-400 group-hover:text-blue-400' ?> transition-colors"></i>
                <span class="font-medium text-sm"><?= $item['label'] ?></span>
            </a>
        <?php endforeach; ?>
    </nav>

    <!-- Bottom Actions -->
    <div class="p-4 border-t border-slate-800/80 bg-slate-900/50">
        <a href="<?= BASE_URL ?>/home"
            class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-slate-800 text-slate-400 hover:text-white transition-all group mb-1 text-sm font-medium">
            <i class="bi bi-shop text-lg group-hover:text-teal-400"></i>
            หน้าร้านค้า
        </a>
        <a href="<?= BASE_URL ?>/logout"
            class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-red-500/10 text-red-400 hover:text-red-300 transition-all group text-sm font-medium">
            <i class="bi bi-box-arrow-right text-lg"></i>
            ออกจากระบบ
        </a>
    </div>
</aside>

<!-- Top border gradient -->
<div class="fixed top-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 z-50"></div>

<!-- Top Navbar -->
<header
    class="fixed top-1 right-0 left-0 lg:left-64 h-16 bg-white shadow-sm border-b border-slate-200 z-10 flex items-center justify-between px-4 sm:px-6 transition-all duration-300 backdrop-blur-md bg-white/90">
    <div class="flex items-center gap-4">
        <button id="mobile-menu-btn"
            class="lg:hidden text-slate-500 hover:text-blue-600 p-2 rounded-xl bg-slate-100/50 hover:bg-blue-50 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-100">
            <i class="bi bi-list text-xl"></i>
        </button>
        <div class="hidden sm:block">
            <h2 class="text-xl font-bold text-slate-800 tracking-tight">Overview</h2>
            <p class="text-xs text-slate-500 font-medium">Welcome back, Admin</p>
        </div>
    </div>

    <div class="flex items-center gap-3 sm:gap-5">
        <!-- Search bar (dummy) -->
        <div
            class="hidden md:flex items-center bg-slate-100 rounded-full px-4 py-2 border border-slate-200/60 focus-within:ring-2 focus-within:ring-blue-500/20 focus-within:border-blue-500 transition-all w-64">
            <i class="bi bi-search text-slate-400 mr-2"></i>
            <input type="text" placeholder="Search..."
                class="bg-transparent border-none outline-none text-sm text-slate-700 w-full placeholder-slate-400">
        </div>

        <!-- Profile Dropdown -->
        <a href="<?= BASE_URL ?>/profile" class="flex items-center gap-3 pl-4 border-l border-slate-200 group">
            <div class="text-right hidden sm:block">
                <p class="text-sm font-bold text-slate-800 leading-tight group-hover:text-blue-600 transition-colors">
                    <?= htmlspecialchars($_SESSION['username'] ?? 'Admin') ?>
                </p>
                <p class="text-[11px] font-semibold text-blue-600 uppercase tracking-wide">Administrator</p>
            </div>
            <div
                class="w-10 h-10 rounded-full bg-gradient-to-tr from-blue-600 to-indigo-500 flex items-center justify-center text-white font-bold text-sm shadow-md shadow-blue-500/20 ring-2 ring-white cursor-pointer hover:scale-110 group-hover:ring-blue-100 transition-all">
                <?= strtoupper(substr($_SESSION['username'] ?? 'A', 0, 1)) ?>
            </div>
        </a>
    </div>
</header>
<script>
    const btn = document.getElementById('mobile-menu-btn');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('mobile-overlay');

    function toggleMenu() {
        sidebar.classList.toggle('-translate-x-full');
        overlay.classList.toggle('hidden');
        document.body.classList.toggle('overflow-hidden');
    }

    btn.addEventListener('click', toggleMenu);
    overlay.addEventListener('click', toggleMenu);

    // ปิดเมนูเมื่อคลิกที่ลิงก์ในหน้ามือถือ
    const links = sidebar.querySelectorAll('a');
    links.forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth < 1024) {
                toggleMenu();
            }
        });
    });

    // Global Confirm Action with SweetAlert2
    function confirmAction(e, url, message = "คุณแน่ใจหรือไม่ว่าต้องการทำรายการนี้?", confirmText = "ใช่, ดำเนินการเลย!", isDanger = true) {
        e.preventDefault();
        Swal.fire({
            title: 'โปรดยืนยัน',
            text: message,
            icon: isDanger ? 'warning' : 'question',
            showCancelButton: true,
            confirmButtonColor: isDanger ? '#ef4444' : '#3b82f6',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: confirmText,
            cancelButtonText: 'ยกเลิก',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-2xl shadow-2xl border border-slate-200'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>