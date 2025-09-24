

<?php
$title='Call Report';
$subTitle='Called and Mailed Report';
?>

<?php $__env->startSection('content'); ?>
<div class="row gy-4 mt-1">

    <!-- Main Bar Chart -->
    <div class="col-xxl-8 col-lg-6">
        <div class="card h-100 border shadow-none radius-8 border-0">
            <div class="card-body p-24">
                <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                    <div>
                        <h6 class="mb-2 fw-bold text-lg">Called and Mailed Statistic</h6>
                        <span class="text-sm fw-medium text-secondary-light" id="chartLabel">Yearly overview</span>
                    </div>
                    <div>
                        <select id="timeFrameSelect" class="form-select form-select-sm w-auto bg-base border text-secondary-light">
                            <option value="yearly">Yearly</option>
                            <option value="monthly">Monthly</option>
                            <option value="weekly">Weekly</option>
                            <option value="today">Today</option>
                        </select>
                    </div>
                </div>
                <div id="barChart" class="barChart mt-4"></div>
            </div>
        </div>
    </div>

    <!-- Side Charts -->
    <div class="col-xxl-4 col-lg-6">
        <div class="card h-100 radius-8 border-0">
            <div class="card-body p-24">
                <h6 class="mb-2 fw-bold text-lg">Statistic</h6>

                <!-- SemiCircle Gauge -->
                <div class="d-flex align-items-center gap-1 justify-content-between mb-4">
                    <div>
                        <span class="text-secondary-light fw-normal mb-1 text-xl">Daily Calls</span>
                        <h5 class="fw-semibold mb-0" id="semiGaugeLabel"><?php echo e($totalCallsToday); ?></h5>
                    </div>
                    <div id="semiCircleGauge"></div>
                </div>

                <!-- Area Chart -->
                <div class="d-flex align-items-center gap-1 justify-content-between mb-4">
                    <div>
                        <span class="text-secondary-light fw-normal mb-1 text-xl">Monthly Calls</span>
                        <h5 class="fw-semibold mb-0" id="areaChartLabel"><?php echo e(array_sum($callsPerMonth)); ?></h5>
                    </div>
                    <div id="areaChart"></div>
                </div>

                <!-- Daily Mini Bar Chart -->
                <div class="d-flex align-items-center gap-1 justify-content-between">
                    <div>
                        <span class="text-secondary-light fw-normal mb-1 text-xl">This Week</span>
                        <h5 class="fw-semibold mb-0" id="miniBarLabel"><?php echo e(array_sum($daysThisWeek)); ?></h5>
                    </div>
                    <div id="dailyIconBarChart"></div>
                </div>
            </div>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener("DOMContentLoaded", function () {

    const chartLabel = document.getElementById("chartLabel");
    const semiGaugeLabel = document.getElementById("semiGaugeLabel");
    const areaChartLabel = document.getElementById("areaChartLabel");
    const miniBarLabel = document.getElementById("miniBarLabel");

    const chartData = {
        yearly: {
            main: JSON.parse('<?php echo json_encode($callsPerMonth, 15, 512) ?>'),
            mainCategories: ["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"],
            area: JSON.parse('<?php echo json_encode($callsPerMonth, 15, 512) ?>'),
            mini: JSON.parse('<?php echo json_encode($daysThisWeek, 15, 512) ?>'),
            label: "Yearly overview",
            semi: $totalCallsToday 
        },
        monthly: {
            main: JSON.parse('<?php echo json_encode($callsPerHour, 15, 512) ?>'),
            mainCategories: JSON.parse('<?php echo json_encode($hourLabels, 15, 512) ?>'),
            area: JSON.parse('<?php echo json_encode($callsPerHour, 15, 512) ?>'),
            mini: JSON.parse('<?php echo json_encode($daysThisWeek, 15, 512) ?>'),
            label: "Monthly overview",
            semi: $totalCallsToday 
        },
        weekly: {
            main: JSON.parse('<?php echo json_encode($daysThisWeek, 15, 512) ?>'),
            mainCategories: JSON.parse('<?php echo json_encode($dayLabels, 15, 512) ?>'),
            area: JSON.parse('<?php echo json_encode($daysThisWeek, 15, 512) ?>'),
            mini: JSON.parse('<?php echo json_encode($daysThisWeek, 15, 512) ?>'),
            label: "Weekly overview",
            semi: $totalCallsToday 
        },
        today: {
            main: JSON.parse('<?php echo json_encode($callsPerHour, 15, 512) ?>'),
            mainCategories: JSON.parse('<?php echo json_encode($hourLabels, 15, 512) ?>'),
            area: JSON.parse('<?php echo json_encode($callsPerHour, 15, 512) ?>'),
            mini: JSON.parse('<?php echo json_encode($daysThisWeek, 15, 512) ?>'),
            label: "Today overview",
            semi: $totalCallsToday 
        }
    };

    // ===== ApexCharts setup (main, semi, area, mini) =====
    const mainChart = new ApexCharts(document.querySelector("#barChart"), {
        series: [{ name: "Calls", data: chartData.yearly.main }],
        chart: { type: "bar", height: 310, toolbar: { show: false } },
        plotOptions: { bar: { borderRadius: 4, columnWidth: "23%" } },
        dataLabels: { enabled: false },
        xaxis: { categories: chartData.yearly.mainCategories },
        yaxis: { labels: { formatter: val => val + " calls" } },
        tooltip: { y: { formatter: val => val + " calls" } }
    });
    mainChart.render();

    const semiGauge = new ApexCharts(document.querySelector("#semiCircleGauge"), {
        series: [chartData.yearly.semi],
        chart: { type: "radialBar", height: 165, width: 120 },
        plotOptions: { radialBar: { startAngle: -90, endAngle: 90, track: { background: "#E3E6E9" }, dataLabels: { show: false } } },
        fill: { type: "gradient", colors: ["#9DBAFF"], gradient: { gradientToColors: ["#487FFF"] } },
        stroke: { lineCap: "round" }
    });
    semiGauge.render();

    const areaChart = new ApexCharts(document.querySelector("#areaChart"), {
        series: [{ data: chartData.yearly.area }],
        chart: { type: "area", height: 72, sparkline: { enabled: true } },
        stroke: { curve: "smooth", width: 2, colors: ["#FF9F29"] },
        fill: { type: "gradient", gradient: { gradientToColors: ["#FF9F2900"], opacityFrom: 0.8, opacityTo: 0.3 } },
        xaxis: { categories: chartData.yearly.mainCategories }
    });
    areaChart.render();

    const miniBarChart = new ApexCharts(document.querySelector("#dailyIconBarChart"), {
        series: [{ data: chartData.yearly.mini }],
        chart: { type: "bar", height: 80, width: 164, sparkline: { enabled: true } },
        plotOptions: { bar: { borderRadius: 6, columnWidth: 14 } },
        fill: { type: "gradient", colors: ["#E3E6E9"] }
    });
    miniBarChart.render();

    // ===== Dropdown Change =====
    document.getElementById("timeFrameSelect").addEventListener("change", function () {
        const tf = this.value;
        const data = chartData[tf];

        mainChart.updateOptions({
            series: [{ data: data.main }],
            xaxis: { categories: data.mainCategories }
        });
        areaChart.updateOptions({ series: [{ data: data.area }], xaxis: { categories: data.mainCategories } });
        miniBarChart.updateOptions({ series: [{ data: data.mini }] });

        semiGauge.updateSeries([data.semi]);

        chartLabel.innerText = data.label;
        semiGaugeLabel.innerText = data.semi;
        areaChartLabel.innerText = data.area.reduce((a,b)=>a+b,0);
        miniBarLabel.innerText = data.mini.reduce((a,b)=>a+b,0);
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layout.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\wowdash\resources\views\reports\senior.blade.php ENDPATH**/ ?>