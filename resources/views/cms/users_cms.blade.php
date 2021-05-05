@extends('layouts.main')

{{-- META --}}
@section('meta')

@endsection

{{-- CSS --}}
@section('css')

@endsection

{{-- TITLE --}}
@section('title', 'CMS Users')

{{-- TITLE CONTENT --}}
@section('title_content', 'CMS Users')

{{-- CONTENT --}}
@section('content')
    @include('includes.nav-page')

    {{-- Data Wrapper --}}
    <div id="user_list"></div>
    @include('includes.loading')
@endsection

{{-- MODAL --}}
@section('modal')

@endsection

{{-- JS --}}
@section('js')
    <script>
        let lastId = 0;
        let hasNext = true;

        function loading() {
            $('#loading').toggleClass('d-none');
        }

        // Check if a user has scrolled to the bottom
        $(window).scroll(function() {
            if($(window).scrollTop() + $(window).height() > $(document).height() - 100) {
                loading();
                if (hasNext) {
                    $.get(`{{ route('cms.user.cms.index') }}/get-users/${lastId}`, function (res) {
                        if (lastId == 0) {
                            $('#user_list').html(res.html);
                        }
                        else {
                            $('#user_list').append(res.html);
                        }
                        lastId += 10;
                        hasNext = res.hasNext;
                    });
                }
                loading();
            }
        });

        $(document).ready(() => {
            loading();
            $.get(`{{ route('cms.user.cms.index') }}/get-users/${lastId}`, function (res) {
                if (lastId == 0) {
                    $('#user_list').html(res.html);
                }
                else {
                    $('#user_list').append(res.html);
                }
                lastId += 10;
                hasNext = res.hasNext;
                loading();
            });
        });


    </script>
@endsection
