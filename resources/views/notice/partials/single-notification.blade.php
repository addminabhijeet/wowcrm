<a href="javascript:void(0)" class="px-24 py-12 d-flex align-items-start gap-3 mb-2 justify-content-between">

    <div class="text-black hover-bg-transparent hover-text-primary d-flex align-items-center gap-3">
        <span class="w-44-px h-44-px bg-success-subtle text-success-main rounded-circle d-flex justify-content-center align-items-center flex-shrink-0">
            <iconify-icon icon="bitcoin-icons:verify-outline" class="icon text-xxl"></iconify-icon>
        </span>

        <div>
            <h6 class="text-md fw-semibold mb-4">
                {{ $msg ?? 'New Notification' }}
            </h6>

            {{-- Candidate / User Info --}}
            <p class="mb-0 text-sm text-secondary-light text-w-200-px">
                <strong>User:</strong> {{ $userName }} ({{ $userEmail }}) <br>

                @if($candidateName)
                    <strong>Candidate:</strong> {{ $candidateName }} <br>
                @endif

                @if($candidateEmail)
                    <strong>Email:</strong> {{ $candidateEmail }} <br>
                @endif

                @if($candidatePhone)
                    <strong>Phone:</strong> {{ $candidatePhone }} <br>
                @endif

                @if($candidateCourse)
                    <strong>Course:</strong> {{ $candidateCourse }} <br>
                @endif
            </p>
        </div>
    </div>

    <span class="text-sm text-secondary-light flex-shrink-0">
        Just now
    </span>

</a>
