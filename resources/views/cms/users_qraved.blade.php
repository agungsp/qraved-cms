@extends('layouts.main')

{{-- META --}}
@section('meta')

@endsection

{{-- CSS --}}
@section('css')
    <style>
        #under_develop {
            position: relative;
            width: 100%;
            height: 60vh;
            z-index: 1030;
            background-color: #dededebf;
            text-align: center;
            font-weight: 900;
            font-size: 2rem;
            color: #dd3333;
            padding-top: 5rem;
            background-size: 50%;
            background-image: url("{{ asset('assets/img/undraw_under_construction_46pa.svg') }}");
            background-repeat: no-repeat;
            background-position: center;
        }
    </style>
@endsection

{{-- TITLE --}}
@section('title', 'Qraved Users')

{{-- TITLE CONTENT --}}
@section('title_content', 'Qraved Users')

{{-- CONTENT --}}
@section('content')

    <div id="under_develop">
        Under Developement
    </div>
    {{-- @for ($i = 0; $i < 10; $i++)
        < class="card mb-3 shadow-sm">
            <div class="card-body p-2">
                <div class="row">
                    <div class="col-auto pl-4">
                        <img class="img-thumbnail rounded-circle shadow-sm" width="50"
                            src="https://ui-avatars.com/api/?name={{ Str::slug(auth()->user()->name) }}"
                            alt="Profile Picture">
                    </div>
                    <div class="col pt-2">
                        User Name
                    </div>
                </div>
            </div>
        </>
    @endfor --}}
@endsection

{{-- MODAL --}}
@section('modal')

@endsection

{{-- JS --}}
@section('js')

@endsection
