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
                        <h5>{{ __('default.Help Articles') }}</h5>
                        <a href="{{ route('helps.create') }}" class="btn btn-primary">
                            {{ __('Create Help Article') }}
                        </a>
                    </div>

                    <!-- Articles List -->
                    <div class="card">
                        <div class="card-body">
                            @if($helps->isEmpty())
                                <p class="text-center my-3">{{ __('No helps articles found') }}</p>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                        <tr>
                                            <th>{{ __('default.Title') }}</th>
                                            <th>{{ __('default.Author') }}</th>
                                            <th>{{ __('default.Status') }}</th>
                                            <th>{{ __('default.Published At') }}</th>
                                            <th>{{ __('default.Created At') }}</th>
                                            <th>{{ __('default.Actions') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($helps as $help)
                                            <tr>
                                                <td style="vertical-align: middle;">{{ Str::limit($help->title, 50) }}</td>
                                                <td style="vertical-align: middle;">{{ $help->user->name }}</td>
                                                <td style="vertical-align: middle;">
                                                    <input class="statusToggle" type="checkbox" {{ $help->is_published ? 'checked' : '' }} data-id="{{$help->id}}" data-toggle="toggle" data-size="sm" data-onlabel="Published" data-offlabel="Draft" data-onstyle="success" data-offstyle="warning">
                                                </td>
                                                <td id="published_at_{{$help->id}}" style="vertical-align: middle;">{{ ($help->published_at) ? $help->published_at->format('Y-m-d H:i') : '' }}</td>
                                                <td style="vertical-align: middle;">{{ $help->created_at->format('Y-m-d H:i') }}</td>
                                                <td style="vertical-align: middle;">
                                                    <form action="{{ route('helps.destroy', $help->id) }}"
                                                          method="POST"
                                                          class="d-inline"
                                                          onsubmit="return confirm('{{ __('default.Are you sure you want to delete this log?') }}')">
                                                        <div class="btn-group" role="group">
                                                            <a href="{{ route('helps.edit', $help->id) }}" class="btn btn-sm btn-primary">
                                                                {{ __('default.Edit') }}
                                                            </a>
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger">
                                                                {{ __('default.Delete') }}
                                                            </button>
                                                        </div>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Pagination -->
                                <div class="d-flex justify-content-center mt-4">
                                    {{ $helps->links('pagination::bootstrap-5') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @include('layouts.footer')
@endsection
@push('scripts')
    <script>
        $(document).ready(function () {
            $('.statusToggle').change(function () {
                var id=$(this).attr('data-id');
                $.ajax({
                    url: '/helps/togglePublished/' + $(this).attr('data-id'),
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        id: id,
                        is_published: $(this).prop('checked')
                    },
                    dataType: 'JSON',
                    success: function (response) {
                        document.querySelector('#published_at_' + id).innerHTML = response.time;
                    },
                    error: function (xhr) {
                    },
                    complete: function () {
                    }
                });
            })
        });

    </script>
@endpush
