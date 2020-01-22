@extends('master')

@section('content')
    <h1>Cadastros</h1>
    <p id="n_ip">IP</p>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item active" aria-current="page">Lista de cadastros</li>
        </ol>
      </nav>
    <input name="_token" id="token" type="hidden" value="{{ csrf_token() }}"/>
    <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <button class="btn btn-primary" onclick="editarAddUser(0)" style="float:right">Adicionar</button>
        </div>
    </div>
    <br/>
     <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <div id="alert_aguarde" style="display: none" class="alert alert-warning" role="alert">
                Aguarde, importando usuários
            </div>

            <div id="alert_erro" style="display: none" class="alert alert-danger" role="alert">
                Ocorreu um erro
            </div>
        </div>
    </div>
    <div class="row">
        <div id="tabela_usuarios" class="col-sm-12 col-md-12 col-lg-12 col-xl-12">

        </div>
    </div>


    <div id="modal_editar" class="modal fade" tabindex="1" role="dialog">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h3 id="title_modal" class="modal-title">Editar</h3>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <form id="form_enviar_docs" name="form_enviar_docs" action="javascript:void(0)" enctype="multipart/form-data" method="POST" >
                    <input type="hidden" value="0" id="modal_id" name="modal_id" />

                    <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                      <label>Nome</label>
                      <input type="text" class="form-control" id="modal_name" name="modal_name" placeholder="Nome" value="">
                    </div>
                    <div class="form-group col-sm-12 col-md-8 col-lg-8 col-xl-8">
                      <label>Email</label>
                      <input type="email" class="form-control" id="modal_email" name="modal_email" placeholder="Email" value="">
                    </div>
                    <div class="form-group col-sm-12 col-md-4 col-lg-4 col-xl-4">
                      <label>Telefone</label>
                      <input type="text" class="form-control" id="modal_fone" name="modal_fone" placeholder="Telefone" value="">
                    </div>
                    <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                          <label>Arquivo (Max 500kb)</label>
                          <input accept=".pdf, .doc, .docx, .odt, .txt" name="arquivo" id="modal_arquivo" name="modal_arquivo" class="form-control" type="file" required />
                          <p class="text-right"><small>.pdf, .doc, .docx, .odt, .txt</small></p>
                    </div>
                    <div class="form-group col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <label>Mensagem</label>
                        <textarea class="form-control" id="modal_msg" name="modal_msg"></textarea>
                    </div>
                    </form>
                </div>
                <div id="alert_erro_modal" style="display: none" class="alert alert-danger" role="alert">
                </div>
                <div id="alert_aguarde_modal" style="display: none" class="alert alert-warning" role="alert">
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Sair</button>
              <button onclick="saveUser()" type="button" class="btn btn-primary">Salvar</button>
            </div>
          </div>
        </div>
      </div>
@stop

@section('js')
<script>

window.onload=function()
{
  carregarUsuarios()



/* $.ajax({
            url : "http://meuip.com/api/meuip.php",
            type: 'get',
            beforeSend:function()
            {
                alert()
            },
            success : function (data)
            {
                alert(data.ip)
                $("#n_ip").text("Meu IP público é: ", data.ip);
            },
            complete : function (data){
                alert(JSON.stringify((data)))
                $("#n_ip").text("Meu IP público é: ", JSON.stringify((data)));
            },
            error : function (err)
            {

            }
        });*/
}



$("#modal_arquivo").on("change", function (e) {
    if(this.files[0].size > 500000){
       alert("Arquivo maior que 500kb");
       this.value = "";
    }

    var ext = this.value.match(/\.([^\.]+)$/)[1];
    switch (ext) {
        case 'pdf':
        case 'doc':
        case 'docx':
        case 'odt':
        case 'txt':
        this.value = this.value;
        break;
        default:
        alert('Tipo de arquivo invalido');
        this.value = '';
    }

});

function editarAddUser(id){

    clearModal()

    if(id == 0){
        $("#title_modal").text("Adicionar")
        $("#modal_editar").modal()
    }
    else{
        $("#title_modal").text("Editar")
        editUser(id)
    }

}

function saveUser(){
    validar = true;
    campos  = '';

    if( $("#modal_name").val() == ""){
        validar = false;
        campos  = 'Preencha o nome / ';
    }

    if( $("#modal_email").val() == ""){
        validar = false;
        campos  += 'Preencha o email / ';
    } else {
        if ( !validacaoEmail( $("#modal_email").val() ) ) {
            validar = false;
            campos  += 'Email inválido / ';
        }
    }

    if( $("#modal_fone").val() == ""){
        validar = false;
        campos  += 'Preencha o telefone /';
    }

    if( $("#modal_msg").val() == ""){
        validar = false;
        campos  += 'Preencha a mensagem / ';
    }

    if( $("#modal_arquivo").val() == ""){
        validar = false;
        campos  += 'Escolha um arquivo / ';
    }

    if( !validar){
        $("#alert_erro_modal").empty().html("<p>"+campos+"</p>")
        $("#alert_erro_modal").show()
    } else {

        $("#alert_erro_modal").empty()
        $("#alert_erro_modal").hide()

        var formData = new FormData($("#form_enviar_docs")[0]);

        $.ajax({
            url : "/cadastros/save",
            cache:false,
            data: formData,
            processData: false,
            contentType: false,
            type: 'post',
            headers: {
                'X-CSRF-TOKEN':  $("#token").val()
            },
            beforeSend:function()
            {
                $("#alert_aguarde_modal").empty().html("<p>Salvando</p>")
                $("#alert_aguarde_modal").show()
            },
            success : function (data)
            {
                clearModal()
                $("#modal_editar").modal("toggle")
                carregarUsuarios()
            },
            error : function (err)
            {
                $("#alert_erro_modal").empty().html("<p>Ocorreu um erro ao salvar!</p>")
                $("#alert_erro_modal").show()
                $("#alert_aguarde_modal").hide()
            }
        });

    }
}

function apagar_usuario(id)
{
    var r = confirm("Deseja realmente excluir este cadastro? Esta ação será irreverssível!");
    if (r == true) {
        $.ajax({
            url : "/cadastros/delete/"+id,
            cache:false,
            type: 'delete',
            headers: {
                'X-CSRF-TOKEN': $("#token").val()
            },
            beforeSend:function()
            {

            },
            success : function (data)
            {
                carregarUsuarios()
            },
            error : function (err)
            {
                $("#alert_erro").empty().html('<p>Ocorreu um erro ao tentar excluir o usuário!</p>');
                $("#alert_erro").show();
            }
            });
    }

}

function validacaoEmail(field) {
    usuario = field.substring(0, field.indexOf("@"));
    dominio = field.substring(field.indexOf("@")+ 1, field.length);
    if ((usuario.length >=1) &&
        (dominio.length >=3) &&
        (usuario.search("@")==-1) &&
        (dominio.search("@")==-1) &&
        (usuario.search(" ")==-1) &&
        (dominio.search(" ")==-1) &&
        (dominio.search(".")!=-1) &&
        (dominio.indexOf(".") >=1)&&
        (dominio.lastIndexOf(".") < dominio.length - 1))
    {
        return true;
    }
    else{
        return false;
    }
}

async function clearModal(){
    $("#modal_id").val("0");
    $("#modal_name").val("");
    $("#modal_email").val("");
    $("#modal_fone").val("");
    $("#modal_msg").val("");
    $("#alert_aguarde_modal").hide()
    $("#alert_erro_modal").hide()
}

function editUser(id){
    $("#modal_editar").modal()

    $.ajax({
        url : "/users/"+id,
        cache:false,
        type: 'get',
        headers: {
            'X-CSRF-TOKEN': $("#token").val()
        },
        beforeSend:function()
        {

        },
        success : function (data)
        {
            $("#modal_id").val(data.id);
            $("#modal_name").val(data.nome);
            $("#modal_email").val(data.email);
            $("#modal_username").val(data.telefone);
            $("#modal_msg").val(data.msg);
        },
        error : function (err)
        {
            $("#alert_aguarde").hide()
            $("#alert_erro").show()
        }
    });
}

function carregarUsuarios(){
    $.ajax({
        url : "/cadastros/getall",
        cache:false,
        type: 'get',
        headers: {
            'X-CSRF-TOKEN': $("#token").val()
        },
        beforeSend:function()
        {
            $("#tabela_usuarios").empty().html('Aguarde...')
        },
        success : function (data)
        {
            var div = '<table class="table">'
            div    += '   <thead>';
            div    += '     <tr>';
            div    += '       <th scope="col">#</th>';
            div    += '       <th scope="col">Nome</th>';
            div    += '       <th scope="col">Email</th>';
            div    += '       <th scope="col">Arquivo</th>';
            div    += '       <th scope="col">Opções</th>';
            div    += '     </tr>';
            div    += '   </thead>';
            div    += '   <tbody>';

            for (var i = 0; i< data.length; i++)
            {
                div    += ' <tr>';
                div    += '     <th scope="row">'+data[i].id+'</th>';
                div    += '     <td>'+data[i].nome+'</td>';
                div    += '     <td>'+data[i].email+'</td>';
                div    += '     <td><a target="_blank" href="'+data[i].arquivo+'">Ver arquivo</a></td>';
                div    += '     <td>';
                //div    += '         <button style="margin-top:5px" onclick="editarAddUser('+data[i].id+')" class="btn btn-sm btn-primary">Editar</button>';
                div    += '         <button style="margin-top:5px" onclick="apagar_usuario('+data[i].id+')" class="btn btn-sm btn-danger">Excluir</button>';
                div    += '     </td>';
                div    += ' </tr>';
            }

            div    += '   </tbody>';
            div    += '</table>';


           $("#tabela_usuarios").empty().html(div)
        },
        error : function (err)
        {
            $("#alert_aguarde").hide()
            $("#alert_erro").show()
            //alert("Ocorreu um erro: "+err);
        }
    });
}



</script>
@stop
