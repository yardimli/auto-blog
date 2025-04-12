@extends('layouts.app')
@section('title', __('Create Change Log'))
@section('content')
    <!-- Main content START -->
    <main>
        <div class="container mb-5" style="min-height: calc(88vh);">
            <div class="row mt-3">
                <!-- Main content START -->
                <div class="col-12 mx-auto">
                    <h5>{{ isset($changelog) ? __('default.Edit Change Log') : __('default.Create Change Log') }}</h5>

                    <form id="articleForm" method="POST"
                          action="{{ isset($changelog) ? route('changelogs.update', $changelog->id) : route('changelogs.store') }}">
                        @csrf
                        @if(isset($changelog))
                            @method('PUT')
                        @endif
                        <!-- Title -->
                        <div class="mb-3">
                            <label for="title" class="form-label">{{ __('default.Title') }}</label>
                            <input type="text" class="form-control" id="title" name="title"
                               value="{{ isset($changelog) ? $changelog->title : old('title') }}" required>
                        </div>

                        {{--   Change Log  --}}
                        <div class="mb-3">
                            <div id="editormd" class="editormd">
                                <textarea name="body" style="display:none;">
{{ isset($changelog) ? $changelog->body : '### Changelog.md
**Markdown Editor**: Write down your change log here.' }}
                                </textarea>
                            </div>
                        </div>

                        <div class="mb-3">
                            <button id="{{isset($changelog) ? 'updateChangeLog' : 'createChangeLog'}}" type="submit" class="btn btn-primary">
                                {{ isset($changelog) ? __('default.Save Change Log') : __('default.Create Change Log') }}
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </main>

    @include('layouts.footer')
@endsection

<script src="/js/jquery-3.7.0.min.js"></script>
<script src="/editormd/editormd.min.js"></script>
<script src="/editormd/languages/en.js"></script>

<link rel="stylesheet" href="/editormd/css/editormd.css" />

@push('scripts')

    <style>
        html[data-bs-theme="dark"] .editor-toolbar a {
            color: white !important;
        }
        html[data-bs-theme="dark"] label[for="title"] {
            color: white !important;
        }
    </style>

    <script>

        $(document).ready(function () {

            var editor = editormd("editormd", {
                width  : "100%",
                height : "750px",
                path   : "/editormd/lib/",
                emoji  : true,
                onchange: function() {
                },
            })

        });

    </script>

@endpush
