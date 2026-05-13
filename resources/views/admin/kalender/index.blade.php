@extends('layouts.sneat.app')

@section('title', 'Kalender Kegiatan')

@section('content')
<h4 class="fw-bold py-3 mb-4">
    <span class="text-muted fw-light">Dashboard /</span> Kalender
</h4>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Kalender Kerja</h5>
                <span class="badge bg-label-primary p-2" id="full-cal-month-year"></span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered text-center" style="min-width: 600px;">
                        <thead class="table-light">
                            <tr>
                                <th width="14.28%">Senin</th>
                                <th width="14.28%">Selasa</th>
                                <th width="14.28%">Rabu</th>
                                <th width="14.28%">Kamis</th>
                                <th width="14.28%">Jumat</th>
                                <th width="14.28%">Sabtu</th>
                                <th width="14.28%">Minggu</th>
                            </tr>
                        </thead>
                        <tbody id="full-calendar-body">
                            </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('page-script')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        function renderFullCalendar() {
            const now = new Date();
            const month = now.getMonth();
            const year = now.getFullYear();
            const today = now.getDate();
            const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

            document.getElementById('full-cal-month-year').innerText = monthNames[month] + " " + year;

            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();
            const calendarBody = document.getElementById('full-calendar-body');
            
            calendarBody.innerHTML = '';
            let date = 1;
            let startingDay = firstDay === 0 ? 6 : firstDay - 1;

            for (let i = 0; i < 6; i++) {
                let row = document.createElement("tr");
                for (let j = 0; j < 7; j++) {
                    let cell = document.createElement("td");
                    cell.style.height = "80px";
                    cell.style.verticalAlign = "top";

                    if (i === 0 && j < startingDay) {
                        cell.innerHTML = "";
                    } else if (date > daysInMonth) {
                        break;
                    } else {
                        if (date === today) {
                            cell.innerHTML = `<span class="badge rounded-pill bg-primary mb-1">${date}</span><br><small class="text-muted">Hari Ini</small>`;
                            cell.classList.add('bg-label-primary');
                        } else {
                            cell.innerHTML = `<strong>${date}</strong>`;
                        }
                        date++;
                    }
                    row.appendChild(cell);
                }
                calendarBody.appendChild(row);
                if (date > daysInMonth) break;
            }
        }
        renderFullCalendar();
    });
</script>
@endpush