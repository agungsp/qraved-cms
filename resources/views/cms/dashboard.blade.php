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
            /* background-image: url("{{ asset('assets/img/undraw_under_construction_46pa.svg') }}"); */
            background-repeat: no-repeat;
            background-position: center;
        }
    </style>
@endsection

{{-- TITLE --}}
@section('title', 'Dashboard')

{{-- TITLE CONTENT --}}
@section('title_content', 'Dashboard')

{{-- CONTENT --}}
@section('content')
    <div id="under_develop">
        {{-- Under Developement --}}
    </div>
@endsection

{{-- MODAL --}}
@section('modal')

@endsection

{{-- JS --}}
@section('js')

@endsection
