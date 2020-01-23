<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CadastroControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function testGetAll()
    {
        $response = $this->json('GET', '/cadastros/getall');

        $response
            ->assertStatus(201);
    }

    public function testeSave()
    {
        Storage::fake('avatars');

        $file = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->json('POST', '/cadastros/save', [
            'modal_id' => '0',
            'modal_name' => 'Teste',
            'modal_email' => 'teste@teste.com.br',
            'modal_fone' => '67988588584',
            'modal_msg' => 'teste',
            'ip_num' => '0',
            'arquivo' => $file
        ]);

        $response
            ->assertStatus(200);


        $this->assertDatabaseHas('tb_cadastros',['email'=>'teste@teste.com.br']);
    }

    public function testCadastrar(){
        \App\TbCadastro::create([
            'nome' => 'Teste',
            'email' => 'teste@teste.com.br',
            'telefone' => "00999997878",
            'msg' => 'teste',
            'arquivo'=> 'teste/teste.pdf',
            'ip' => 'teste'
        ]);

        $this->assertDatabaseHas('tb_cadastros',['email'=>'teste@teste.com.br']);
    }

}
