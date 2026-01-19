@extends('layout.layout')
@php
    $title = 'Report -> Trainer';
    $role = auth()->user()->role ?? '';
    if ($role === 'admin') {
        $subTitle = 'Super Admin';
    } elseif ($role === 'operation') {
        $subTitle = 'Operation Manager';
    } else {
        $subTitle = 'role';
    }
    $script = '<script>
        $(".remove-item-btn").on("click", function() {
            $(this).closest("tr").addClass("d-none")
        });
    </script>';
@endphp

@section('content')
    <div class="user-select-none">
        <div class="card h-100 p-0 radius-12">
            <div class="card-header border-bottom bg-base py-16 px-24">
                <div class="d-flex flex-wrap gap-3 align-items-center justify-content-between">
                    <div class="d-flex flex-wrap gap-3 align-items-center">
                        <div class="d-flex align-items-center gap-2">
                            <label for="selected_date" class="fw-semibold small mb-0">Select Date:</label>
                            <input type="date" id="selected_date" value="{{ request('selected_date', date('Y-m-d')) }}"
                                class="form-control form-control-sm">
                        </div>
                        <button class="btn btn-danger btn-sm" id="downloadPdfBtn">
                            <i class="bi bi-file-earmark-pdf-fill me-1"></i> Download Merged PDF
                        </button>
                    </div>
                </div>
            </div>

            <div class="card-body p-24">
                <div class="table-responsive scroll-sm">
                    <table class="table bordered-table sm-table mb-0 align-middle">
                        <thead>
                            <tr>

                                <th>S.L</th>
                                <th>Name</th>

                                <th>Role</th>
                                <th class="text-center" style="width:40px;">
                                    <div class="form-check d-flex justify-content-center">
                                        <input class="form-check-input" type="checkbox" id="selectAllUsers"
                                            style="opacity:1; position:static;">
                                    </div>
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($juniorUsers as $index => $user)
                                <tr>

                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $user->name }}</td>
                                 
                                    <td>
                                        {{ $user->role === 'junior' ? 'IT Recruiter' : ucfirst($user->role) }}
                                    </td>
                                    <td class="text-center">
                                        <div class="form-check d-flex justify-content-center">
                                            <input class="form-check-input user-checkbox" type="checkbox" name="users[]"
                                                value="{{ $user->id }}" data-role="{{ $user->role }}">
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const selectAll = document.getElementById("selectAllUsers");

            selectAll.addEventListener("change", function() {
                document.querySelectorAll(".user-checkbox").forEach(cb => {
                    cb.checked = selectAll.checked;
                });
            });

        });
    </script>

    <script>
        document.getElementById('selectAllUsers').addEventListener('change', function() {
            document.querySelectorAll('.user-checkbox')
                .forEach(cb => cb.checked = this.checked);
        });

        document.getElementById("downloadPdfBtn").addEventListener("click", async function() {

            const selectedDate = document.getElementById("selected_date").value;
            const selectedUsers = [...document.querySelectorAll(".user-checkbox:checked")];

            if (!selectedUsers.length) {
                alert("Please select at least one user");
                return;
            }

            const {
                jsPDF
            } = window.jspdf;
            const mergedPdf = new jsPDF('p', 'px', 'a4');

            let firstPage = true;

            for (const checkbox of selectedUsers) {

                const userId = checkbox.value;
                const role = checkbox.dataset.role;

                const url =
                    role === 'senior' ?
                    `/dashboard/allseniordaily/call-reports/${userId}?selected_date=${selectedDate}` :
                    `/dashboard/alljuniordaily/call-reports/${userId}?selected_date=${selectedDate}`;

                // Load report silently
                const iframe = document.createElement("iframe");
                iframe.style.position = "absolute";
                iframe.style.left = "-9999px";
                iframe.src = url;
                document.body.appendChild(iframe);

                await new Promise(resolve => iframe.onload = resolve);

                const content = iframe.contentDocument.getElementById("pdfContent");

                const pdfBlob = await html2pdf()
                    .from(content)
                    .set({
                        margin: 0,
                        image: {
                            type: "jpeg",
                            quality: 0.98
                        },
                        html2canvas: {
                            scale: 3,
                            useCORS: true
                        },
                        jsPDF: {
                            unit: "px",
                            format: "a4",
                            orientation: "portrait"
                        }
                    })
                    .outputPdf("blob");

                const arrayBuffer = await pdfBlob.arrayBuffer();
                const tempPdf = new jsPDF();
                const pages = tempPdf.loadFile(arrayBuffer);

                const tempDoc = await jsPDF.load(arrayBuffer);

                tempDoc.getPages().forEach((page, index) => {
                    if (!firstPage) mergedPdf.addPage();
                    mergedPdf.addImage(
                        page.getImageData(),
                        'JPEG',
                        0,
                        0,
                        mergedPdf.internal.pageSize.width,
                        mergedPdf.internal.pageSize.height
                    );
                    firstPage = false;
                });

                document.body.removeChild(iframe);
            }

            mergedPdf.save(`Merged_Report_${selectedDate}.pdf`);
        });
    </script>
@endsection
