
<?php
    $title='Line Chart';
    $subTitle = 'Components / Line Chart';
    $script = ' <script src="' . asset('assets/js/lineChartPageChart.js') . '"></script>';
?>

<?php $__env->startSection('content'); ?>

            <div class="row gy-4">
                <div class="col-md-6">
                    <div class="card h-100 p-0">
                        <div class="card-header border-bottom bg-base py-16 px-24">
                            <h6 class="text-lg fw-semibold mb-0">Default Line Chart</h6>
                        </div>
                        <div class="card-body p-24">
                            <div id="defaultLineChart" class="apexcharts-tooltip-style-1"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100 p-0">
                        <div class="card-header border-bottom bg-base py-16 px-24">
                            <h6 class="text-lg fw-semibold mb-0">Zoomable Chart</h6>
                        </div>
                        <div class="card-body p-24">
                            <div id="zoomAbleLineChart"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100 p-0">
                        <div class="card-header border-bottom bg-base py-16 px-24">
                            <h6 class="text-lg fw-semibold mb-0">Line Chart with Data Labels</h6>
                        </div>
                        <div class="card-body p-24">
                            <div id="lineDataLabel"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100 p-0">
                        <div class="card-header border-bottom bg-base py-16 px-24">
                            <h6 class="text-lg fw-semibold mb-0">Line Chart Animation</h6>
                        </div>
                        <div class="card-body p-24">
                            <div id="doubleLineChart"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100 p-0">
                        <div class="card-header border-bottom bg-base py-16 px-24">
                            <h6 class="text-lg fw-semibold mb-0">Stepline Charts</h6>
                        </div>
                        <div class="card-body p-24">
                            <div id="stepLineChart"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100 p-0">
                        <div class="card-header border-bottom bg-base py-16 px-24">
                            <h6 class="text-lg fw-semibold mb-0">Gradient Charts</h6>
                        </div>
                        <div class="card-body p-24">
                            <div id="gradientLineChart"></div>
                        </div>
                    </div>
                </div>
            </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\wowdash\resources\views\chart\lineChart.blade.php ENDPATH**/ ?>