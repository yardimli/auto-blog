@extends('layouts.app')
@section('title', __('Create Help Article'))
@section('content')
    <!-- Main content START -->
    <main>
        <div class="container mb-5" style="min-height: calc(88vh);">
            <div class="row mt-3">
                <!-- Main content START -->
                <div class="col-12 mx-auto">
                    <h5>{{ isset($help) ? __('default.Edit Help Article') : __('default.Create Help Article') }}</h5>

                    <form id="articleForm" method="POST"
                          action="{{ isset($help) ? route('helps.update', $help->id) : route('helps.store') }}">
                        @csrf
                        @if(isset($help))
                            @method('PUT')
                        @endif
                        <input type="hidden" name="order" value="{{ isset($help) ? $help->order : '0' }}">
                        <!-- Title -->
                        <div class="mb-3">
                            <label for="title" class="form-label">{{ __('default.Title') }}</label>
                            <input type="text" class="form-control" id="title" name="title"
                                   value="{{ isset($help) ? $help->title : old('title') }}" required>
                        </div>

                        <!-- Category -->
                        <div class="mb-3">
                            <label for="title" class="form-label">{{ __('Category') }}</label>
                            <select class="form-select" id="category_id" name="category_id" required>
                                <option value="">{{ __('Select Category') }}</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}"
                                            {{ (isset($help) && $help->category_id == $category->id) ? 'selected' : '' }}>
                                        {{ $category->category_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{--   Help Article  --}}
                        <div class="mb-3">
                            <div id="editormd" class="editormd">
                                <textarea name="body" style="display:none;">
{{ isset($help) ? $help->body : '### Changelog.md
**Markdown Editor**: Write down your change log here.' }}
                                </textarea>
                            </div>
                        </div>

                        <div class="mb-3">
                            <button id="{{isset($help) ? 'updateHelp' : 'createHelp'}}" type="submit" class="btn btn-primary">
                                {{ isset($help) ? __('default.Save Help Article') : __('default.Create Help Article') }}
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
