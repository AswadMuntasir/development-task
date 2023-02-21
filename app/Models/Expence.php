<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expence extends Model
{
    use HasFactory;
    protected $fillable = ['expense_reason', 'amount', 'note', 'src', 'custom_date'];
}
