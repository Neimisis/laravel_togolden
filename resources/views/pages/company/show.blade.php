@extends('layouts.app')

@section('content')
<section class="company">
    <div class="container">
        <div data-id="{{ $company['id'] }}">
            @foreach ($company['fields'] as $key => $field)
                <div class="company__field mb-3">
                    <div class="company__title">
                        <span class="text-warning fw-semibold">{{ $field['title'] }}</span>
                        @auth <a data-type="{{ $key }}" class="text-muted text-decoration-none comment-open"><span>{{ $text_comment }}</span> <i class="fa fa-comment" aria-hidden="true"></i></a> @endauth
                    </div>
                    <div class="company__value">{{ $field['value'] }}</div>
                    @auth
                        <ol id="comment-list-{{ $key }}" class="comments">
                            @foreach ($field['comments'] as $comment)
                                <li class="company__comment text-muted">{{ $comment->updated_at }} <span class="text-warning">{{ $comment->name }}</span>: {{ $comment->text }}</li>
                            @endforeach
                        </ol>
                    @endauth
                </div>
            @endforeach
            @auth <div class="text-end"><a data-type="general" class="text-muted text-decoration-none comment-open"><span>{{ $text_comment_company }}</span> <i class="fa fa-comment" aria-hidden="true"></i></a></div> @endauth
            @auth
                <ol id="comment-list-general" class="company__comments comments">
                    @foreach ($comments as $comment)
                        <li class="company__comment text-muted">{{ $comment->updated_at }} <span class="text-warning">{{ $comment->name }}</span>: {{ $comment->text }}</li>
                    @endforeach
                </ol>
            @endauth
        </div>
    </div>
    @auth
        <div class="modal fade" id="comment-modal" tabindex="-1" aria-labelledby="comment-modalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="comment-modalLabel">{{ __('Добавление нового комментария') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="comment-form">
                            @csrf
                            <input type="hidden" name="company_id" value="{{ $company['id'] }}">
                            <input type="hidden" name="field">
                            <div class="mb-3">
                                <label for="recipient-name" class="col-form-label">{{ __('Комментарий:') }}</label>
                                <textarea class="form-control" name="text" id="text" cols="30" rows="10"></textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="comment-close" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Закрыть') }}</button>
                        <button type="button" id="comment-add" class="btn btn-primary">{{ __('Добавить') }}</button>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $(".comment-open").click(function () {
                $("#comment-modal").modal("show");
                $("#comment-modal").find('[name="field"]').val($(this).data('type'));
            });
            class Comment {
                constructor() {
                    this.init();
                    this.handlers();
                }
                init(){
                    this.show = $('#comment-open');
                    this.submit = $('#comment-add');
                    this.form = $('#comment-form');
                }
                handlers(){
                    $(this.submit).on('click', ()=>{
                        this.clearErrorValidate();
                        const data = $(this.form).serialize();

                        axios.post("{{ route('comment.store') }}", data)
                            .then((res)=>{
                                this.addHtmlComment(res.data, $('[name="field"]').val());
                                this.closeModal();
                                this.clearForm();
                            })
                            .catch((e)=>{
                                if (e.response.status === 422) {
                                    this.setErrorValidate(e.response.data.errors);
                                } else {
                                    console.error(e);
                                }
                            });
                    });
                }
                addHtmlComment(data, type){
                    const node = $('#comment-list-' + type).append(`<li class="company__comment text-muted">${data['updated_at']} <span class="text-warning">{{ Auth::user()->name }}</span>: ${data['text']}</li>`);
                    comment.showAlert('success', '{{ __("Комментарий успешно добавлен!")}}');
                }
                closeModal(){
                    $('#comment-close').trigger('click');
                }
                setErrorValidate(errors){
                    for (const key in errors) {
                        const node = $(this.form).find(`[name="${key}"]`);
                        $(node).addClass('is-invalid');
                        $(node).after(`<div class="invalid-feedback">${errors[key][0]}</div>`);
                    }
                }
                clearErrorValidate(){
                    $(this.form).find('.invalid-feedback').remove();
                    $(this.form).find('.is-invalid').removeClass('is-invalid');
                }
                clearForm(){
                    $(this.form)[0].reset();
                }
                showAlert(type = 'success', message = '') {
                    switch (type) {
                        case 'success':
                            iziToast.success({
                                message: message,
                            });
                            break;
                        case 'info':
                            iziToast.info({
                                message: message,
                            });
                            break;
                        case 'error':
                            iziToast.error({
                                message: message,
                            });
                            break;
                        case 'warning':
                            iziToast.warning({
                                message: message,
                            });
                            break;
                        default:
                            break;
                    }
                }
            }
            const comment = new Comment();
        </script>
    @endauth
</section>
@endsection