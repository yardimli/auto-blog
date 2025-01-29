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
                        <h5>{{ __('default.Change Logs') }}</h5>
                        <a href="{{ route('changelogs.create') }}" class="btn btn-primary">
                            {{ __('default.Create Change Log') }}
                        </a>
                    </div>

                    <!-- Articles List -->
                    <div class="card">
                        <div class="card-body">
                            @if($changelogs->isEmpty())
                                <p class="text-center my-3">{{ __('default.No changelogs found') }}</p>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                        <tr>
                                            <th>{{ __('default.Title') }}</th>
                                            <th>{{ __('default.Author') }}</th>
                                            <th>{{ __('default.Status') }}</th>
                                            <th>{{ __('default.Released At') }}</th>
                                            <th>{{ __('default.Created At') }}</th>
                                            <th>{{ __('default.Actions') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($changelogs as $changelog)
                                            <tr>
                                                <td>{{ Str::limit($changelog->title, 50) }}</td>
                                                <td>{{ $changelog->user->name }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $changelog->is_released ? 'success' : 'warning' }}">
                                                        {{ $changelog->is_released ? __('default.Released') : __('default.Draft') }}
                                                    </span>
                                                </td>
                                                <td>{{ ($changelog->released_at) ? $changelog->release_at->format('Y-m-d H:i') : '' }}</td>
                                                <td>{{ $changelog->created_at->format('Y-m-d H:i') }}</td>
                                                <td>
                                                    <div class="btn-group">
                                                        <a href="{{ route('changelogs.edit', $changelog->id) }}"
                                                           class="btn btn-sm btn-primary">
                                                            {{ __('default.Edit') }}
                                                        </a>
                                                        <form action="{{ route('changelogs.destroy', $changelog->id) }}"
                                                              method="POST"
                                                              class="d-inline"
                                                              onsubmit="return confirm('{{ __('default.Are you sure you want to delete this log?') }}')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger">
                                                                {{ __('default.Delete') }}
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Pagination -->
                                <div class="d-flex justify-content-center mt-4">
                                    {{ $changelogs->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
