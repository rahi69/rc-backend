<?php
namespace App\Repositories;

use App\Models\Channel;
use Illuminate\Support\Str;

class ChannelRepository
{

    public function all()
    {
       return Channel::all();
    }

    public function create($name)
    {
        Channel::create([
            'name' => $name,
            'slug' => Str::slug($name)
        ]);
    }

    public function update($id , $name)
    {
        Channel::find($id)->update([
            'name' => $name,
            'slug' => Str::slug($name)
        ]);
    }

    public function delete($id)
    {
        Channel::destroy($id);
    }


}