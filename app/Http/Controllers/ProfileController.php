<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Exceptions\ValidationException;
use App\Modules\User\UserService as Service;
use App\Modules\User\UserValidation as Validation;
use App\Rules\ValidateAuthPassword;
use App\Rules\ValidateRecentPassword;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

final class ProfileController extends Controller 
{
    protected $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        crypto_secrets();
        
        $id = auth()->user()->id;
        $row = $this->service->findById($id);
        
        return view('user.edit', compact('id', 'row'))
            ->with('title', __('message.edit_profile'))
            ->with('is_profile', true)
            ->with('crypto_salt', session('crypto_salt'))
            ->with('crypto_iv', session('crypto_iv'))
            ->with('crypto_key', session('crypto_key'))
            ->with('crypto_key_size', session('crypto_key_size'))
            ->with('crypto_iterations', session('crypto_iterations'));
    }

    public function update(Request $request, $id)
    {
        try 
        {
            $validator = Validator::make(
                $request->all(), 
                Validation::getRules((int) $id, [
                    'first_name',
                    'last_name',
                    'email',
                    'contact_number'
                ])
            );

            if ($validator->fails()) 
                return $this->error($validator->errors());

            $this->service->updateById($validator->validated(), $id);
            return $this->updated();
        }
        catch (Throwable $exception) 
        {
            return $this->handler($error);
        }
    }

    public function changePassword(Request $request, $id)
    {
        try 
        {
            $request->merge([
                'current_password' => crypto_decrypt($request->current_password),
                'password' => crypto_decrypt($request->password),
                'password_confirmation' => crypto_decrypt($request->password_confirmation),
            ]);

            $rules =  Validation::getRules((int) $id, [
                'password',
                'password_confirmation',
            ]);

            $rules['current_password'] = [
                'bail',
                'required',
                new ValidateAuthPassword(),
            ];

            $rules['id'] = Validation::getIDRule('users');

            $validator = Validator::make(
                $request->all(), 
                $rules,
                Validation::messages()
            );

            if ($validator->fails()) 
                return $this->error($validator->errors());

            $this->service->updateById($validator->validated(), $id, true);
            return $this->updated();
        }
        catch (Throwable $exception) 
        {
            return $this->handler($error);
        }
    }

    public function updatePassword(Request $request, $id) 
    {
        try 
        {
            $request->merge([
                'password' => crypto_decrypt($request->password),
                'password_confirmation' => crypto_decrypt($request->password_confirmation),
            ]);

            $rules =  Validation::getRules((int) $id, [
                'password',
                'password_confirmation',
            ]);

            $rules['id'] = Validation::getIDRule('users');

            $validator = Validator::make(
                $request->all(), 
                $rules,
                Validation::messages()
            );

            if ($validator->fails()) 
                return $this->error($validator->errors());

            $this->service->updateById($validator->validated(), $id, true);
            return $this->updated();
        }
        catch (Throwable $exception) 
        {
            return $this->handler($error);
        }
    }

    public function updatePhoto(Request $request, $id) 
    {
        try 
        {
            $rules =  Validation::getRules((int) $id, [
                'image',
            ]);

            $rules['id'] = Validation::getIDRule('users');

            $validator = Validator::make(
                $request->all(), 
                $rules
            );

            if ($validator->fails()) 
                return $this->error($validator->errors());

            $this->service->updatePhoto($validator->validated(), $id);
            return $this->updated();
        }
        catch (Throwable $exception) 
        {
            return $this->handler($error);
        }
    }

}
