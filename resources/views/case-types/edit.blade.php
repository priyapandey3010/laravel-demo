@extends('components.admin.layout')

@section('page-content')

<div class="container-fluid">
    @include('components.admin.page-header', ['pageTitle' => __('message.case_type_master')])
    
    @include('components.admin.alert-manager')

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        @isset($id)
                            {{ __('message.edit_case_type') }}
                        @else 
                            {{ __('message.new_case_type') }}
                        @endisset
                    </h6>
                </div>
                <div class="card-body">
                    <form id="form">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="required">{{ __('message.case_type') }}</label>
                                <input type="text" class="form-control" name="case_type" id="case_type" placeholder="{{ __('message.case_type') }}"
                                    @if (isset($row) && isset($row['case_type']))
                                        value="{{ $row['case_type'] }}"
                                    @endif
                                >
                                <span class="text-danger form-error" id="case_type_error"></span>
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
            var caseType = $("#case_type").val();
            var csrfToken = "{{ csrf_token() }}";
            
            @isset($id)
                var method = 'PUT';
                var url = "{{ url('/case-types/'. $id) }}";
            @else 
                var method = 'POST';
                var url = "{{ url('/case-types') }}";
            @endisset

            var requestData = {
                url: url,
                method: method,
                body: {
                    case_type: caseType,
                    is_active: true,
                    _token: csrfToken
                }
            };

            sendRequest(requestData, "{{ url('case-types') }}");
        });
    </script>

@endsection