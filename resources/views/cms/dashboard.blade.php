@extends('layouts.main')

{{-- META --}}
@section('meta')

@endsection

{{-- CSS --}}
@section('css')

@endsection

{{-- TITLE --}}
@section('title', 'Dashboard')

{{-- TITLE CONTENT --}}
@section('title_content', 'Dashboard')

{{-- CONTENT --}}
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="d-print-block text-center">
                {!! QrCode::size(100)->generate(Request::url()); !!}
                <p>Scan me to return to the original page.</p>
            </div>
        </div>
    </div>
@endsection

{{-- MODAL --}}
@section('modal')

@endsection

{{-- JS --}}
@section('js')

@endsection
