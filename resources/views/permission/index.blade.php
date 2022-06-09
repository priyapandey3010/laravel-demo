@extends('components.admin.layout')

@section('page-content')

<div class="container-fluid">
  @include('components.admin.page-header', ['pageTitle' => __('message.permission_master')])
  @include('components.admin.alert-manager')
  <div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">{{ __('message.permission_list') }}
          <span class="float-right">
          @if (acl('permission-create'))
            <a href="{{ url('permissions/create') }}" class="btn btn-info btn-sm">
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
                        @if (acl('permission-update') || acl('permission-delete'))
                        <th>{{ __('message.action') }}</th>
                        @endif
                    </tr>
                </thead>
            </table>
        </div>
    </div>
  </div>
</div>

@include('permission.modal-permission-group')

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
    url: "{{ url('permissions/datalist') }}",
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
      @if (acl('permission-update') || acl('permission-delete'))
      {
        "orderable": false,
        "render": function (data, type, row) {
            var action = '';
            @if (acl('permission-update'))
            action += buttonEdit("{{ url('permissions') }}", row.id);
            @endif
            @if (acl('permission-delete'))
            action += '&nbsp;';
            action += buttonDelete("{{ url('permissions') }}", row.id);
            @endif
            @if (acl('permission-update'))
            action += '&nbsp;';
            action += buttonPermission({
              url: "{{ url('permission-groups') }}",
              id: row.id,
              target: "#permissionGroupModal",
              className: "btn-permission-group"
            });
            @endif
            return action;
        }
      }
      @endif
    ]
  });

  $(document).find("#permission_group").bootstrapDualListbox();

  $(document).on("click", ".btn-permission-group", function() {
    var id = $(this).data("id");
    var url = $(this).data("url");
    $(document).find("#permission_id").val(id);
    $(document).find("#modal-url").val(url);
    $(document).find(`#permission_group option`).show();
    $(document).find(`#permission_group option[value="${id}"]`).hide();
    $(document).find("#permission_group").bootstrapDualListbox('refresh');
    $.ajax({
      url: `{{ url('permission-groups') }}/${id}`,
      method: "GET",
      success: function (response) {
        if (response.status) {
          var permissions = response.data.permissions;
          for (var index in permissions) {
            var id = permissions[index];
            $(document).find(`#permission_group option[value="${id}"]`).prop("selected", true);
            $(document).find("#permission_group").bootstrapDualListbox('refresh');
          }
        }
      }
    });
  });

  $(document).find(".moveall").html('Move All <i class="fa fa-angle-double-right"></i>');
  $(document).find(".removeall").html('<i class="fa fa-angle-double-left"></i> Remove All ');

  $(document).on("click", "#confirm-group-permission", function() {
    $("#permissionGroupModal").modal("hide");
    var permissions = $("#permission_group").val();
    var permissionId = $("#permission_id").val();
    var url = $("#modal-url").val();
    var csrfToken = "{{ csrf_token() }}";
    var method = "POST";
    var requestData = {
        url: url,
        method: method,
        body: {
            permission_id: permissionId,
            permissions: permissions,
            _token: csrfToken
        }
    };
    sendRequest(requestData, "{{ url('permissions') }}");
  });
});
</script>

@endsection
           