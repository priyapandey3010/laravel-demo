<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        {{ __('message.change_password') }}
                    </h6>
                </div>
                <div class="card-body">
                    <form id="change-password-form">
                        @if ($is_profile)
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="required">{{ __('message.current_password') }}</label>
                                <input type="password" class="form-control" name="current_password" id="current_password" placeholder="{{ __('message.current_password') }}" />
                                <span class="text-danger form-error" id="current_password_error"></span>
                            </div>
                        </div>
                        @endif
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="required">{{ $is_profile ? __('message.new_password') : __('message.password') }}</label>
                                <input type="password" class="form-control" name="password" id="password" placeholder="{{ $is_profile ? __('message.new_password') : __('message.password') }}" />
                                <span class="text-danger form-error" id="password_error"></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="required">{{ __('message.confirm_password') }}</label>
                                <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="{{ __('message.confirm_password') }}" />
                                <span class="text-danger form-error" id="password_confirmation_error"></span>
                            </div>
                        </div>
                        <button type="submit" id="change-password-btn" class="btn btn-primary">
                            {{ __('Change Password') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>