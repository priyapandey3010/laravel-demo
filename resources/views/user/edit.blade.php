@extends('components.admin.layout')

@section('page-content')

<div class="container-fluid">
    @isset($is_profile)

        @include('components.admin.page-header', ['pageTitle' => __('message.profile')])

    @else 
    
        @include('components.admin.page-header', ['pageTitle' => __('message.user')])

    @endisset
    
    @include('components.admin.alert-manager')

    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        @isset($id)
                            @isset($is_profile)
                                {{ __('message.edit_profile') }}
                            @else
                                {{ __('message.edit_user') }}
                            @endisset
                        @else 
                            {{ __('message.new_user') }}
                        @endisset
                    </h6>
                </div>
                <div class="card-body">
                    <form id="form">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="required">{{ __('message.first_name') }}</label>
                                <input type="text" class="form-control" name="first_name" id="first_name" placeholder="{{ __('message.first_name') }}"
                                    @if (isset($row) && isset($row['first_name']))
                                        value="{{ $row['first_name'] }}"
                                    @endif
                                >
                                <span class="text-danger form-error" id="first_name_error"></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label>{{ __('message.last_name') }}</label>
                                <input type="text" class="form-control" name="last_name" id="last_name" placeholder="{{ __('message.last_name') }}"
                                    @if (isset($row) && isset($row['last_name']))
                                        value="{{ $row['last_name'] }}"
                                    @endif
                                >
                                <span class="text-danger form-error" id="last_name_error"></span>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="required">{{ __('message.email') }}</label>
                                <input type="text" class="form-control" name="email" id="email" placeholder="{{ __('message.email') }}"
                                    @if (isset($row) && isset($row['email']))
                                        value="{{ $row['email'] }}"
                                    @endif
                                >
                                <span class="text-danger form-error" id="email_error"></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="required">{{ __('message.username') }}</label>
                                <input type="text" class="form-control" name="username" id="username" placeholder="{{ __('message.username') }}"
                                    @if (isset($row) && isset($row['username']))
                                        value="{{ $row['username'] }}"
                                    @endif
                                    @isset($is_profile)
                                        disabled="disabled"
                                    @endisset
                                >
                                <span class="text-danger form-error" id="username_error"></span>
                            </div>
                        </div>
                        @if (!isset($row))
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="required">{{ __('message.password') }}</label>
                                <input type="password" class="form-control" name="password" id="password" placeholder="{{ __('message.password') }}"
                                    @if (isset($row) && isset($row['password']))
                                        value="{{ $row['password'] }}"
                                    @endif
                                >
                                <span class="text-danger form-error" id="password_error"></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="required">{{ __('message.confirm_password') }}</label>
                                <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="{{ __('message.confirm_password') }}"
                                    @if (isset($row) && isset($row['confirm_password']))
                                        value="{{ $row['confirm_password'] }}"
                                    @endif
                                >
                                <span class="text-danger form-error" id="password_confirmation_error"></span>
                            </div>
                        </div>
                        @endif
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="required">{{ __('message.designation') }}</label>
                                {{ Form::select('designation_id', designation_list(), $row['designation_id'] ?? null, ['class' => 'form-control', 'id' => 'designation_id', 'disabled' => isset($is_profile)]) }}
                                <span class="text-danger form-error" id="designation_id_error"></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="required">{{ __('message.department') }}</label>
                                {{ Form::select('department_id', department_list(), $row['department_id'] ?? null, ['class' => 'form-control', 'id' => 'department_id', 'disabled' => isset($is_profile)]) }}
                                <span class="text-danger form-error" id="department_id_error"></span>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label class="required">{{ __('message.role') }}</label>
                                {{ Form::select('role_id', role_list(), $row['role_id'] ?? null, ['class' => 'form-control', 'id' => 'role_id', 'disabled' => isset($is_profile)]) }}
                                <span class="text-danger form-error" id="role_id_error"></span>
                            </div>
                            <div class="form-group col-md-6">
                                <label class="required">{{ __('message.category_type') }}</label>
                                {{ Form::select('category_type', category_type_list(), $row['category_type_id'] ?? null, ['class' => 'form-control', 'id' => 'category_type', 'disabled' => isset($is_profile)]) }}
                                <span class="text-danger form-error" id="category_type_error"></span>
                            </div>
                        </div>
                        <div class="form-row" id="display-board-user">
                            <div class="form-group col-md-6" id="bench-dropdown" style="display:none;">
                                <label class="required">{{ __('message.bench') }}</label>
                                {{ Form::select('bench_id', bench_list(), $row['bench_id'] ?? null, ['class' => 'form-control', 'id' => 'bench_id', 'disabled' => isset($is_profile)]) }}
                                <span class="text-danger form-error" id="bench_id_error"></span>
                            </div>
                            <div class="form-group col-md-6" id="court-dropdown" style="display:none;">
                                <label class="required">{{ __('message.court') }}</label>
                                {{ Form::select('court_id', court_list(), $row['court_id'] ?? null, ['class' => 'form-control', 'id' => 'court_id', 'disabled' => isset($is_profile)]) }}
                                <span class="text-danger form-error" id="court_id_error"></span>
                            </div>
                        </div>
                        <div class="form-row">
                            @if (!isset($row))
                            <div class="form-group col-md-6">
                                <label class="required">{{ __('Upload Photo') }}</label>
                                <input type="file" class="form-control" name="image" id="image" />
                                <span class="text-danger form-error" id="image_error"></span>
                            </div>
                            @endif
                            <div class="form-group col-md-6">
                                <label class="required">{{ __('message.contact_number') }}</label>
                                <input type="text" class="form-control" name="contact_number" id="contact_number" placeholder="{{ __('message.contact_number') }}" maxlength="10"
                                    @if (isset($row) && isset($row['contact_number']))
                                        value="{{ $row['contact_number'] }}"
                                    @endif
                                >
                                <span class="text-danger form-error" id="contact_number_error"></span>
                            </div>
                        </div>
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

@isset($row)

    @include('user.change_password', ['id' => $row['id'], 'is_profile' => isset($is_profile)])
    
    @include('user.change_photo', ['id' => $row['id']])

@endisset

@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/4.1.1/crypto-js.min.js" integrity="sha512-E8QSvWZ0eCLGk4km3hxSsNmGWbLtSCSUcewDQPQWZF6pEU8GlT8a5fF32wOl1i8ftdMhssTrF/OhyGWwonTcXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        function crypto(secret) {
            if (secret.length > 0) {
                var salt = CryptoJS.enc.Hex.parse("{{ $crypto_salt }}");
                var iv = CryptoJS.enc.Hex.parse("{{ $crypto_iv }}");
                var key = CryptoJS.PBKDF2(
                    "{{ $crypto_key }}", 
                    salt, { 
                        hasher: CryptoJS.algo.SHA512, 
                        keySize: {{ $crypto_key_size }}, 
                        iterations: {{ $crypto_iterations }} 
                    }
                ); 
                var encrypted = CryptoJS.AES.encrypt(secret, key, {iv: iv});
                var encryptedData = {
                    ciphertext : CryptoJS.enc.Base64.stringify(encrypted.ciphertext),
                    salt : CryptoJS.enc.Hex.stringify(salt),
                    iv : CryptoJS.enc.Hex.stringify(iv)    
                };  
                return encryptedData;
            }
        }
        $("#form").on("submit", function (event) {
            event.preventDefault();
            var firstName = $("#first_name").val();
            var lastName = $("#last_name").val();
            var email = $("#email").val();
            var userName = $("#username").val();
            @if (!isset($row))
                var password = $("#password").val();
                var passwordConfirmation = $("#password_confirmation").val();
                var uploadImage = document.getElementById("image").files[0];
            @endif
            var designationId = $("#designation_id").val();
            var departmentId = $("#department_id").val();
            var roleId = $("#role_id").val();
            var categoryType = $("#category_type").val();
            var contactNumber = $("#contact_number").val();
            var csrfToken = "{{ csrf_token() }}";
            var benchId = null;
            var courtId = null;

            if (categoryType === "{{ config('category.display_board') }}") {
                benchId = $("#bench_id").val();
                courtId = $("#court_id").val();
            }

            @if (!isset($is_profile) && !isset($row))
            if (password) {
                password = crypto(password).ciphertext;
            }

            if (passwordConfirmation) {
                passwordConfirmation = crypto(passwordConfirmation).ciphertext;
            }
            @endif

            var formData = new FormData();
            formData.append("first_name", firstName);
            formData.append("last_name", lastName);
            formData.append("email", email);
            formData.append("username", userName);
            @if (!isset($row))
                formData.append("password", password);
                formData.append("password_confirmation", passwordConfirmation);
                formData.append("image", uploadImage);
            @endif
            formData.append("designation_id", designationId);
            formData.append("department_id", departmentId);
            formData.append("role_id", roleId);
            formData.append("category_type", categoryType);
            formData.append("contact_number", contactNumber);
            if (categoryType === "{{ config('category.display_board') }}") {
                if (roleId === "{{ config('role.bench-admin') }}") {
                    formData.append("bench_id", benchId);
                } else if (roleId === "{{ config('role.court-user') }}") {
                    formData.append("bench_id", benchId);
                    formData.append("court_id", courtId);
                } else {
                    formData.append("bench_id", '');
                    formData.append("court_id", '');
                }
            }
            formData.append("_token", csrfToken);
            
            @isset($id)
                var method = 'PUT';
                
                @isset($is_profile)
                    var url = "{{ url('profile/'. $id) }}";
                    var redirectTo = "{{ url('profile') }}";
                @else 
                    var url = "{{ url('/users/'. $id) }}";
                    var redirectTo = "{{ url('users') }}";
                @endisset

            @else 
                var method = 'POST';
                var url = "{{ url('/users') }}";
                var redirectTo = "{{ url('users') }}";
            @endisset

            

            @if (!isset($row))
                $.ajax({
                    url: url,
                    method: method,
                    data: formData,
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(responseData) {
                        if (responseData.status) {
                            success(responseData);
                            redirect(redirectTo);
                        }
                        else if (!responseData.status && responseData.errors) {
                            applyValidationErrors(responseData);
                            enableSubmit();
                            return;
                        }
                        else {
                            failure(responseData);
                            enableSubmit();
                        }
                    },
                    error: function() {
                        $("#spinner").html('<span class="text-danger">Something went wrong..</span>');
                    }
                });
            @else 
                var requestData = {
                    url: url,
                    method: method,
                    body: {
                        first_name: firstName,
                        last_name: lastName,
                        email: email,
                        @if (!isset($is_profile))
                            username: userName,
                            role_id: roleId,
                            department_id: departmentId,
                            designation_id: designationId,
                            category_type: categoryType,
                            bench_id: benchId,
                            court_id: courtId,
                        @endif
                        contact_number: contactNumber,
                        _token: csrfToken
                    }
                };
                sendRequest(requestData, redirectTo);
            @endif
        });

        @if (isset($row) && isset($row['category_type_id']) && $row['category_type_id'] === config('category.display_board'))
            $("#display-board-user").show();
        @else 
            $("#display-board-user").hide();
        @endif

        @if (isset($row) && $row['category_type_id'] == config('category.display_board'))
            @if ($row['role_id'] == config('role.bench-admin'))
                $("#bench-dropdown").show();
            @elseif ($row['role_id'] == config('role.court-user')) 
                $("#bench-dropdown").show();
                $("#court-dropdown").show();
            @endif
        @endif

        $("#category_type").change(function() {
            var categoryType = $(this).val();
            var role = $("#role_id").val();
            if (!role) {
                alert("Please select any one role!");
                $("#category_type").val('');
                return;
            }
            if (categoryType === "{{ config('category.display_board') }}") {
                $("#display-board-user").show();
                if (role === "{{ config('role.bench-admin') }}") {
                    $("#bench-dropdown").show();
                    $("#court-dropdown").hide();
                } else if (role === "{{ config('role.court-user') }}") {
                    $("#bench-dropdown").show();
                    $("#court-dropdown").show();
                } else {
                    $("#bench-dropdown").hide();
                    $("#court-dropdown").hide();
                }
            } else {
                $("#display-board-user").hide();
            }
        });

        $("#role_id").change(function() {
            $("#category_type").val('').trigger("change");
        });

        @isset($id)
        $("#change-photo-form").on("submit", function (event) {
            event.preventDefault();
            var uploadImage = document.getElementById("image").files[0];
            var csrfToken = "{{ csrf_token() }}";
            var method = 'POST';

            @isset($is_profile)
            var url = "{{ url('profile/'. $id .'/update-photo') }}";
            var redirectTo = "{{ url('/profile') }}";
            @else
            var url = "{{ url('users/'. $id .'/update-photo') }}";
            var redirectTo = "{{ url('/users/'. $id . '/edit') }}";
            @endisset

            var formData = new FormData();
            formData.append("id", "{{ $id }}");
            formData.append("image", uploadImage);
            formData.append("_token", csrfToken);

            $.ajax({
                url: url,
                method: method,
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                success: function(responseData) {
                    if (responseData.status) {
                        success(responseData);
                        redirect(redirectTo);
                    }
                    else if (!responseData.status && responseData.errors) {
                        applyValidationErrors(responseData);
                        enableSubmit();
                        return;
                    }
                    else {
                        failure(responseData);
                        enableSubmit();
                    }
                },
                error: function() {
                    $("#spinner").html('<span class="text-danger">Something went wrong..</span>');
                }
            });
        });

       
        $("#change-password-form").on("submit", function (event) {
            event.preventDefault();
            var password = $("#password").val();
            var passwordConfirmation = $("#password_confirmation").val();
            var csrfToken = "{{ csrf_token() }}";
            var method = 'POST';

            @isset($is_profile)
                var url = "{{ url('profile/'. $id .'/update-password') }}";
            @else 
                var url = "{{ url('users/'. $id .'/update-password') }}";
            @endisset

            if (password) {
                password = crypto(password).ciphertext;
            }

            if (passwordConfirmation) {
                passwordConfirmation = crypto(passwordConfirmation).ciphertext;
            }

            @isset($is_profile)

                var currentPassword = $("#current_password").val();
                if (currentPassword) {
                    currentPassword = crypto(currentPassword).ciphertext;
                }

            @endisset
           
            var requestData = {
                url: url,
                method: method,
                body: {
                    id: "{{$id}}",
                    password: password,
                    password_confirmation: passwordConfirmation,
                    @isset($is_profile)
                        current_password: currentPassword,
                    @endisset
                    _token: csrfToken
                }
            };

            @if(isset($is_profile)) 
                var redirectTo = "{{ url('/logout') }}";
                sendRequest(requestData, redirectTo, true);
            @else 
                var redirectTo = "{{ url('/users/'. $id . '/edit') }}";
                sendRequest(requestData, redirectTo);
            @endif

        });
        @endisset

       
    </script>

@endsection