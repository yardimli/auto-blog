@extends('layouts.app')
@section('title', __('Create Change Log'))
@section('content')
    <!-- Main content START -->
    <main>
        <div class="container mb-5" style="min-height: calc(88vh);">
            <div class="row mt-3">
                <!-- Main content START -->
                <div class="col-12 col-xl-8 col-lg-8 mx-auto">
                    <h5>{{ isset($changelog) ? __('default.Edit Change Log') : __('default.Create Change Log') }}</h5>

                    <!-- Title -->
                    <div class="mb-3">
                        <label for="title" class="form-label">{{ __('default.Title') }}</label>
                        <input type="text" class="form-control" id="title" name="title"
                               value="{{ isset($changelog) ? $changelog->title : old('title') }}" required>
                    </div>

                    <div class="mb-3">
                        <div style="height: 700px; margin-bottom: 15px;">
                            <textarea id="myChangeLog" style="height: 650px;width: 100%;"></textarea>
                        </div>
                        <button id="{{isset($changelog) ? 'updateChangeLog' : 'createChangeLog'}}" type="button" class="btn btn-primary">
                            {{ isset($changelog) ? __('default.Save Change Log') : __('default.Create Change Log') }}
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </main>

    @include('layouts.footer')
@endsection

<link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">
<script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>

@push('scripts')

    <style>
        html[data-bs-theme="dark"] .editor-toolbar a {
            color: white !important;
        }
        html[data-bs-theme="dark"] label[for="title"] {
            color: white !important;
        }
        .CodeMirror {
            height: 650px !important;
        }
    </style>

    <script>

        var changeLogId = `{{isset($changelog) ? $changelog->id : ''}}`;
        var changeLogContent = `{{isset($changelog) ? $changelog->body : ''}}`;
        console.log(changeLogContent);

        $(document).ready(function () {
            var simplemde = new SimpleMDE({ element: document.getElementById("myChangeLog") });
            if(changeLogContent.trim() !== '') simplemde.value(changeLogContent);
            $('#createChangeLog').click(function (){
                $.ajax({
                    url: '/changelogs/store',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        title: $('#title').val(),
                        body: simplemde.value()
                    },
                    success: function (response) {
                        window.location = '/changelogs?success=1'
                    },
                    error: function (xhr) {
                    },
                    complete: function () {
                    }
                });
            })
            $('#updateChangeLog').click(function (){
                $.ajax({
                    url: '/changelogs/' + changeLogId + '/update',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        title: $('#title').val(),
                        body: simplemde.value()
                    },
                    success: function (response) {
                        window.location = '/changelogs?success=1'
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
