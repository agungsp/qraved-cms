@extends('layouts.main')

{{-- META --}}
@section('meta')

@endsection

{{-- CSS --}}
@section('css')

@endsection

{{-- TITLE --}}
@section('title', 'Quiz')

{{-- TITLE CONTENT --}}
@section('title_content', 'Quiz')

{{-- CONTENT --}}
@section('content')
    @include('includes.nav-page')

    {{-- Data Wrapper --}}
    <div id="quiz_list"></div>
    <button id="btnMore" class="btn qraved-btn-primary btn-block d-none">
        more..
    </button>
    @include('includes.loading')
@endsection

{{-- MODAL --}}
@section('modal')
    <!-- Modal -->
    <div class="modal fade" id="modalQuiz" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="modalQuizLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalQuizLabel">Add/Edit Quiz</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="formQuiz" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="id" value="">
                        <div class="form-group">
                            <label for="question">Question <span class="text-danger">*</span></label>
                            <textarea name="question" id="question" class="form-control" cols="30" rows="10" style="resize: none;"></textarea>
                            <span id="question_invalid" class="invalid-feedback" role="alert"></span>
                        </div>
                        <div class="form-group">
                            <label for="question_images">Question Images</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="question_images" name="question_images[]" multiple>
                                <label class="custom-file-label" for="question_images">
                                    <i class="text-muted">Select 1 or more images</i>
                                </label>
                            </div>
                            <span id="question_images_invalid" class="invalid-feedback d-block" role="alert"></span>
                        </div>
                        <div class="form-group mb-3">
                            <label for="answer_type" class="d-block">Answer Type <span class="text-danger">*</span></label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="answer_type" id="answer_type_1" value="1" checked>
                                <label class="form-check-label" for="answer_type_1">Multiple Choice</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="answer_type" id="answer_type_2" value="2">
                                <label class="form-check-label" for="answer_type_2">Essay</label>
                            </div>
                            {{-- <select name="answer_type" id="answer_type" class="form-control">
                                <option value="1">Multiple Choice</option>
                                <option value="2">Essay</option>
                            </select> --}}
                        </div>
                        <hr>

                        <div id="multiple-choice-wrapper">
                            <div class="form-group mt-3">
                                <label>Multiple Choice <span class="text-danger">*</span> <span id="correct_answer_invalid" class="invalid-feedback d-block" role="alert"></span> </label>
                                <div class="card shadow-sm mb-3">
                                    <div class="card-body">
                                        <strong class="mr-3">Field</strong>
                                        <strong class="mr-3">Correct</strong>
                                        <strong class="mr-3">Answer</strong>
                                        <hr>
                                        <div class="input-group mb-1">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text pl-3 pr-3" title="Field in use">
                                                    <input type="checkbox" id="use_this_1" name="use_this_1" checked onclick="toggleMultipleChoice(1)">
                                                </div>
                                                <div class="input-group-text pl-4 pr-4" title="Correct Choice">
                                                    <input type="radio" name="correct_answer" id="correct_answer_1" aria-label="Answer Choice" value="1">
                                                </div>
                                            </div>
                                            <input type="text" name="answer_1" id="answer_1" class="form-control" aria-label="Answer Choice">
                                        </div>
                                        <span id="answer_1_invalid" class="invalid-feedback d-block" role="alert"></span>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="answer_image_1" name="answer_image_1">
                                            <label class="custom-file-label" for="answer_image_1">
                                                <i class="text-muted">Select image (optional)</i>
                                            </label>
                                        </div>
                                        <span id="answer_image_1_invalid" class="invalid-feedback d-block" role="alert"></span>

                                        <hr>

                                        <div class="input-group mb-1">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text pl-3 pr-3" title="Field in use">
                                                    <input type="checkbox" id="use_this_2" name="use_this_2" checked onclick="toggleMultipleChoice(2)">
                                                </div>
                                                <div class="input-group-text pl-4 pr-4" title="Correct Choice">
                                                    <input type="radio" name="correct_answer" id="correct_answer_2" aria-label="Answer Choice" value="2">
                                                </div>
                                            </div>
                                            <input type="text" name="answer_2" id="answer_2" class="form-control" aria-label="Answer Choice">
                                        </div>
                                        <span id="answer_2_invalid" class="invalid-feedback d-block" role="alert"></span>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="answer_image_2" name="answer_image_2">
                                            <label class="custom-file-label" for="answer_image_2">
                                                <i class="text-muted">Select image (optional)</i>
                                            </label>
                                        </div>
                                        <span id="answer_image_2_invalid" class="invalid-feedback d-block" role="alert"></span>

                                        <hr>

                                        <div class="input-group mb-1">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text pl-3 pr-3" title="Field in use">
                                                    <input type="checkbox" id="use_this_3" name="use_this_3" checked onclick="toggleMultipleChoice(3)">
                                                </div>
                                                <div class="input-group-text pl-4 pr-4" title="Correct Choice">
                                                    <input type="radio" name="correct_answer" id="correct_answer_3" aria-label="Answer Choice" value="3">
                                                </div>
                                            </div>
                                            <input type="text" name="answer_3" id="answer_3" class="form-control" aria-label="Answer Choice">
                                        </div>
                                        <span id="answer_3_invalid" class="invalid-feedback d-block" role="alert"></span>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="answer_image_3" name="answer_image_3">
                                            <label class="custom-file-label" for="answer_image_3">
                                                <i class="text-muted">Select image (optional)</i>
                                            </label>
                                        </div>
                                        <span id="answer_image_3_invalid" class="invalid-feedback d-block" role="alert"></span>

                                        <hr>

                                        <div class="input-group mb-1">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text pl-3 pr-3" title="Field in use">
                                                    <input type="checkbox" id="use_this_4" name="use_this_4" onclick="toggleMultipleChoice(4)">
                                                </div>
                                                <div class="input-group-text pl-4 pr-4" title="Correct Choice">
                                                    <input type="radio" name="correct_answer" id="correct_answer_4" aria-label="Answer Choice" disabled value="4">
                                                </div>
                                            </div>
                                            <input type="text" name="answer_4" id="answer_4" class="form-control" aria-label="Answer Choice" disabled>
                                        </div>
                                        <span id="answer_4_invalid" class="invalid-feedback d-block" role="alert"></span>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="answer_image_4" name="answer_image_4" disabled>
                                            <label class="custom-file-label" for="answer_image_4">
                                                <i class="text-muted">Select image (optional)</i>
                                            </label>
                                        </div>
                                        <span id="answer_image_4_invalid" class="invalid-feedback d-block" role="alert"></span>

                                        <hr>

                                        <div class="input-group mb-1">
                                            <div class="input-group-prepend">
                                                <div class="input-group-text pl-3 pr-3" title="Field in use">
                                                    <input type="checkbox" id="use_this_5" name="use_this_5" onclick="toggleMultipleChoice(5)">
                                                </div>
                                                <div class="input-group-text pl-4 pr-4" title="Correct Choice">
                                                    <input type="radio" name="correct_answer" id="correct_answer_5" aria-label="Answer Choice" disabled value="5">
                                                </div>
                                            </div>
                                            <input type="text" name="answer_5" id="answer_5" class="form-control" aria-label="Answer Choice" disabled>
                                        </div>
                                        <span id="answer_5_invalid" class="invalid-feedback d-block" role="alert"></span>
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="answer_image_5" name="answer_image_5" disabled>
                                            <label class="custom-file-label" for="answer_image_5">
                                                <i class="text-muted">Select image (optional)</i>
                                            </label>
                                        </div>
                                        <span id="answer_image_5_invalid" class="invalid-feedback d-block" role="alert"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div id="essay-wrapper" class="d-none">
                            <div class="form-group">
                                <label for="essay_answer">Answer</label>
                                <input type="text" id="essay_answer" name="essay_answer" class="form-control" disabled>
                            </div>
                            <span id="essay_answer_invalid" class="invalid-feedback d-block" role="alert"></span>
                        </div>
                    </form>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" id="btnClose" class="btn btn-light" data-dismiss="modal">Close</button>
                    <button type="button" id="btnDelete" class="btn qraved-btn-danger d-none">
                        <i class="fa fa-trash"></i> Delete
                    </button>
                    <button type="submit" form="formQuiz" id="btnSave" class="btn qraved-btn-primary">
                        <i class="fa fa-save"></i> Save
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- JS --}}
@section('js')
    <script src="{{ asset('assets/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>
    <script>
        let lastId = 0;
        let hasNext = false;


        $('body').on('click', '#btnNew', function () {
            $('#id').val('');
            $('#formQuiz').trigger('reset');
            resetAnswerType(1);
            $('#modalQuizLabel').html('Add Quiz');
            $('#modalQuiz').modal('show');
        });

        $('#modalQuiz').on('shown.bs.modal', function () {
            $('#question').focus();
        });

        $('body').on('click', 'input[name="answer_type"]', function () {
            resetAnswerType($(this).val());
            // if ($(this).val() == 1) {
            //     $('#multiple-choice-wrapper').removeClass('d-none');
            //     $('#essay-wrapper').addClass('d-none');
            //     $('#essay_answer').prop('disabled', true);
            //     toggleMultipleChoice(3);
            // } else {
            //     $('#multiple-choice-wrapper').addClass('d-none');
            //     $('#essay-wrapper').removeClass('d-none');
            //     $('#essay_answer').prop('disabled', false);
            //     toggleMultipleChoice(0);
            //     $('#essay_answer').focus();
            // }
        });

        $('body').on('click', '#btnEdit', function () {
            const id = $(this).attr('data-id');
            $.get(`{{ route('cms.quiz.index') }}/get-question/${id}`, function (res) {
                $('#id').val(res.id);
                $('#question').val(res.question);
                resetAnswerType(res.answer_type);

                if (res.answer_type == 1) {
                    $('#multiple-choice-wrapper').removeClass('d-none');
                    $('#essay-wrapper').addClass('d-none');
                    $('#essay_answer').prop('disabled', true);
                    toggleMultipleChoice(JSON.parse(res.answer).length);
                    for (let i = 1; i <= JSON.parse(res.answer).length; i++) {
                        $(`#answer_${i}`).val(JSON.parse(res.answer)[i-1].text);
                        $(`#correct_answer_${i}`).prop('checked', JSON.parse(res.answer)[i-1].correct);
                    }
                }
                else if (res.answer_type == 2) {
                    $('#multiple-choice-wrapper').addClass('d-none');
                    $('#essay-wrapper').removeClass('d-none');
                    $('#essay_answer').prop('disabled', false);
                    $('#essay_answer').val(JSON.parse(res.answer).text);
                }

                $('#modalQuizLabel').html('Edit Quiz');
                $('#modalQuiz').modal('show');
            });
        });

        $('#formQuiz').submit(function (e) {
            e.preventDefault();
            let formData = new FormData(this);

            $.ajax({
                type       : 'POST',
                url        : '{{ route('cms.quiz.store') }}',
                data       : formData,
                contentType: false,
                processData: false,
                success    : (response) => {
                    Swal.fire({
                        icon : response.success ? 'success' : 'error',
                        title: response.success ? 'Success' : 'Failed',
                        text : response.message,
                        timer: response.success ? 3000 : undefined,
                        timerProgressBar: response.success,
                    }).then((result) => {
                        if (result.isConfirmed) {
                            lastId = 0;
                            hasNext = false;
                            loadList();
                            $('#modalQuiz').modal('hide');
                        }
                        else if (result.isDismissed) {
                            lastId = 0;
                            hasNext = false;
                            loadList();
                            $('#modalQuiz').modal('hide');
                        }
                    });
                },
                error: function(response){
                    if (response.status) {
                        showValidation(response.responseJSON.errors);
                    }
                    else {
                        Swal.fire({
                            icon : response.success ? 'success' : 'error',
                            title: response.success ? 'Success' : 'Failed',
                            text : response.message,
                        });
                    }
                }
            });

        });

        function toggleMultipleChoice(number) {
            for (let i = 1; i <= 5; i++) {
                $(`#use_this_${i}`).prop('checked', (i <= number));
                $(`#correct_answer_${i}`).prop('disabled', !(i <= number));
                $(`#answer_${i}`).prop('disabled', !(i <= number));
                $(`#answer_image_${i}`).prop('disabled', !(i <= number));

                if (!(i <= number)) {
                    $(`#answer_${i}`).val('');
                }

                if (i > number && $(`#correct_answer_${i}`).prop('checked')) {
                    $(`#correct_answer_${i}`).prop('checked', false);
                }
            }
        }

        function resetAnswerType(type) {
            $(`#answer_type_${type}`).prop('checked', true);
            if (type == 1) {
                $('#multiple-choice-wrapper').removeClass('d-none');
                $('#essay-wrapper').addClass('d-none');
                $('#essay_answer').prop('disabled', true);
                toggleMultipleChoice(3);
            }
            else if (type == 2) {
                $('#multiple-choice-wrapper').addClass('d-none');
                $('#essay-wrapper').removeClass('d-none');
                $('#essay_answer').prop('disabled', false);
                toggleMultipleChoice(0);
                $('#essay_answer').focus();
            }
        }

        function loading() {
            $('#loading').toggleClass('d-none');
        }

        function loadList() {
            loading();
            $.get(`{{ route('cms.quiz.index') }}/get-questions/${lastId}`, function (res) {
                if (lastId == 0) {
                    $('#quiz_list').html(res.html);
                }
                else {
                    $('#quiz_list').append(res.html);
                }
                lastId += 50;
                hasNext = res.hasNext;
                loading();

                if (hasNext) {
                    $('#btnMore').removeClass('d-none');
                }
                else {
                    $('#btnMore').addClass('d-none');
                }
            });
        }

        function showValidation(errors) {
            for (const error in errors) {
                $(`#${error}`).addClass('is-invalid');
                $('#'+error+'_invalid').html(errors[error][0]);
            }
        }

        $(document).ready(() => {
            bsCustomFileInput.init();
            loading();
            $.get(`{{ route('cms.quiz.index') }}/get-questions/${lastId}`, function (res) {
                if (lastId == 0) {
                    $('#quiz_list').html(res.html);
                }
                else {
                    $('#quiz_list').append(res.html);
                }
                lastId += 50;
                hasNext = res.hasNext;
                loading();
            });
        });


    </script>
@endsection
