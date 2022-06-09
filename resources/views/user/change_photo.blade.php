<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        {{ __('message.change_photo') }}
                    </h6>
                </div>
                <div class="card-body">
                    <form id="change-photo-form">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="required">{{ __('Upload Photo') }}</label>
                                <input type="file" class="form-control" name="image" id="image" />
                                <span class="text-danger form-error" id="image_error"></span>
                            </div>
                        </div>
                        <button type="submit" id="change-photo-btn" class="btn btn-primary">
                            {{ __('message.change_photo') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>