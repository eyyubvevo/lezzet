@extends('admin.layouts.master')
@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4"><span
                    class="text-muted fw-light"> Sayt Parametrləri /</span> {{ isset($setting) ? 'Yenilə' : 'Parametr Əlavə Et' }}
            </h4>
            <div class="row">
                <div class="col-xxl">
                    <div class="card mb-4">
                     @foreach($settings as $setting)
                        <div class="card-body">
                           
                            <form
                                action="{{  route('admin.settings.update',['id' => $setting->id]) }}"
                                method="post"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row mb-3">
                                    <label class="col-sm-2 col-form-label" for="button_url">Key</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="key" name="key"
                                               placeholder="Key açarı daxil edin..."
                                               @if(isset($setting)) value="{{$setting->key}}" disabled
                                               @else required @endif
                                        />
                                    </div>
                                </div>



                               
                                   @if($setting->type == 'image')
                                <div  class="row mb-3" id="image-input">
                                    <h5 class="card-header">Şəkil</h5>
                                    <!-- Account -->
                                    <div class="card-body">
                                        <div class="d-flex align-items-start align-items-sm-center gap-4">
                                            <img
                                                src=" @if(isset($setting) && $setting->value) {{ asset('storage/'.$setting->value) }} @else {{asset('backend/assets/img/default.jpg')}} @endif"
                                                alt="setting-avatar"
                                                class="d-block rounded"
                                                height="100"
                                                width="100"
                                                id="uploadedAvatar"
                                            />
                                            <div class="button-wrapper">
                                                <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                                                    <span class="d-none d-sm-block">Upload new photo</span>
                                                    <i class="bx bx-upload d-block d-sm-none"></i>
                                                    <input
                                                        type="file"
                                                        id="upload"
                                                        class="account-file-input"
                                                        hidden
                                                        name="value_image"
                                                        accept="image/png, image/jpeg"
                                                    />
                                                </label>
                                                <button type="button"
                                                        class="btn btn-outline-secondary account-image-reset mb-4">
                                                    <i class="bx bx-reset d-block d-sm-none"></i>
                                                    <span class="d-none d-sm-block">Reset</span>
                                                </button>

                                                <p class="text-muted mb-0">Allowed JPG, GIF or PNG. Max size of 800K</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @else
                                 <div  class="row mb-3" id="text-input">
                                    <label class="col-sm-2 col-form-label" for="value_text">Sadə Mətn</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="value_text" name="value_text"
                                               @if(isset($setting)) value="{{$setting->value}}" @endif
                                        />
                                    </div>
                                </div>
                              @endif


                                <div style="display: none;" id="ckeditor-input" class="row mb-3">
                                    <label class="col-sm-2 col-form-label" for="value">CkeEditor</label>
                                    <div class="col-sm-10">
                                        <textarea name="value_ckeditor" id="ckeditor">
                                            @if(isset($setting))
                                                {!! $setting->value !!}
                                            @endif
                                        </textarea>

                                        @push('scripts')
                                            <script>
                                                var editor = null;
                                                ClassicEditor.create(document.querySelector('#ckeditor')).then(newEditor => {
                                                    editor = newEditor;
                                                }).catch(error => {
                                                    console.error(error);
                                                });
                                            </script>
                                        @endpush
                                    </div>
                                </div>

                                <div class="row justify-content-end">
                                    <div class="col-sm-10">
                                        <button type="submit" class="btn btn-primary">Yadda Saxla</button>
                                    </div>
                                </div>
                            </form>
                        
                        </div>
                            @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endsection
        @push('scripts')
           <script>
            //     $(document).ready(function () {
            //         $('#type').select2();
            //         $('#is_active').select2();
            //         var textInput = $('#text-input');
            //         var imageInput = $('#image-input');
            //         var ckeditorInput = $('#ckeditor-input');
            //     @if(isset($setting) &&$setting->type == 'text')
            //     textInput.show();
            //         ckeditorInput.hide();
            //         imageInput.hide();
            //         $('#value_text').prop('required', true);

            //         $('.account-image-reset').click();
            //         if (editor) {
            //             editor.setData('');
            //         }
            //     @elseif(isset($setting) && $setting->type == 'image')
            //     textInput.hide();
            //         $('#value_text').val("");
            //         imageInput.show();
            //         ckeditorInput.hide();
            //         if (editor) {
            //             editor.setData('');
            //         }
            //     @elseif(isset($setting) && $setting->type == 'ckeditor')
            //     textInput.hide();
            //         $('#value_text').val("");
            //         imageInput.hide();
            //         $('.account-image-reset').click();
            //         ckeditorInput.show();
            //   @endif
            //         $('#type').on('change', function () {
            //             var selectedValue = $(this).val();
            //             if (selectedValue === 'text') {
            //                 textInput.show();
            //                 ckeditorInput.hide();
            //                 imageInput.hide();
            //               // $('#value_text').prop('required', true);

            //                 $('.account-image-reset').click();
            //                 if (editor) {
            //                     editor.setData('');
            //                 }
            //             } else if (selectedValue === 'image') {
            //                 textInput.hide();
            //                 $('#value_text').val("");
            //                 imageInput.show();
            //                 ckeditorInput.hide();
            //                 if (editor) {
            //                     editor.setData('');
            //                 }
            //             } else if (selectedValue === 'ckeditor') {
            //                 textInput.hide();
            //                 $('#value_text').val("");
            //                 imageInput.hide();
            //                 $('.account-image-reset').click();
            //                 ckeditorInput.show();
            //             } else {
            //                 textInput.hide();
            //                 $('#value_text').val("");
            //                 imageInput.hide();
            //                 $('.account-image-reset').click();
            //                 ckeditorInput.hide();
            //                 if (editor) {
            //                     editor.setData('');
            //                 }
            //             }
            //         });

            //     });
             </script>
    @endpush
