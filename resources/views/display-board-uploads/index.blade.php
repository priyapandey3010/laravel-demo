@extends('components.admin.layout')

@section('page-content')

<div class="container-fluid">
  @include('components.admin.page-header', ['pageTitle' => __('message.display_board_uploads')])
  @include('components.admin.alert-manager')
  <div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">{{ __('message.display_board_uploads_list') }}
          <span class="float-right">
          @if (acl('display-board-upload-create'))
            <a href="{{ url('display-board-uploads/create') }}" class="btn btn-info btn-sm">
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
                        <th>{{ __('message.file_name') }}</th>
                        <th>{{ __('message.upload_date') }}</th>
                        @if (acl('display-board-upload-download') || acl('display-board-upload-delete'))
                        <th>{{ __('message.action') }}</th>
                        @endif
                    </tr>
                </thead>
            </table>
        </div>
    </div>
  </div>
</div>

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
    url: "{{ url('display-board-uploads/datalist') }}",
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
            return row.original_file_name;
        }
      },
      {
        "orderable": true,
        "render": function(data, type, row) {
            return row.upload_date;
        }
      },
      @if (acl('display-board-upload-download') || acl('display-board-upload-delete'))
      {
        "orderable": false,
        "render": function (data, type, row) {
            var action = '';
            @if (acl('display-board-upload-download'))
            action += buttonDownload(`{{ url('display-boards') }}/${row.file_name}`);
            @endif
            @if (acl('display-board-upload-delete'))
            action += '&nbsp;';
            action += buttonDelete("{{ url('display-board-uploads') }}", row.id);
            @endif
            return action;
        }
      }
      @endif
    ]
  });
});
</script>

@endsection
           