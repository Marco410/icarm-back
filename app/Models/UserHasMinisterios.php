<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserHasMinisterios extends BaseModel
{
    use HasFactory;


    protected $table = 'user_has_ministerios';
    protected $fillable = [
        'user_id',
        'ministerio_id'
    ];

    protected $hidden = [
       'updated_at','created_at'
    ];

    public function ministerio(){
        return $this->hasOne(Ministerio::class,'id','ministerio_id');
    }

}
