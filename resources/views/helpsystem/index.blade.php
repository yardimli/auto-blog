@extends('layouts.app')

@section('content')
    <main>
        <div class="container" style="min-height: calc(88vh);">

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row mt-3">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5>{{ __('default.Help System') }}</h5>
                    </div>

                    <!-- Help System Entries List -->
                </div>
            </div>
        </div>
    </main>
@endsection
@push('scripts')
<script>
    $(document).ready(function () {
    });

</script>
@endpush
