<!-- Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">{{ __('message.confirm_delete') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('message.close') }}">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        {{ __('message.delete_modal_body') }}
        <input type="hidden" id="modal-delete-url" />
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('message.close') }}</button>
        <button type="button" class="btn btn-primary" id="confirm-delete">{{ __('message.delete') }}</button>
      </div>
    </div>
  </div>
</div>
