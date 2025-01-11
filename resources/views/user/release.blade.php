@extends('layouts.app')
@section('title', __('Create Release Note'))
@section('content')
    <!-- Main content START -->
    <main>
        <div class="container mb-5" style="min-height: calc(88vh);">
            <div class="row mt-3">
                <!-- Main content START -->
                <div class="col-12 col-xl-8 col-lg-8 mx-auto">
                    <h5>{{ isset($release) ? __('default.Edit Release Note') : __('default.Create Release Note') }}</h5>

                    <!-- Title -->
                    <div class="mb-3">
                        <label for="title" class="form-label">{{ __('default.Title') }}</label>
                        <input type="text" class="form-control" id="title" name="title"
                               value="{{ isset($release) ? $release->title : old('title') }}" required>
                    </div>

                    <div class="mb-3">
                        <div style="height: 500px; margin-bottom: 15px;">
                            <textarea id="myReleaseNote" style="height: 450px;width: 100%;"></textarea>
                        </div>
                        <button id="submitReleaseNote" type="button" class="btn btn-primary">
                            {{ isset($release) ? __('default.Save Release Note') : __('default.Save Release Note') }}
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
        .CodeMirror {
            height: 450px !important;
        }
    </style>

    <script>

        $(document).ready(function () {
            var simplemde = new SimpleMDE({ element: document.getElementById("myReleaseNote") });

            $('#submitReleaseNote').click(function (){
                $.ajax({
                    url: '/releases/store',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        title: $('#title').val(),
                        body: simplemde.value()
                    },
                    success: function (response) {
                        if (response.success) {

                        } else {

                        }
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
