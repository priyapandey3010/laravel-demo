@extends('components.admin.layout')

@section('page-content')

<div class="container-fluid">
  @include('components.admin.page-header', ['pageTitle' => __('message.user')])
  @include('components.admin.alert-manager')
  <div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">{{ __('message.user_list') }}
          <span class="float-right">
          @if (acl('user-update'))
            <a href="{{ url('users/create') }}" class="btn btn-info btn-sm">
                <i class="fas fa-plus"></i> {{ __('message.create') }}
            </a>
            @endif
          </span>
        </h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>{{ __('message.sno') }}</th>
                        <th>{{ __('message.name') }}</th>
                        <th>{{ __('message.username') }}</th>
                        <th>{{ __('message.email') }}</th>
                        <th>{{ __('message.role') }}</th>
                        <th>{{ __('message.department') }}</th>
                        <th>{{ __('message.designation') }}</th>
                        <th>{{ __('message.bench_name') }}</th>
                        <th>{{ __('message.court_name') }}</th>
                        @if (acl('user-update') || acl('user-delete'))
                        <th>{{ __('message.is_active') }}</th>
                        <th>{{ __('message.action') }}</th>
                        @endif
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
      direction: "ASC"
    },
    url: "{{ url('users/datalist') }}",
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
            return row.full_name;
        }
      },
      {
        "orderable": false,
        "render": function(data, type, row) {
            return row.username;
        }
      },
      {
        "orderable": false,
        "render": function(data, type, row) {
            return row.email;
        }
      },
      {
        "orderable": false,
        "render": function(data, type, row) {
            return row.role_name;
        }
      },
      {
        "orderable": false,
        "render": function(data, type, row) {
            return row.department_name;
        }
      },
      {
        "orderable": false,
        "render": function(data, type, row) {
            return row.designation_name;
        }
      },
      {
        "orderable": false,
        "render": function(data, type, row) {
            return row.bench_name;
        }
      },
      {
        "orderable": false,
        "render": function(data, type, row) {
            return row.court_name;
        }
      },
      {
        "orderable": false,
        "render": function(data, type, row) {
            return (row.is_active == 1) 
              ? buttonDeactivate(`{{ url('users/deactivate') }}/${row.id}`)
              : buttonActivate(`{{ url('users/activate') }}/${row.id}`);
        }
      },
      @if (acl('user-update') || acl('user-delete'))
      {
        "orderable": false,
        "render": function (data, type, row) {
            var action = '';
            @if (acl('user-update'))
            action += buttonEdit("{{ url('users') }}", row.id);
            @endif
            @if (acl('user-delete'))
            action += '&nbsp;';
            action += buttonDelete("{{ url('users') }}", row.id);
            @endif
            @if (acl('user-update'))
            action += '&nbsp;';
            action += buttonPermission({
              url: "{{ url('user-permissions') }}",
              id: row.id,
              target: "#userPermissionModal",
              className: "btn-user-permission"
            });
            @endif
            return action;
        }
      }
      @endif
    ]
  });

  $(document).find("#user_permission").bootstrapDualListbox();

  $(document).on("click", ".btn-user-permission", function() {
    var id = $(this).data("id");
    var url = $(this).data("url");
    $(document).find("#user_id").val(id);
    $(document).find("#modal-url").val(url);
    $(document).find("#user_permission").bootstrapDualListbox('refresh');
    $.ajax({
      url: `{{ url('user-permissions') }}/${id}`,
      method: "GET",
      success: function (response) {
        if (response.status) {
          var permissions = response.data.permissions;
          for (var index in permissions) {
            var id = permissions[index];
            $(document).find(`#user_permission option[value="${id}"]`).prop("selected", true);
            $(document).find("#user_permission").bootstrapDualListbox('refresh');
          }
        }
      }
    });
  });

  $(document).find(".moveall").html('Move All <i class="fa fa-angle-double-right"></i>');
  $(document).find(".removeall").html('<i class="fa fa-angle-double-left"></i> Remove All ');

  $(document).on("click", "#confirm-user-permission", function() {
    $("#userPermissionModal").modal("hide");
    var permissions = $("#user_permission").val();
    var userId = $("#user_id").val();
    var url = $("#modal-url").val();
    var csrfToken = "{{ csrf_token() }}";
    var method = "POST";
    var requestData = {
        url: url,
        method: method,
        body: {
            user_id: userId,
            permissions: permissions,
            _token: csrfToken
        }
    };
    sendRequest(requestData, "{{ url('users') }}");
  });
});
</script>

@endsection
           