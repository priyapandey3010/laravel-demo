@extends('components.admin.layout')

@section('page-content')

<div class="container-fluid">
    @include('components.admin.page-header', ['pageTitle' => __('message.court_master')])
    
    @include('components.admin.alert-manager')

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        @isset($id)
                            {{ __('message.edit_court') }}
                        @else 
                            {{ __('message.new_court') }}
                        @endisset
                    </h6>
                </div>
                <div class="card-body">
                    <form id="form">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="required">{{ __('message.bench') }}</label>
                                {{ Form::select('bench_id', bench_list(), $row['bench_id'] ?? null, ['class' => 'form-control', 'id' => 'bench_id']) }}
                                <span class="text-danger form-error" id="bench_id_error"></span>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="required">{{ __('message.court_number') }}</label>
                                <input type="number" step="1" class="form-control" name="court_number" id="court_number" placeholder="{{ __('message.court_number') }}"
                                    @if (isset($row) && isset($row['court_number']))
                                        value="{{ $row['court_number'] }}"
                                    @endif
                                >
                                <span class="text-danger form-error" id="court_number_error"></span>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="required">{{ __('message.court_name') }}</label>
                                <input type="text" class="form-control" name="court_name" id="court_name" placeholder="{{ __('message.court_name') }}"
                                    @if (isset($row) && isset($row['court_name']))
                                        value="{{ $row['court_name'] }}"
                                    @endif
                                >
                                <span class="text-danger form-error" id="court_name_error"></span>
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
            var benchId = $("#bench_id").val();
            var courtNumber = $("#court_number").val();
            var courtName = $("#court_name").val();
            var csrfToken = "{{ csrf_token() }}";
            
            @isset($id)
                var method = 'PUT';
                var url = "{{ url('/courts/'. $id) }}";
            @else 
                var method = 'POST';
                var url = "{{ url('/courts') }}";
            @endisset

            var requestData = {
                url: url,
                method: method,
                body: {
                    bench_id: benchId,
                    court_number: courtNumber,
                    court_name: courtName,
                    is_active: true,
                    _token: csrfToken
                }
            };

            sendRequest(requestData, "{{ url('courts') }}");
        });
    </script>

@endsection