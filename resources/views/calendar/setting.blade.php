@extends('layout.layout')

@php
    $title = 'Calendar Setting';
    $role = auth()->user()->role ?? '';
    if ($role === 'admin') {
        $subTitle = 'Super Admin';
    } elseif ($role === 'operation') {
        $subTitle = 'Operation Manager';
    } else {
        $subTitle = 'role';
    }
@endphp

@section('content')
    <div id="pdfContent">
        <div class="row mb-3">
            <div class="col-md-4">
                <label class="fw-bold mb-1">Select Month & Year</label>

                <div class="input-group">
                    <input type="text" id="calendarPicker" class="form-control" placeholder="Select Month" readonly>
                    <button class="btn btn-primary" type="button" id="openCalendar">
                        Select
                    </button>
                </div>
            </div>

        </div>


        <div class="row gy-4 mt-1">
            <div class="col-12">
                <div class="card h-100 border-0 shadow-sm radius-12">
                    <div class="card-body p-4">

                        <div class="row">
                            <!-- ================= FIRST TABLE : 1 - 16 ================= -->
                            <div class="col-md-6">
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered align-middle mb-0">
                                        <thead class="table-primary">
                                            <tr>
                                                <th class="fw-bold">Date</th>
                                                <th class="fw-bold text-center">Holiday</th>
                                            </tr>
                                        </thead>
                                        <tbody id="firstHalf"></tbody>


                                        <tr>
                                            <td>01</td>
                                            <td class="text-center">

                                                <div class="form-check d-inline-block ms-2">
                                                    <input class="form-check-input" type="checkbox">
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>02</td>
                                            <td class="text-center">

                                                <div class="form-check d-inline-block ms-2">
                                                    <input class="form-check-input" type="checkbox">
                                                </div>
                                            </td>

                                        </tr>

                                        <tr>
                                            <td>03</td>
                                            <td class="text-center">

                                                <div class="form-check d-inline-block ms-2">
                                                    <input class="form-check-input" type="checkbox">
                                                </div>
                                            </td>

                                        </tr>

                                        <tr>
                                            <td>04</td>
                                            <td class="text-center">

                                                <div class="form-check d-inline-block ms-2">
                                                    <input class="form-check-input" type="checkbox">
                                                </div>
                                            </td>

                                        </tr>

                                        <tr>
                                            <td>05</td>
                                            <td class="text-center">

                                                <div class="form-check d-inline-block ms-2">
                                                    <input class="form-check-input" type="checkbox">
                                                </div>
                                            </td>

                                        </tr>

                                        <tr>
                                            <td>06</td>
                                            <td class="text-center">

                                                <div class="form-check d-inline-block ms-2">
                                                    <input class="form-check-input" type="checkbox">
                                                </div>
                                            </td>

                                        </tr>

                                        <tr>
                                            <td>07</td>
                                            <td class="text-center">

                                                <div class="form-check d-inline-block ms-2">
                                                    <input class="form-check-input" type="checkbox">
                                                </div>
                                            </td>

                                        </tr>

                                        <tr>
                                            <td>08</td>
                                            <td class="text-center">

                                                <div class="form-check d-inline-block ms-2">
                                                    <input class="form-check-input" type="checkbox">
                                                </div>
                                            </td>

                                        </tr>

                                        <tr>
                                            <td>09</td>
                                            <td class="text-center">

                                                <div class="form-check d-inline-block ms-2">
                                                    <input class="form-check-input" type="checkbox">
                                                </div>
                                            </td>

                                        </tr>

                                        <tr>
                                            <td>10</td>
                                            <td class="text-center">

                                                <div class="form-check d-inline-block ms-2">
                                                    <input class="form-check-input" type="checkbox">
                                                </div>
                                            </td>

                                        </tr>

                                        <tr>
                                            <td>11</td>
                                            <td class="text-center">

                                                <div class="form-check d-inline-block ms-2">
                                                    <input class="form-check-input" type="checkbox">
                                                </div>
                                            </td>

                                        </tr>

                                        <tr>
                                            <td>12</td>
                                            <td class="text-center">

                                                <div class="form-check d-inline-block ms-2">
                                                    <input class="form-check-input" type="checkbox">
                                                </div>
                                            </td>

                                        </tr>

                                        <tr>
                                            <td>13</td>
                                            <td class="text-center">

                                                <div class="form-check d-inline-block ms-2">
                                                    <input class="form-check-input" type="checkbox">
                                                </div>
                                            </td>

                                        </tr>

                                        <tr>
                                            <td>14</td>
                                            <td class="text-center">

                                                <div class="form-check d-inline-block ms-2">
                                                    <input class="form-check-input" type="checkbox">
                                                </div>
                                            </td>

                                        </tr>

                                        <tr>
                                            <td>15</td>
                                            <td class="text-center">

                                                <div class="form-check d-inline-block ms-2">
                                                    <input class="form-check-input" type="checkbox">
                                                </div>
                                            </td>

                                        </tr>

                                        <tr>
                                            <td>16</td>
                                            <td class="text-center">

                                                <div class="form-check d-inline-block ms-2">
                                                    <input class="form-check-input" type="checkbox">
                                                </div>
                                            </td>

                                        </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>



                            <!-- ================= SECOND TABLE : 17 - 31 ================= -->
                            <div class="col-md-6">
                                <div class="table-responsive">
                                    <table class="table table-hover table-bordered align-middle mb-0">
                                        <thead class="table-primary">
                                            <tr>
                                                <th class="fw-bold">Date</th>
                                                <th class="fw-bold text-center">Holiday</th>
                                            </tr>
                                        </thead>
                                        <tbody id="secondHalf"></tbody>


                                        <tr>
                                            <td>17</td>
                                            <td class="text-center">

                                                <div class="form-check d-inline-block ms-2">
                                                    <input class="form-check-input" type="checkbox">
                                                </div>
                                            </td>

                                        </tr>

                                        <tr>
                                            <td>18</td>
                                            <td class="text-center">

                                                <div class="form-check d-inline-block ms-2">
                                                    <input class="form-check-input" type="checkbox">
                                                </div>
                                            </td>

                                        </tr>

                                        <tr>
                                            <td>19</td>
                                            <td class="text-center">

                                                <div class="form-check d-inline-block ms-2">
                                                    <input class="form-check-input" type="checkbox">
                                                </div>
                                            </td>

                                        </tr>

                                        <tr>
                                            <td>20</td>
                                            <td class="text-center">

                                                <div class="form-check d-inline-block ms-2">
                                                    <input class="form-check-input" type="checkbox">
                                                </div>
                                            </td>

                                        </tr>

                                        <tr>
                                            <td>21</td>
                                            <td class="text-center">

                                                <div class="form-check d-inline-block ms-2">
                                                    <input class="form-check-input" type="checkbox">
                                                </div>
                                            </td>

                                        </tr>

                                        <tr>
                                            <td>22</td>
                                            <td class="text-center">

                                                <div class="form-check d-inline-block ms-2">
                                                    <input class="form-check-input" type="checkbox">
                                                </div>
                                            </td>

                                        </tr>

                                        <tr>
                                            <td>23</td>
                                            <td class="text-center">

                                                <div class="form-check d-inline-block ms-2">
                                                    <input class="form-check-input" type="checkbox">
                                                </div>
                                            </td>

                                        </tr>

                                        <tr>
                                            <td>24</td>
                                            <td class="text-center">

                                                <div class="form-check d-inline-block ms-2">
                                                    <input class="form-check-input" type="checkbox">
                                                </div>
                                            </td>

                                        </tr>

                                        <tr>
                                            <td>25</td>
                                            <td class="text-center">

                                                <div class="form-check d-inline-block ms-2">
                                                    <input class="form-check-input" type="checkbox">
                                                </div>
                                            </td>

                                        </tr>

                                        <tr>
                                            <td>26</td>
                                            <td class="text-center">

                                                <div class="form-check d-inline-block ms-2">
                                                    <input class="form-check-input" type="checkbox">
                                                </div>
                                            </td>

                                        </tr>

                                        <tr>
                                            <td>27</td>
                                            <td class="text-center">

                                                <div class="form-check d-inline-block ms-2">
                                                    <input class="form-check-input" type="checkbox">
                                                </div>
                                            </td>

                                        </tr>

                                        <tr>
                                            <td>28</td>
                                            <td class="text-center">

                                                <div class="form-check d-inline-block ms-2">
                                                    <input class="form-check-input" type="checkbox">
                                                </div>
                                            </td>

                                        </tr>

                                        <tr>
                                            <td>29</td>
                                            <td class="text-center">

                                                <div class="form-check d-inline-block ms-2">
                                                    <input class="form-check-input" type="checkbox">
                                                </div>
                                            </td>

                                        </tr>

                                        <tr>
                                            <td>30</td>
                                            <td class="text-center">

                                                <div class="form-check d-inline-block ms-2">
                                                    <input class="form-check-input" type="checkbox">
                                                </div>
                                            </td>

                                        </tr>

                                        <tr>
                                            <td>31</td>
                                            <td class="text-center">

                                                <div class="form-check d-inline-block ms-2">
                                                    <input class="form-check-input" type="checkbox">
                                                </div>
                                            </td>

                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $('#calendarPicker').datepicker({
            format: "MM yyyy",
            minViewMode: 1,
            autoclose: true
        }).on('changeDate', function(e) {
            generateDates(e.date);
        });

        function generateDates(selectedDate) {
            const month = selectedDate.getMonth() + 1;
            const year = selectedDate.getFullYear();
            const daysInMonth = new Date(year, month, 0).getDate();

            const firstHalf = document.getElementById('firstHalf');
            const secondHalf = document.getElementById('secondHalf');

            firstHalf.innerHTML = '';
            secondHalf.innerHTML = '';

            for (let day = 1; day <= daysInMonth; day++) {
                const fullDate = `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;

                const row = `
                <tr>
                    <td>${String(day).padStart(2, '0')}</td>
                    <td class="text-center">
                        <div class="form-check d-inline-block ms-2">
                            <input class="form-check-input holiday-checkbox"
                                   type="checkbox"
                                   data-date="${fullDate}">
                        </div>
                    </td>
                </tr>
            `;

                if (day <= 16) {
                    firstHalf.insertAdjacentHTML('beforeend', row);
                } else {
                    secondHalf.insertAdjacentHTML('beforeend', row);
                }
            }

            attachCheckboxEvents();
        }

        function attachCheckboxEvents() {
            document.querySelectorAll('.holiday-checkbox').forEach(cb => {
                cb.addEventListener('change', function() {
                    fetch('/holiday/save', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            date: this.dataset.date,
                            is_holiday: this.checked ? 1 : 0
                        })
                    });
                });
            });
        }
    </script>
    <script>
        document.getElementById('openCalendar').addEventListener('click', function() {
            $('#calendarPicker').datepicker('show');
        });
    </script>



    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
@endsection
