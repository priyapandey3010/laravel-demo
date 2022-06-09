@section('css')

.moveall, .removeall {
  background-color: teal;
  color: #fff;
}

@endsection

<!-- Modal -->
<div class="modal fade" id="rolePermissionModal" tabindex="-1" aria-labelledby="rolePermissionModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="rolePermissionModalLabel">{{ __('message.role_permission') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('message.close') }}">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        {{ Form::select('role_permission[]', permission_list() ?? [], $rolePermissions ?? [], [
          'multiple' => 'multiple',
          'class' => 'form-control',
          'id' => 'role_permission'
        ]) }}        
        <input type="hidden" id="modal-url" />
        <input type="hidden" id="role_id" />
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('message.close') }}</button>
        <button type="button" class="btn btn-primary" id="confirm-role-permission">{{ __('message.create') }}</button>
      </div>
    </div>
  </div>
</div>
