<?php

namespace App\Traits;

trait Respond 
{
    public function success($data = [], $message = null)
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data
        ]);
    }

    public function error($errors = [], $message = null)
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'errors' => $errors
        ]);
    }

    public function created($data = [])
    {
        return $this->success($data, __('message.created'));
    }

    public function updated($data = [])
    {
        return $this->success($data, __('message.updated'));
    }

    public function deleted()
    {
        return $this->success([], __('message.deleted'));
    }

    public function notCreated($errors)
    {
        return $this->error($errors, __('message.not_created'));
    }

    public function notUpdated($errors = [])
    {
        return $this->error($errors, __('message.not_updated'));
    }

    public function notDeleted($errors = [])
    {
        return $this->error($errors, __('message.not_deleted'));
    }

    public function handler($exception)
    {
        return $this->error($exception, $exception->getMessage());
    }
}
