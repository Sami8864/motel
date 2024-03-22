<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomRelationAmenity extends Model
{
    use HasFactory;
    protected  $table='room_relation_amenitiys';
    protected $fillable=['room','added_by','aminity'];
}
