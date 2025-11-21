<a href="{{ route('admin.notifications') }}" 
   class="px-24 py-12 d-flex align-items-start gap-3 mb-2 justify-content-between"
   style="
        text-decoration:none;
        color:inherit;
        background:rgba(255,255,255,0.12);
        backdrop-filter:blur(14px) saturate(200%);
        -webkit-backdrop-filter:blur(14px) saturate(200%);
        border-radius:16px;
        border:1px solid rgba(255,255,255,0.35);
        box-shadow:
            0 6px 18px rgba(0,0,0,0.12),
            inset 0 0 12px rgba(255,255,255,0.20);
        transition:0.25s ease;
        cursor:pointer;
   "
   onmouseover="this.style.background='rgba(255,255,255,0.22)'; this.style.boxShadow='0 10px 22px rgba(0,0,0,0.18), inset 0 0 14px rgba(255,255,255,0.28)';"
   onmouseout="this.style.background='rgba(255,255,255,0.12)'; this.style.boxShadow='0 6px 18px rgba(0,0,0,0.12), inset 0 0 12px rgba(255,255,255,0.20)';"
>

    <div class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">

        <div>
            <h6 class="text-md fw-semibold mb-4" 
                style="
                    margin:0;
                    padding:0;
                    line-height:1.5;
                    font-weight:600;
                ">
                {!! nl2br(e($msg)) !!}
            </h6>
        </div>
    </div>

    <span class="text-sm text-secondary-light flex-shrink-0"
          style="
                opacity:0.75;
                backdrop-filter:blur(4px);
                -webkit-backdrop-filter:blur(4px);
                padding:4px 10px;
                border-radius:10px;
          ">
        Just now
    </span>

</a>
