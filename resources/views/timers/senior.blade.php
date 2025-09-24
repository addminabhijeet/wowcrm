@extends('layout.layout')

@php
$title = 'Senior Dashboard';
$subTitle = 'All Junior Timers';
@endphp

@section('content')
<div class="row gy-4">
    @foreach($timers as $timer)
        <div class="col-xxl-3 col-md-6 user-grid-card">
            <div class="position-relative border radius-16 overflow-hidden">
                <img src="{{ asset('assets/images/user-grid/user-grid-bg1.png') }}" class="w-100 object-fit-cover" alt="">

                <div class="ps-16 pb-16 pe-16 text-center mt--50">
                    <img src="{{ asset('assets/images/user-grid/user-grid-img1.png') }}" class="border br-white border-width-2-px w-100-px h-100-px rounded-circle object-fit-cover" alt="">
                    <h6 class="text-lg mb-0 mt-4">{{ $timer['name'] }}</h6>
                    <span class="text-secondary-light mb-16">{{ $timer['email'] }}</span>

                    <!-- Timer Widget -->
                    <div class="timer-widget" 
                        data-user="{{ $timer['user_id'] }}" 
                        data-remaining="{{ $timer['remaining_seconds'] }}" 
                        data-elapsed="{{ $timer['elapsed_seconds'] }}" 
                        data-status="{{ $timer['status'] }}"
                        style="display:flex;align-items:center;background:#fff;border:1px solid #ddd;border-radius:50px;padding:5px 8px;box-shadow:0 1px 3px rgba(0,0,0,0.08);flex-wrap:wrap;min-width:180px;">
                        
                        <!-- Countdown -->
                        <div style="margin-right:10px;text-align:center;min-width:60px;">
                            <div style="display:flex;align-items:center;justify-content:center;gap:2px;flex-wrap:wrap;">
                                <iconify-icon icon="mdi:timer-outline" style="color:#dc3545;font-size:14px;"></iconify-icon>
                                <small style="color:#6c757d;font-size:10px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">Countdown</small>
                            </div>
                            <span class="countdown" style="font-weight:bold;color:#212529;font-size:14px;display:block;margin-top:-2px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                {{ gmdate('H:i:s', $timer['remaining_seconds']) }}
                            </span>
                        </div>

                        <!-- Divider -->
                        <div style="width:1px;background:#dee2e6;margin:0 4px;"></div>

                        <!-- Elapsed -->
                        <div style="margin-right:10px;text-align:center;min-width:60px;">
                            <div style="display:flex;align-items:center;justify-content:center;gap:2px;flex-wrap:wrap;">
                                <iconify-icon icon="mdi:clock-outline" style="color:#28a745;font-size:14px;"></iconify-icon>
                                <small style="color:#6c757d;font-size:10px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">Elapsed</small>
                            </div>
                            <span class="elapsed" style="font-weight:bold;color:#212529;font-size:14px;display:block;margin-top:-2px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                                {{ gmdate('H:i:s', $timer['elapsed_seconds']) }}
                            </span>
                        </div>

                        <!-- Control Buttons -->
                        <div class="controlButtons" style="display:flex;align-items:center;gap:4px;flex-wrap:wrap;">
                            <button data-type="resume" style="width:65px;height:28px;border-radius:14px;background:#d4edda;border:1px solid #28a745;display:flex;align-items:center;justify-content:center;font-size:12px;color:#28a745;">
                                <iconify-icon icon="mdi:play" style="margin-right:2px;font-size:14px;"></iconify-icon>Resume
                            </button>
                            <button data-type="lunch" style="width:65px;height:28px;border-radius:14px;background:#f8f9fa;border:1px solid #ddd;display:flex;align-items:center;justify-content:center;font-size:12px;color:#ffc107;">
                                <iconify-icon icon="mdi:food" style="margin-right:2px;font-size:14px;"></iconify-icon>Lunch
                            </button>
                            <button data-type="tea" style="width:65px;height:28px;border-radius:14px;background:#f8f9fa;border:1px solid #ddd;display:flex;align-items:center;justify-content:center;font-size:12px;color:#8b4513;">
                                <iconify-icon icon="mdi:coffee" style="margin-right:2px;font-size:14px;"></iconify-icon>Tea
                            </button>
                            <button data-type="break" style="width:65px;height:28px;border-radius:14px;background:#f8f9fa;border:1px solid #ddd;display:flex;align-items:center;justify-content:center;font-size:12px;color:#007bff;">
                                <iconify-icon icon="mdi:pause" style="margin-right:2px;font-size:14px;"></iconify-icon>Break
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection



<div id="statusOverlay"></div>


@section('scripts')
<script>
function formatTime(sec){
    sec = Math.floor(sec);
    const h = Math.floor(sec / 3600);
    const m = Math.floor((sec % 3600) / 60);
    const s = sec % 60;
    return `${h.toString().padStart(2,'0')}:${m.toString().padStart(2,'0')}:${s.toString().padStart(2,'0')}`;
}

function startJuniorTimers(){
    document.querySelectorAll('.timer-widget').forEach(widget => {
        let remaining = parseInt(widget.dataset.remaining);
        let elapsed = parseInt(widget.dataset.elapsed);
        let status = widget.dataset.status;
        const userId = widget.dataset.user;

        // Tick every second locally
        setInterval(() => {
            if(status === 'running' && remaining > 0){
                remaining--;
                elapsed++;
                widget.dataset.remaining = remaining;
                widget.dataset.elapsed = elapsed;

                widget.querySelector('.countdown').innerText = formatTime(remaining);
                widget.querySelector('.elapsed').innerText = formatTime(elapsed);

                if(remaining <= 0){
                    status = 'paused';
                    alert(userId + " has finished their 9-hour session.");
                }
            }
        }, 1000);

        // Sync with backend every 5 seconds
        setInterval(() => {
            fetch("{{ route('timer.updatejunior') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ user_id: userId, action: 'tick' })
            })
            .then(res => res.json())
            .then(data => {
                remaining = data.remaining_seconds;
                elapsed = data.elapsed_seconds;
                status = data.status;

                widget.dataset.remaining = remaining;
                widget.dataset.elapsed = elapsed;
                widget.dataset.status = status;

                widget.querySelector('.countdown').innerText = formatTime(remaining);
                widget.querySelector('.elapsed').innerText = formatTime(elapsed);

                if(data.logout){
                    alert(userId + " has finished their 9-hour session.");
                }
            });
        }, 5000);

        // Control buttons
        widget.querySelectorAll('button').forEach(btn => {
            btn.addEventListener('click', () => {
                const action = btn.dataset.type;
                fetch("{{ route('timer.updatejunior') }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({ user_id: userId, action })
                })
                .then(res => res.json())
                .then(data => {
                    remaining = data.remaining_seconds;
                    elapsed = data.elapsed_seconds;
                    status = data.status;

                    widget.dataset.remaining = remaining;
                    widget.dataset.elapsed = elapsed;
                    widget.dataset.status = status;

                    widget.querySelector('.countdown').innerText = formatTime(remaining);
                    widget.querySelector('.elapsed').innerText = formatTime(elapsed);

                    if(data.logout){
                        alert(userId + " has finished their 9-hour session.");
                    }
                });
            });
        });
    });
}

// Start timers
startJuniorTimers();

</script>
@endsection
