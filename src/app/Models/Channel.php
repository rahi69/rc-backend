<?php

namespace App\Models;

use Database\Factories\ChannelFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    use HasFactory;

    protected static function newFactory()
    {
        return ChannelFactory::new();
    }

    protected $fillable = ['name', 'slug'];

    public function threads()
    {
        return $this->hasMany(Thread::class);
    }
}
