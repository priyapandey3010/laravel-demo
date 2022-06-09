@extends('components.admin.layout')

@section('page-content')

<div class="container-fluid">
    @include('components.admin.page-header', ['pageTitle' => __('message.designation_master')])
    
    @include('components.admin.alert-manager')

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        @isset($id)
                            {{ __('message.edit_designation') }}
                        @else 
                            {{ __('message.new_designation') }}
                        @endisset
                    </h6>
                </div>
                <div class="card-body">
                    <form id="form">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="required">{{ __('message.designation_name') }}</label>
                                <input type="text" class="form-control" name="name" id="name" placeholder="{{ __('message.designation_name') }}"
                                    @if (isset($row) && isset($row['name']))
                                        value="{{ $row['name'] }}"
                                    @endif
                                >
                                <span class="text-danger form-error" id="name_error"></span>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label>{{ __('message.description') }}</label>
                                <textarea rows="3" class="form-control" name="description" id="description" placeholder="{{ __('message.description') }}"> @if (isset($row) && isset($row['description'])) {{ $row['description'] }}  @endif</textarea>
                                <span class="text-danger form-error" id="description_error"></span>
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
            var name = $("#name").val();
            var description = $("#description").val();
            var csrfToken = "{{ csrf_token() }}";
            
            @isset($id)
                var method = 'PUT';
                var url = "{{ url('/designations/'. $id) }}";
            @else 
                var method = 'POST';
                var url = "{{ url('/designations') }}";
            @endisset

            var requestData = {
                url: url,
                method: method,
                body: {
                    name: name,
                    description: description,
                    is_active: true,
                    _token: csrfToken
                }
            };

            sendRequest(requestData, "{{ url('designations') }}");
        });
    </script>

@endsection









