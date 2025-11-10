<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class UserModel extends Model
{
    use HasFactory;

    protected $table = 'users';
    protected $guarded = ['id'];
    protected $keyType = 'string';
    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = Str::uuid();
            }
        });
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    public function getUser(){
        return $this->with('kelas')->get();
    }

    public function findUser($id)
    {
        return $this->with('kelas')->findOrFail($id);
    }

    public function updateUser($id, $data)
    {
        $user = $this->findOrFail($id);
        return $user->update($data);
    }

    public function deleteUser($id)
    {
        $user = $this->findOrFail($id);
        return $user->delete();
    }
}