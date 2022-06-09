@extends('components.admin.layout')

@section('page-content')

<div class="container-fluid">
    @include('components.admin.page-header', ['pageTitle' => __('message.status_master')])
    
    @include('components.admin.alert-manager')

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        @isset($id)
                            {{ __('message.edit_status') }}
                        @else 
                            {{ __('message.new_status') }}
                        @endisset
                    </h6>
                </div>
                <div class="card-body">
                    <form id="form">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="required">{{ __('message.status') }}</label>
                                <input type="text" class="form-control" name="status" id="status" placeholder="{{ __('message.status') }}"
                                    @if (isset($row) && isset($row['status']))
                                        value="{{ $row['status'] }}"
                                    @endif
                                >
                                <span class="text-danger form-error" id="status_error"></span>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="required">{{ __('message.color') }}</label>
                                <input type="color" class="form-control" name="colour_code" id="colour_code" placeholder="{{ __('message.color') }}"
                                    @if (isset($row) && isset($row['colour_code']))
                                        value="{{ $row['colour_code'] }}"
                                    @endif
                                >
                                <span class="text-danger form-error" id="colour_code_error"></span>
                            </div>
                        </div>
                        <button type="submit" id="submit-btn" class="btn btn-primary">
                            @isset($id)
                                {{ __('message.update') }}
                            @else 
                                {{ __('message.create') }}
                            @endisset
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
        $("#form").on("submit", function (event) {
            event.preventDefault();
            var status = $("#status").val();
            var colorCode = $("#colour_code").val();
            var isDefault = $("#is_default").is(":checked");
            var csrfToken = "{{ csrf_token() }}";
            
            @isset($id)
                var method = 'PUT';
                var url = "{{ url('/status/'. $id) }}";
            @else 
                var method = 'POST';
                var url = "{{ url('/status') }}";
            @endisset

            var requestData = {
                url: url,
                method: method,
                body: {
                    status: status,
                    colour_code: colorCode,
                    //is_default: isDefault, 
                    is_active: true,
                    _token: csrfToken
                }
            };

            sendRequest(requestData, "{{ url('status') }}");
        });
    </script>

@endsection