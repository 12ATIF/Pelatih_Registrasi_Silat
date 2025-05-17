<?php

namespace App\Traits;

trait LogsActivity
{
    protected static function bootLogsActivity()
    {
        // Event handlers untuk mencatat aktivitas
        static::created(function($model) {
            $model->logActivity('created');
        });

        static::updated(function($model) {
            $model->logActivity('updated');
        });

        static::deleted(function($model) {
            $model->logActivity('deleted');
        });
    }

    protected function logActivity($action)
    {
        // Metode sederhana untuk logging aktivitas
        // Anda bisa mengubahnya sesuai kebutuhan Anda
        $admin = auth()->guard('admin')->user();
        $admin_id = $admin ? $admin->id : null;

        if ($admin_id) {
            \App\Models\AdminLog::create([
                'admin_id' => $admin_id,
                'aksi' => $action,
                'model' => get_class($this),
                'model_id' => $this->id,
                'waktu_aksi' => now(),
                // 'perubahan' => json_encode($this->getDirty()), // Opsional
            ]);
        }
    }
}