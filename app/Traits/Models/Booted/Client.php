<?php

namespace App\Traits\Models\Booted;

trait Client
{
    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        // static::creating(function ($model) {
        //     // ... code here
        // });

        // static::created(function ($model) {
        //     // ... code here
        // });

        // static::updating(function ($model) {
        //     //  ... code here
        // });

        static::updated(function ($model) {
            dbg($model->link);
            dbg($model->getOriginal('link'));
            if ($model->link != $model->getOriginal('link')) {
                dbg('updated');
            }
            dd(storage_path('app/public/clients/eddin-gmc/zWxXpyrKFxKCLDdAbVJmlGsCCpBDAKYcC5t.png'));
        });

        // static::deleting(function ($model) {
        //     // ... code here
        // });

        // static::deleted(function ($model) {
        //     // ... code here
        // });
    }
}
