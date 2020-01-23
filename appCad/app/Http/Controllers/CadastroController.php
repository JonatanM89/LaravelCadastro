<?php

namespace App\Http\Controllers;

use App\TbCadastro;
use Illuminate\Http\Request;
use PHPMailer\PHPMailer;

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
            $nv_user->ip        = $data->input('ip_num');
            $nv_user->arquivo   = '/storage/'.$filename;
            if ($nv_user->save() ){
                if(env('MAIL_GOOGLE') != null){
                    $mail               = new PHPMailer\PHPMailer(); ; // create a n
                    $mail->SMTPDebug    = 1; // debugging: 1 = errors and messages, 2 = messages only
                    $mail->SMTPAuth     = true; // authentication enabled
                    $mail->SMTPSecure   = 'tls'; // secure transfer enabled REQUIRED for Gmail
                    $mail->Host         = "smtp.gmail.com";
                    $mail->Port         = 587; // or 587
                    $mail->Mailer       = "smtp";
                    $mail->Username     = env('MAIL_GOOGLE');
                    $mail->Password     = env('PASS_GOOGLE');
                    $mail->Subject      = 'Cadastro realizado';
                    $mail->Body         = 'Cadastro realizado';

                    $mail->SetFrom(env('MAIL_GOOGLE'), 'Teste cadastro');
                    $mail->IsHTML(true);
                    $mail->IsSMTP();
                    $mail->AddAddress($data->input('modal_email'));

                    if ($mail->Send()) {
                        return 'Email Sended Successfully';
                    } else {
                        return 'Failed to Send Email';
                    }
                }

            }

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
                $nv_user->ip        = $data->input('ip_num');
                $nv_user->arquivo   = '/storage/'.$filename;
                $nv_user->save();
            } else {
                echo "Cadastro não encontrado";
            }
        }
    }


}
