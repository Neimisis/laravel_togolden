@extends('layouts.app')

@section('content')
<section class="company">
    <div class="container">
        <div class="row" id="company-list">
            @foreach ($companies as $company)
                <div class="col-lg-4 col-md-6 col-12 mb-4">
                    <div class="company__cart" data-id="{{ $company['id'] }}">
                        <div class="company__name fw-semibold">
                            <a class="text-warning" href="{{ route('company.show', $company['id']) }}">{{ $company['name'] }}</a>
                            @auth
                            <a href="javascript:void(0)" class="company__delete"><i class="fa fa-trash" aria-hidden="true"></i></a>
                            @endauth
                        </div>
                        <div class="company__field">{{ __('Адрес:') }} {{ $company['address'] }}</div>
                        <div class="company__field">{{ __('Телефон:') }} {{ $company['phone'] }}</div>
                        <div class="company__field">{{ __('Генеральный директор:') }} {{ $company['director'] }}</div>
                    </div>
                </div>  
            @endforeach
        </div>
        @auth
            <button type="button" class="btn btn-warning fw-semibold" data-bs-toggle="modal" data-bs-target="#company-modal">Новая компания</button>
        @endauth
    </div>
    @auth
        <div class="modal fade" id="company-modal" tabindex="-1" aria-labelledby="company-modalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="company-modalLabel">{{ __('Создание новой компании') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="company-form">
                            @csrf
                            <div class="mb-3">
                                <label for="recipient-name" class="col-form-label">{{ __('Наименование:') }}</label>
                                <input type="text" name="name" class="form-control" id="name" >
                            </div>
                            <div class="mb-3">
                                <label for="recipient-name" class="col-form-label">{{ __('ИНН:') }}</label>
                                <input type="text" name="inn" class="form-control" id="inn" >
                            </div>
                            <div class="mb-3">
                                <label for="recipient-name" class="col-form-label">{{ __('Общая информация:') }}</label>
                                <input type="text" name="information" class="form-control" id="information" >
                            </div>
                            <div class="mb-3">
                                <label for="recipient-name" class="col-form-label">{{ __('Генеральный директор:') }}</label>
                                <input type="text" name="director" class="form-control" id="director" >
                            </div>
                            <div class="mb-3">
                                <label for="recipient-name" class="col-form-label">{{ __('Адрес:') }}</label>
                                <input type="text" name="address" class="form-control" id="address" >
                            </div>
                            <div class="mb-3">
                                <label for="recipient-name" class="col-form-label">{{ __('Телефон:') }}</label>
                                <input type="text" name="phone" class="form-control" id="phone" >
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="company-close" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Закрыть') }}</button>
                        <button type="button" id="company-add" class="btn btn-primary">{{ __('Добавить') }}</button>
                    </div>
                </div>
            </div>
        </div>
        <script>
            class Company {
                constructor() {
                    this.init();
                    this.handlers();
                }
                init(){
                    this.submit = $('#company-add');
                    this.form = $('#company-form');
                    this.list = $('#company-list');
                }
                handlers(){
                    $(this.submit).on('click', ()=>{
                        this.clearErrorValidate();
                        const data = $(this.form).serialize();
                        axios.post('{{ route("company.store") }}', data)
                            .then((res)=>{
                                this.addHtmlCompany(res.data);
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
                    $(this.list).delegate('.company__delete', 'click', function(){
                        const id = $(this).closest('.company__cart').data('id');
                        const url = '{{ url("company") }}/' + id;
                        axios.post(url, {_method: 'delete'})
                            .then((res)=>{
                                if (res.status === 200) {
                                    (function(){
                                        this.removeHtmlCompany(id);
                                    }).bind(company)();
                                }
                            })
                            .catch((e)=>{console.error(e)});
                    });
                }
                addHtmlCompany(data){
                    const node = $(this.list).prepend(`
                        <div class="col-lg-4 col-md-6 col-12 mb-4">
                            <div class="company__cart" data-id="${data['id']}">
                                <div class="company__name fw-semibold">
                                    <a class="text-warning" href="${location.href}company/${data['id']}">${data['name']}</a>
                                    @auth
                                    <a href="javascript:void(0)" class="company__delete"><i class="fa fa-trash" aria-hidden="true"></i></a>
                                    @endauth
                                </div>
                                <div class="company__field">{{ __('Адрес:') }} ${data['address']}</div>
                                <div class="company__field">{{ __('Телефон:') }} ${data['phone']}</div>
                                <div class="company__field">{{ __('Генеральный директор:') }} ${data['director']}</div>
                            </div>
                        </div>
                    `);
                    company.showAlert('success', '{{ __("Компания успешно добавлена!")}}');
                }
                removeHtmlCompany(id){
                    $(`.company__cart[data-id="${id}"]`).parent().remove();
                    company.showAlert('info', '{{ __("Компания была удалена!")}}');
                }
                closeModal(){
                    $('#company-close').trigger('click');
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
            const company = new Company();
        </script>
    @endauth
</section>
@endsection