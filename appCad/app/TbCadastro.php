<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TbCadastro extends Model
{
    protected $fillable = ['nome','email','telefone','msg','arquivo','ip'];
}
