<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory , SoftDeletes;
    protected $guarded =[];
    protected $dates = ['deleted_at'];
    protected $softDelete = true;

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function section(){
        return $this->belongsTo(Section::class,'section_id');
    }

    public function attachement(){
        return $this->hasMany(InvoiceAttachement::class,'invoice_id');
    }
}
