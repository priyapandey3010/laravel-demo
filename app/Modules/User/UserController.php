<?php declare(strict_types=1);

namespace App\Modules\User;

use App\Http\Controllers\Controller;
use App\Exceptions\ValidationException;
use App\Modules\User\UserService as Service;
use App\Modules\User\UserValidation as Validation;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

final class UserController extends Controller 
{
    protected $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        guard('user-view');
        return view('user.index')
            ->with('title', __('message.user_list'));
    }

    public function datalist()
    {
        guard('user-view');
        $response = $this->service->getDataTableList();
        return $this->success($response);
    }

    public function create()
    {
        guard('user-create');
        crypto_secrets();
        return view('user.edit')
            ->with('title', __('message.new_user'))
            ->with('crypto_salt', session('crypto_salt'))
            ->with('crypto_iv', session('crypto_iv'))
            ->with('crypto_key', session('crypto_key'))
            ->with('crypto_key_size', session('crypto_key_size'))
            ->with('crypto_iterations', session('crypto_iterations'));
    }

    public function store(Request $request) 
    {
        guard('user-create');
        try 
        {
            $request->merge([
                'password' => crypto_decrypt($request->password),
                'password_confirmation' => crypto_decrypt($request->password_confirmation),
            ]);
            
            $validator = Validator::make(
                $request->all(), 
                Validation::getRules(),
                Validation::messages()
            );

            if ($validator->fails()) 
                return $this->error($validator->errors());

            $response = $this->service->create($validator->validated());
            return $this->created($response);
        }
        catch (Throwable $exception) 
        {
            return $this->handler($error);
        }
    }

    public function edit($id)
    {
        guard('user-update');
        crypto_secrets();
        $row = $this->service->findById($id);
        
        return view('user.edit', compact('id', 'row'))
            ->with('title', __('message.edit_user'))
            ->with('crypto_salt', session('crypto_salt'))
            ->with('crypto_iv', session('crypto_iv'))
            ->with('crypto_key', session('crypto_key'))
            ->with('crypto_key_size', session('crypto_key_size'))
            ->with('crypto_iterations', session('crypto_iterations'));
    }

    public function show($id)
    {
        guard('user-update');
        try 
        {
            $response = $this->service->findById($id);
            return $this->success($response);
        }
        catch (Throwable $exception) 
        {
            return $this->handler($error);
        }
    }

    public function update(Request $request, $id) 
    {
        guard('user-update');
        try 
        {
            $validator = Validator::make(
                $request->all(), 
                Validation::getRules((int) $id, [
                    'first_name',
                    'last_name',
                    'email',
                    'username',
                    'role_id',
                    'department_id',
                    'designation_id',
                    'category_type',
                    'bench_id',
                    'court_id',
                    'contact_number'
                ])
            );

            if ($validator->fails()) 
                return $this->error($validator->errors());

            $this->service->update($validator->validated(), $id);
            return $this->updated();
        }
        catch (Throwable $exception) 
        {
            return $this->handler($error);
        }
    }

    public function updatePassword(Request $request, $id) 
    {
        guard('user-update');
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
        guard('user-update');
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

    public function activate($id)
    {
        guard('user-update');
        try 
        {
            $this->service->activate($id);
            return $this->updated();
        }
        catch (Throwable $exception) 
        {
            return $this->handler($error);
        }
    }

    public function deactivate($id)
    {
        guard('user-update');
        try 
        {
            $this->service->deactivate($id);
            return $this->updated();
        }
        catch (Throwable $exception) 
        {
            return $this->handler($error);
        }
    }
    
    public function destroy($id)
    {
        guard('user-delete');
        try 
        {
            $this->service->destroy($id);
            return $this->deleted();
        }
        catch (Throwable $exception) 
        {
            return $this->handler($error);
        }
    }
}