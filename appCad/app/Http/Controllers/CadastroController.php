<?php

namespace App\Http\Controllers;

use App\TbCadastro;
use Illuminate\Http\Request;

class CadastroController extends Controller
{
    public function index()
    {
        $cadastros   = TbCadastro::all();
        return view('welcome',array('cadastros' => $cadastros));
    }

    public function getAll(){
        $cadastros   = TbCadastro::all();
        return Response()->json($cadastros,201);
    }

    public function get($id){
        $cadastros   = TbCadastro::find($id);
        return Response()->json($cadastros,201);

    }

    public function delete($id){
        $cad = TbCadastro::find($id);
        $cad->delete();

        return Response('ok');
    }

    public function save(Request $data){
        $file = $data->file('arquivo');
        $filename = time().$file->getClientOriginalName();
        $upload = $data->arquivo->storeAs('public', $filename);

        if($data->input('modal_id') == '0'){
            $nv_user = new TbCadastro();
            $nv_user->nome      = $data->input('modal_name');
            $nv_user->email     = $data->input('modal_email');
            $nv_user->telefone  = $data->input('modal_fone');
            $nv_user->msg       = $data->input('modal_msg');
            $nv_user->ip        = 'localhost';
            $nv_user->arquivo   = '/storage/'.$filename;
            $nv_user->save();

        }
        else
        {
            $nv_user            = TbCadastro::find($data->input('modal_id'));
            if($nv_user){
                $nv_user->nome      = $data->input('modal_name');
                $nv_user->email     = $data->input('modal_email');
                $nv_user->telefone  = $data->input('modal_fone');
                $nv_user->msg       = $data->input('modal_msg');
                $nv_user->arquivo   = $filename;
                $nv_user->ip        = 'localhost';
                $nv_user->arquivo   = '/storage/'.$filename;
                $nv_user->save();
            } else {
                echo "Cadastro não encontrado";
            }
        }
    }


}
