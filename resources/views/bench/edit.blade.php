@extends('components.admin.layout')

@section('page-content')

<div class="container-fluid">
    @include('components.admin.page-header', ['pageTitle' => __('message.bench_master')])
    
    @include('components.admin.alert-manager')

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        @isset($id)
                            {{ __('message.edit_bench') }}
                        @else 
                            {{ __('message.new_bench') }}
                        @endisset
                    </h6>
                </div>
                <div class="card-body">
                    <form id="form">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="required">{{ __('message.bench_name') }}</label>
                                <input type="text" class="form-control" name="bench_name" id="bench_name" placeholder="{{ __('message.bench_name') }}"
                                    @if (isset($row) && isset($row['bench_name']))
                                        value="{{ $row['bench_name'] }}"
                                    @endif
                                >
                                <span class="text-danger form-error" id="bench_name_error"></span>
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
            var BenchName = $("#bench_name").val();
            var csrfToken = "{{ csrf_token() }}";
            
            @isset($id)
                var method = 'PUT';
                var url = "{{ url('/bench/'. $id) }}";
            @else 
                var method = 'POST';
                var url = "{{ url('/bench') }}";
            @endisset

            var requestData = {
                url: url,
                method: method,
                body: {
                    bench_name: BenchName,
                    is_active: true,
                    _token: csrfToken
                }
            };

            sendRequest(requestData, "{{ url('bench') }}");
        });
    </script>

@endsection









