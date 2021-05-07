@extends('layouts.main')

{{-- META --}}
@section('meta')

@endsection

{{-- CSS --}}
@section('css')

@endsection

{{-- TITLE --}}
@section('title', 'Qraved Users')

{{-- TITLE CONTENT --}}
@section('title_content', 'Qraved Users')

{{-- CONTENT --}}
@section('content')

    <div class="row justify-content-center">
        <div class="col-7">
            <img class="img-fluid" src="{{ asset('assets/img/undraw_under_construction_46pa.svg') }}" alt="Under Development">
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-7 text-center">
            <span class="display-4" style="text-decoration: underline; color: #dd0000">Under Development</span>
        </div>
    </div>
    {{-- @for ($i = 0; $i < 10; $i++)
        <div class="card mb-3 shadow-sm">
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
        </div>
    @endfor --}}
@endsection

{{-- MODAL --}}
@section('modal')

@endsection

{{-- JS --}}
@section('js')

@endsection
