<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<title>Admin Dashboard</title>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<link rel="stylesheet"
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

<script src="https://cdn.tailwindcss.com"></script>

</head>


<body>

<?php require_once APP_PATH . '/Views/admin/nav.php'; ?>


<!-- ✅ Main Container (ใช้ class นี้เท่านั้น) -->
<div class="admin-main-container p-4 sm:p-6 lg:p-8">


<!-- Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6 mb-8">


<!-- Revenue -->
<div class="bg-white rounded-xl p-6 shadow-sm border border-slate-200">

<p class="text-sm text-slate-500 mb-1">
Total Revenue
</p>

<h3 class="text-2xl font-bold text-slate-800">
฿<?= number_format($stats['revenue'],2) ?>
</h3>

</div>


<!-- Orders -->
<div class="bg-blue-300 rounded-xl p-6 shadow-sm border border-slate-200">

<p class="text-sm text-blue-800 mb-1">
Total Orders
</p>
<h3 class="text-2xl font-bold text-blue-800">
<?= number_format($stats['total_orders']) ?>
</h3>

</div>


<!-- Completed -->
<div class="bg-green-300 rounded-xl p-6 shadow-sm border border-slate-200">

<p class="text-sm text-green-800 mb-1">
Completed
</p>

<h3 class="text-2xl font-bold text-green-800">
<?= number_format($stats['completed_orders']) ?>
</h3>

</div>


<!-- Cancelled -->
<div class="bg-red-300 rounded-xl p-6 shadow-sm border border-slate-200">

<p class="text-sm text-red-800 mb-1">
Cancelled
</p>

<h3 class="text-2xl font-bold text-red-800">
<?= number_format($stats['cancelled_orders']) ?>
</h3>

</div>


</div>



<!-- Chart + Table -->
<div class="grid grid-cols-1 xl:grid-cols-3 gap-6">


<!-- Chart -->
<div class="xl:col-span-2 bg-white rounded-xl shadow-sm border border-slate-200 p-6">


<div class="flex justify-between mb-4">

<h2 class="text-lg font-bold text-slate-800">
Order Status
</h2>

</div>


<div class="relative w-full h-[300px] sm:h-[350px] lg:h-[400px]">

<canvas id="salesChart"></canvas>

</div>


</div>



<!-- Top Products -->
<div class="bg-white rounded-xl shadow-sm border border-slate-200 flex flex-col">


<div class="p-6 border-b border-slate-200">

<h2 class="text-lg font-bold text-slate-800">
Top Products
</h2>

</div>


<div class="overflow-x-auto">


<table class="w-full text-sm">


<thead class="bg-slate-50">

<tr>

<th class="px-6 py-3 text-left font-semibold text-slate-500">
Product
</th>

<th class="px-6 py-3 text-right font-semibold text-slate-500">
Sold
</th>

</tr>

</thead>



<tbody>


<?php if(!empty($topProducts)): ?>


<?php foreach($topProducts as $p): ?>


<tr class="border-t border-slate-100 hover:bg-slate-50">


<td class="px-6 py-4">


<div class="flex items-center gap-3">


<img src="<?= htmlspecialchars($p['img'] ?? 'https://via.placeholder.com/40') ?>"
class="w-10 h-10 rounded-lg object-cover">


<span class="font-medium text-slate-700 truncate">
<?= htmlspecialchars($p['product_name']) ?>
</span>


</div>


</td>


<td class="px-6 py-4 text-right font-semibold text-slate-800">

<?= number_format($p['total_sold']) ?>

</td>


</tr>


<?php endforeach; ?>


<?php else: ?>


<tr>

<td colspan="2"
class="text-center py-6 text-slate-500">

No data

</td>

</tr>


<?php endif; ?>


</tbody>


</table>


</div>


</div>


</div>


</div>



<!-- Prepare Chart.js Data -->
<?php
$pieLabels = ['ได้รับสินค้า (Completed)', 'ยกเลิกสินค้า (Cancelled)'];
$pieData = [
    (int)$stats['completed_orders'],
    (int)$stats['cancelled_orders']
];
?>

<!-- Chart Script -->
<script>

const ctx = document.getElementById('salesChart');

new Chart(ctx, {
    type: 'pie',
    data: {
        labels: <?= json_encode($pieLabels) ?>,
        datasets: [{
            data: <?= json_encode($pieData) ?>,
            backgroundColor: [
                '#3fc36ff7', // สีเขียว
                '#e07373ff'  // สีแดง
            ],
            hoverOffset: 4,
            borderWidth: 2,
            borderColor: '#ffffff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true,
                position: 'bottom',
                labels: {
                    font: {
                        family: 'sans-serif',
                        size: 14
                    }
                }
            }
        }
    }
});


</script>


</body>
</html>