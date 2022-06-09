@extends('components.admin.layout')

@section('page-content')

<div class="container-fluid">
  @include('components.admin.page-header', ['pageTitle' => __('message.role_master')])
  @include('components.admin.alert-manager')
  <div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">{{ __('message.role_list') }}
          <span class="float-right">
          @if (acl('role-create'))
            <a href="{{ url('roles/create') }}" class="btn btn-info btn-sm">
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
                        <th>{{ __('message.role_name') }}</th>
                        @if (acl('role-update') || acl('role-delete'))
                          <th>{{ __('message.action') }}</th>
                        @endif
                    </tr>
                </thead>
            </table>
        </div>
    </div>
  </div>
</div>

@include('role.modal-role-permission')

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
    url: "{{ url('roles/datalist') }}",
    columns: [
      {
        "orderable": true,
        "render": function(data, type, full, meta) {
          return serialNumber("#dataTable", meta.row);
        }
      },
      {
        "orderable": true,
        "render": function(data, type, row) {
            return row.name;
        }
      },
      @if (acl('role-update') || acl('role-delete'))
      {
        "orderable": false,
        "render": function (data, type, row) {
            var action = '';
            @if (acl('role-update'))
            action += buttonEdit("{{ url('roles') }}", row.id);
            @endif
            @if (acl('role-delete'))
            action += '&nbsp;';
            action += buttonDelete("{{ url('roles') }}", row.id);
            @endif
            @if (acl('role-update'))
            action += '&nbsp;';
            action += buttonPermission({
              url: "{{ url('role-permissions') }}",
              id: row.id,
              target: "#rolePermissionModal",
              className: "btn-role-permission"
            });
            @endif
            return action;
        }
      }
      @endif
    ]
  });

  $(document).find("#role_permission").bootstrapDualListbox();

  $(document).on("click", ".btn-role-permission", function() {
    var id = $(this).data("id");
    var url = $(this).data("url");
    $(document).find("#role_id").val(id);
    $(document).find("#modal-url").val(url);
    $(document).find("#role_permission").bootstrapDualListbox('refresh');
    $.ajax({
      url: `{{ url('role-permissions') }}/${id}`,
      method: "GET",
      success: function (response) {
        if (response.status) {
          var permissions = response.data.permissions;
          for (var index in permissions) {
            var id = permissions[index];
            $(document).find(`#role_permission option[value="${id}"]`).prop("selected", true);
            $(document).find("#role_permission").bootstrapDualListbox('refresh');
          }
        }
      }
    });
  });

  $(document).find(".moveall").html('Move All <i class="fa fa-angle-double-right"></i>');
  $(document).find(".removeall").html('<i class="fa fa-angle-double-left"></i> Remove All ');

  $(document).on("click", "#confirm-role-permission", function() {
    $("#rolePermissionModal").modal("hide");
    var permissions = $("#role_permission").val();
    var roleId = $("#role_id").val();
    var url = $("#modal-url").val();
    var csrfToken = "{{ csrf_token() }}";
    var method = "POST";
    var requestData = {
        url: url,
        method: method,
        body: {
            role_id: roleId,
            permissions: permissions,
            _token: csrfToken
        }
    };
    sendRequest(requestData, "{{ url('roles') }}");
  });
});
</script>

@endsection
           