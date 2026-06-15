@extends('layout.layout')
@php
$title='Target -> Edit';
$role = auth()->user()->role ?? '';
if($role === 'admin'){
$subTitle = 'Super Admin';
} elseif ($role === 'operation') {
$subTitle = 'Operation Manager';
} else{
$subTitle = 'role';
}
$script = '<script>
    $(".remove-item-btn").on("click", function() {
        $(this).closest("tr").addClass("d-none")
    });
</script>';
@endphp

@section('content')

<!-- IP Management Section -->
<div class="card h-100 p-0 radius-12 mt-24">
    <div class="card-header border-bottom bg-base py-16 px-24 d-flex align-items-center flex-wrap gap-3 justify-content-between">
        <div>
            <span class="text-md fw-medium text-secondary-light mb-0">Allowed IP Addresses</span>
        </div>
        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addIpModal">
            Add IP
        </button>
    </div>

    <div class="card-body p-24">
        <div class="table-responsive scroll-sm">
            <table class="table bordered-table sm-table mb-0">
                <thead>
                    <tr>
                        <th>IP Address</th>
                        <th>Created At</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($allowedIps as $ip)
                    <tr>
                        <td>{{ $ip->ip_address }}</td>
                        <td>{{ $ip->created_at->format('Y-m-d H:i:s') }}</td>
                        <td class="text-center">
                            @if($ip->id !== $allowedIps->min('id'))
                            <form action="{{ route('target.deleteip', $ip->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                    Delete
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center">No IP addresses added.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add IP Modal -->
<div class="modal fade" id="addIpModal" tabindex="-1" aria-labelledby="addIpModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content radius-16 bg-base">
            <div class="modal-header py-16 px-24 border border-top-0 border-start-0 border-end-0">
                <h1 class="modal-title fs-5" id="addIpModalLabel">Add IP Address</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body p-24">
                <form action="{{ route('target.addip') }}" method="POST">
                    @csrf
                    <div class="mb-20">
                        <label class="form-label fw-semibold text-primary-light text-sm mb-8">IP Address</label>
                        <input type="text" class="form-control radius-8 @error('ip_address') is-invalid @enderror"
                            name="ip_address" placeholder="e.g., 192.168.1.1" required>
                        @error('ip_address')
                        <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="d-flex align-items-center justify-content-center gap-3 mt-24">
                        <button type="button" class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-40 py-11 radius-8" data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button type="submit" class="btn btn-primary border border-primary-600 text-md px-48 py-12 radius-8">
                            Add IP
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection