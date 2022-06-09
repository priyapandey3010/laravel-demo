@extends('components.admin.layout')

@section('page-content')

<div class="container-fluid">
    @include('components.admin.page-header', ['pageTitle' => __('message.manage_cases')])
    
    @include('components.admin.alert-manager')

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        @isset($id)
                            {{ __('message.edit_manage_case') }}
                        @else 
                            {{ __('message.new_manage_case') }}
                        @endisset
                    </h6>
                </div>
                <div class="card-body">
                    <form id="form">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="required">{{ __('message.case_type') }}</label>
                                {{ Form::select('case_type_id', case_type_list(), $row['case_type_id'] ?? null, ['class' => 'form-control', 'id' => 'case_type_id']) }}
                                <span class="text-danger form-error" id="case_type_id_error"></span>
                            </div>
                        </div>
                        <!-- <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="required">{{ __('Category') }}</label>
                                {{ Form::select('category_id', category_list(), $row['category_id'] ?? null, ['class' => 'form-control', 'id' => 'category_id']) }}
                                <span class="text-danger form-error" id="category_id_error"></span>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="required">{{ __('Bench') }}</label>
                                {{ Form::select('bench_id', bench_list(), $row['bench_id'] ?? null, ['class' => 'form-control', 'id' => 'bench_id']) }}
                                <span class="text-danger form-error" id="bench_id_error"></span>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="required">{{ __('Court') }}</label>
                                {{ Form::select('court_id', ['' => 'Select'], $row['court_id'] ?? null, ['class' => 'form-control', 'id' => 'court_id']) }}
                                <span class="text-danger form-error" id="court_id_error"></span>
                            </div>
                        </div> -->
                        <!-- <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="required">{{ __('message.item_number') }}</label>
                                <input type="number" step="1" max="9999" class="form-control" name="item_number" id="item_number" placeholder="{{ __('message.item_number') }}"
                                    @if (isset($row) && isset($row['item_number']))
                                        value="{{ (int) $row['item_number'] }}"
                                    @endif
                                >
                                <span class="text-danger form-error" id="item_number_error"></span>
                            </div>
                        </div> -->
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="required">{{ __('message.case_number') }}</label>
                                <input type="text" class="form-control" name="case_number" id="case_number" placeholder="{{ __('message.case_number') }}"
                                    @if (isset($row) && isset($row['case_number']))
                                        value="{{ $row['case_number'] }}"
                                    @endif
                                >
                                <span class="text-danger form-error" id="case_number_error"></span>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="required">{{ __('message.case_title') }}</label>
                                <input type="text" class="form-control" name="case_title" id="case_title" placeholder="{{ __('message.case_title') }}"
                                    @if (isset($row) && isset($row['case_title']))
                                        value="{{ $row['case_title'] }}"
                                    @endif
                                >
                                <span class="text-danger form-error" id="case_title_error"></span>
                            </div>
                        </div>
                        <!-- <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="required">{{ __('message.status') }}</label>
                                {{ Form::select('status_id', status_list(), $row['status_id'] ?? null, ['class' => 'form-control', 'id' => 'status_id']) }}
                                <span class="text-danger form-error" id="status_id_error"></span>
                            </div>
                        </div> -->
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

        $("#bench_id").change(function() {
            var benchId = $(this).val();
            $.ajax({
                url: `{{ url('case/get-courts') }}/${benchId}`,
                method: "GET",
                success: function (response) {
                    if (response.status) {
                        var courts = response.data.courts;
                        var list = '<option value="">Select</option>';
                        for (var i in courts) {
                            list += `<option value="${courts[i]['id']}">${courts[i]['court_name']}</option>`;
                        }
                        $("#court_id").html(list);
                    }
                }
            });
        });

        $("#form").on("submit", function (event) {
            event.preventDefault();
            var caseTypeId = $("#case_type_id").val();
            // var categoryId = $("#category_id").val();
            var itemNumber = $("#item_number").val();
            var caseNumber = $("#case_number").val();
            var caseTitle = $("#case_title").val();
            // var benchId = $("#bench_id").val();
            // var courtId = $("#court_id").val();
            var caseTitle = $("#case_title").val();
            var statusId = $("#status_id").val();
            var csrfToken = "{{ csrf_token() }}";
            
            @isset($id)
                var method = 'PUT';
                var url = "{{ url('/case/'. $id) }}";
            @else 
                var method = 'POST';
                var url = "{{ url('/case') }}";
            @endisset

            var requestData = {
                url: url,
                method: method,
                body: {
                    case_type_id: caseTypeId,
                    //category_id: categoryId,
                    //item_number: itemNumber,
                    case_number: caseNumber,
                    case_title: caseTitle,
                    //bench_id: benchId,
                    //court_id: courtId,
                    //status_id: statusId,
                    is_active: true,
                    _token: csrfToken
                }
            };

            sendRequest(requestData, "{{ url('case') }}");
        });
    </script>

@endsection