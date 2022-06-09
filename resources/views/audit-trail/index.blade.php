@extends('components.admin.layout')

@section('page-content')

<div class="container-fluid">
  @include('components.admin.page-header', ['pageTitle' => __('Audit Trail')])
  @include('components.admin.alert-manager')
  <div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">{{ __('Audit Trail List') }}
                  </h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>{{ __('message.sno') }}</th>
                        <th>{{ __('Module Name') }}</th>
                        <th>{{ __('Activity Type') }}</th>
                        <th>{{ __('message.username') }}</th>
                        <th>{{ __('message.email') }}</th>
                        <th>{{ __('message.role') }}</th> 
                        <th>{{ __('Last Access') }}</th> 
                        <th>{{ __('Last Login') }}</th> 
                        <th>{{ __('Activity Datetime') }}</th> 
                        <th>{{ __('IP Address') }}</th> 
                    </tr>
                </thead>
            </table>
        </div>
    </div>
  </div>
</div>

@include('user.modal-user-permission')

@endsection

@section('js')

<script>
$(document).ready(function() {
  dataTableInit({
    id: "#dataTable",
    order: {
      column: 0,
      direction: "DESC"
    },
    url: "{{ url('audit-trail/datalist') }}",
    columns: [
      {
        "orderable": false,
        "render": function(data, type, full, meta) {
          return serialNumber("#dataTable", meta.row);
        }
      },
      {
        "orderable": false,
        "render": function(data, type, row) {
            return row.module_name;
        }
      },
      {
        "orderable": false,
        "render": function(data, type, row) {
            return row.activity_type;
        }
      },
      {
        "orderable": false,
        "render": function(data, type, row) {
            return row.user_name;
        }
      },
      {
        "orderable": false,
        "render": function(data, type, row) {
            return row.email_id;
        }
      },
      {
        "orderable": false,
        "render": function(data, type, row) {
            return row.role_id;
        }
      },
      {
        "orderable": false,
        "render": function(data, type, row) {
            return row.last_access;
        }
      },
      {
        "orderable": false,
        "render": function(data, type, row) {
            return row.last_login;
        }
      },
      {
        "orderable": false,
        "render": function(data, type, row) {
            return row.activity_datetime;
        }
      },

      {
        "orderable": false,
        "render": function(data, type, row) {
            return row.ip_address;
        }
      }
    ]
  });
  });
  
</script>

@endsection
           