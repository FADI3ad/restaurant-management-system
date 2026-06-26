<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Guarded;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Model;


#[Guarded(['id'])]
#[Table('sections')]
class Section extends Model
{
    
}
