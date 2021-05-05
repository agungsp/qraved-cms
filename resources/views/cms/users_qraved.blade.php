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
    @for ($i = 0; $i < 10; $i++)
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
    @endfor
@endsection

{{-- MODAL --}}
@section('modal')

@endsection

{{-- JS --}}
@section('js')

@endsection
