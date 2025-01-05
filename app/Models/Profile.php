<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $table = 'userprofile'; // Explicitly specify the table name

    protected $fillable = ['image', 'address', 'number'];

    public function user()
    {
        return $this->belongsTo(User::class); // BelongsTo relationship back to User
    }
}
