
<?php
    $title='Users Grid';
    $subTitle = 'Database';
    $script ='<script>
                        $(".remove-item-btn").on("click", function() {
                            $(this).closest("tr").addClass("d-none")
                        });
            </script>';
?>

<?php $__env->startSection('content'); ?>

            <div class="card h-100 p-0 radius-12 mt-4">
                <!-- Header -->
                <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center flex-wrap gap-3 justify-content-between">
                    <h5 class="text-primary m-0 d-flex align-items-center gap-2">
                        <i class="fab fa-google"></i>
                    </h5>
                    <!-- <a href="" class="btn btn-primary text-sm btn-sm px-12 py-12 radius-8 d-flex align-items-center gap-2">
                        <iconify-icon icon="ic:baseline-plus" class="icon text-xl line-height-1"></iconify-icon>
                        Fetch New Sheet
                    </a> -->
                </div>

                <div class="card-body p-24">
                    <!-- Flash & Validation Messages -->
                    <?php if(session('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle"></i> <?php echo e(session('success')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    <?php if(session('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle"></i> <?php echo e(session('error')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>
                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><i class="fas fa-times-circle"></i> <?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Fetch Google Sheet Form -->
                    <!-- <form method="POST" action="" class="d-flex flex-wrap gap-3 mb-4">
                        <?php echo csrf_field(); ?>
                        <input type="url" name="sheet_link" id="sheet_link"
                            class="form-control flex-grow-1 h-40-px"
                            placeholder="Paste Google Sheet URL" required>
                        <button type="submit" class="btn btn-primary d-flex align-items-center gap-2">
                            <i class="fas fa-sync"></i> Fetch
                        </button>
                    </form> -->

                    <!-- Data Table -->
                    <div class="table-responsive scroll-sm">
                        <?php if($data->isEmpty()): ?>
                            <p class="text-muted">No data found. Fetch a Google Sheet first.</p>
                        <?php else: ?>
                            <table class="table bordered-table sm-table mb-0 align-middle">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Row #</th>
                                        <?php $__currentLoopData = array_keys(json_decode($data->first()->data, true)); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $col): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <th><?php echo e($col); ?></th>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="sheet-table-body">
                                    <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php $decoded = json_decode($row->data, true); ?>
                                        <tr id="row-<?php echo e($row->id); ?>" data-id="<?php echo e($row->id); ?>">
                                            <td><?php echo e($row->id); ?></td>
                                            <td><?php echo e($row->sheet_row_number); ?></td>
                                            <?php $__currentLoopData = $decoded; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <td contenteditable="true" data-key="<?php echo e($key); ?>"><?php echo e($val); ?></td>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <td class="text-center">
                                                <button class="btn btn-sm btn-success save-btn" data-id="<?php echo e($row->id); ?>">
                                                    <i class="fas fa-save"></i> Save
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                </div>
            </div>


<?php $__env->stopSection(); ?>

<!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const tableBody = document.getElementById("sheet-table-body");

        // Auto-add row function
        function addBlankRow() {
            let colKeys = [];
            let firstRow = tableBody.querySelector("tr");
            if (firstRow) {
                firstRow.querySelectorAll("td[contenteditable=true]").forEach(cell => {
                    colKeys.push(cell.dataset.key);
                });
            }

            let newRow = document.createElement("tr");
            newRow.setAttribute("data-id", "new");

            let cells = `<td>New</td><td>—</td>`;
            colKeys.forEach(k => {
                cells += `<td contenteditable="true" data-key="${k}"></td>`;
            });
            cells += `
                <td>
                    <button class="btn btn-sm btn-success save-btn" data-id="new">
                        <i class="fas fa-save"></i> Save
                    </button>
                </td>
            `;

            newRow.innerHTML = cells;
            tableBody.appendChild(newRow);

            attachSaveHandler(newRow.querySelector(".save-btn"));
        }

        if (!tableBody.querySelector('tr[data-id="new"]')) {
            addBlankRow();
        }

        function attachSaveHandler(btn) {
            btn.addEventListener("click", function () {
                let saveBtn = this;
                let id = saveBtn.dataset.id;
                let row = saveBtn.closest("tr");

                // Disable button + show spinner
                saveBtn.disabled = true;
                let originalHTML = saveBtn.innerHTML;
                saveBtn.innerHTML = `<i class="fas fa-spinner fa-spin"></i> Saving...`;

                let updatedData = {};
                row.querySelectorAll("td[contenteditable=true]").forEach(cell => {
                    updatedData[cell.dataset.key] = cell.innerText.trim();
                });

                if (id === "new") {
                    fetch(`/dashboard/admin/google-sheet/store`, {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>",
                            "Content-Type": "application/json",
                        },
                        body: JSON.stringify({ rows: [updatedData] }),
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            let rowData = data.rows[0];

                            // Replace row content
                            let html = `
                                <td>${rowData.id}</td>
                                <td>${rowData.sheet_row_number}</td>
                            `;
                            Object.entries(rowData.data).forEach(([key, val]) => {
                                html += `<td contenteditable="true" data-key="${key}">${val}</td>`;
                            });
                            html += `
                                <td>
                                    <button class="btn btn-sm btn-success save-btn" data-id="${rowData.id}">
                                        <i class="fas fa-save"></i> Save
                                    </button>
                                </td>
                            `;

                            row.setAttribute("id", "row-" + rowData.id);
                            row.setAttribute("data-id", rowData.id);
                            row.innerHTML = html;

                            attachSaveHandler(row.querySelector(".save-btn"));
                            showMessage("✅ New row saved!", "success");

                            addBlankRow();
                        } else {
                            showMessage("❌ Failed to insert row", "danger");
                        }
                    })
                    .catch(() => showMessage("⚠️ Server error!", "danger"))
                    .finally(() => {
                        saveBtn.disabled = false;
                        saveBtn.innerHTML = originalHTML;
                    });

                } else {
                    fetch(`/dashboard/admin/google-sheet/update/${id}`, {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>",
                            "Content-Type": "application/json",
                        },
                        body: JSON.stringify({ data: updatedData, _method: "PATCH" }),
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            showMessage(" Row updated!", "success");
                        } else {
                            showMessage(" Update failed: " + (data.message || ""), "danger");
                        }
                    })
                    .catch(() => showMessage(" Server error!", "danger"))
                    .finally(() => {
                        saveBtn.disabled = false;
                        saveBtn.innerHTML = originalHTML;
                    });

                }
            });
        }


        document.querySelectorAll(".save-btn").forEach(btn => attachSaveHandler(btn));
    });

    // Flash message
    function showMessage(message, type) {
        let alertBox = document.createElement("div");
        alertBox.className = `alert alert-${type} alert-dismissible fade show mt-2`;
        alertBox.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.querySelector(".container-fluid").prepend(alertBox);
    }
</script>

<?php echo $__env->make('layout.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\wowdash\resources\views\database\admin.blade.php ENDPATH**/ ?>