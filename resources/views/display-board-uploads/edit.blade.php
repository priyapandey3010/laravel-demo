@extends('components.admin.layout')

@section('page-content')

<div class="container-fluid">
    @include('components.admin.page-header', ['pageTitle' => __('message.display_board_uploads')])
    
    @include('components.admin.alert-manager')

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        
                            {{ __('message.new_display_board_uploads') }}
                        
                    </h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-danger alert-error" style="display:none;"></div>
                    <form id="form">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                            <a href="{{ url('private/formats/display-board-bulk-upload.xlsx') }}" class="btn btn-warning">{{ __('Download Format') }}</a>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="required">{{ __('message.upload_excel_file') }}</label>
                                <input type="file" class="form-control" name="upload_file" id="upload_file" />
                                <span class="text-danger form-error" id="file_error"></span>
                                <span id="spinner" class="text-success"></span>
                                
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="required">{{ __('message.upload_date') }}</label>
                                <input type="date" class="form-control" name="upload_date" id="upload_date" />
                                <span class="text-danger form-error" id="upload_date_error"></span>
                            </div>
                        </div>
                        <button type="submit" id="submit-btn" class="btn btn-primary">
                            
                                {{ __('message.upload') }}
                            
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
    
    <script>

        // $("#upload_file").change(function() {
        //     var uploadFile = document.getElementById("upload_file").files[0];
        //     var formData = new FormData();
        //     formData.append("file", uploadFile);
        //     formData.append("_token", "{{ csrf_token() }}");
        //     $.ajax({
        //         url: "{{ url('display-board-uploads/upload') }}",
        //         method: "POST",
        //         data: formData,
        //         contentType: false,
        //         cache: false,
        //         processData: false,
        //         beforeSend: function() {
        //             $("#spinner").html('<i class="fa fa-spinner fa-spin"></i> Uploading...');
        //         },
        //         success: function(response) {
        //             if (response.status) {
        //                 $("#spinner").html('Uploaded');
        //                 $("#upload_file_error").text('');
        //             } else {
        //                 $("#upload_file_error").text(response.errors.file[0]);
        //                 $("#spinner").html('');
        //                 return;
        //             }
        //         },
        //         error: function() {
        //             $("#spinner").html('<span class="text-danger">Something went wrong..</span>');
        //         }
        //     });
        // });


        $("#form").on("submit", function (event) {
            event.preventDefault();
            disableSubmit();

            var uploadFile = document.getElementById("upload_file").files[0];
            var uploadDate = $("#upload_date").val();
            var csrfToken = "{{ csrf_token() }}";
            var redirectTo = "{{ url('display-board-uploads') }}";
            var formData = new FormData();
            formData.append("file", uploadFile);
            formData.append("upload_date", uploadDate);
            formData.append("_token", csrfToken);
            
            @isset($id)
                var method = 'PUT';
                var url = "{{ url('/display-board-uploads/'. $id) }}";
            @else 
                var method = 'POST';
                var url = "{{ url('/display-board-uploads') }}";
            @endisset

            $.ajax({
                url: url,
                method: method,
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                success: function(responseData) {
                    $(".alert-error").hide();
                    if (responseData.status) {
                        success(responseData);
                        redirect(redirectTo);
                    }
                    else if (!responseData.status && responseData.errors) {
                        applyValidationErrors(responseData);
                        enableSubmit();
                        return;
                    }
                    else {
                        failure(responseData);
                        enableSubmit();
                    }
                },
                error: function(response) {
                    if (response.status === 422) {
                        var errorsList = '<ul>';
                        for (var idx in response.responseJSON.errors) {
                            errorsList += `<li>${response.responseJSON.errors[idx]}</li>`;
                        }
                        errorsList += '</ul>'; 
                        console.log(errorsList);
                        $(".alert-error").show();
                        $(".alert-error").html(errorsList);
                    }
                    $("#spinner").html('<span class="text-danger">Something went wrong..</span>');
                }
            });
        });
    </script>

@endsection