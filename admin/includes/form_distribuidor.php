<?php
include_once('../../assets/lib/bbdd.php');
include_once('../assets/lib/funciones_admin.php');

// echo $_SESSION['distribuidor'];
?>
<button type="button" class="close" data-bs-dismiss="modal">&times;</button>

<div class="row form-modal">
    <form id="form-datos" class="form_datos_perso" method='POST' onsubmit="return valida_form()">
        <div class="row">
            <div class="sep10"></div>

            <div class="form-group col-md-12 datetime">
                <div class="row">
                    <div class="col-md-12">
                        <label for="form_empresa">Distribuidor <span class="required" data-bs-toggle="tooltip" data-bs-placement="top" title="Campo obligatorio">*</span></label>
                        <input type="text" class="form-control" id="input_distribuidor" name="input_distribuidor" value="<?php echo (!empty($datos_descuento->valor)) ? $datos_descuento->valor : ''; ?>" >

                    </div>
                </div>
            </div>
            <div class="sep20"></div>

            <div class="btns">
                <input type="hidden" name="accion" value="añadir_distribuidor">
                <button id="aceptar" class="send cart_btn"><i class="fa fa-save"></i>Guardar</button>
            </div>
        </div>
    </form>
</div>
<style>
    select {
        -webkit-appearance: listbox !important
    }

    .form-control:focus {
        border-color: #ccc;
        box-shadow: none;
    }

    .form-modal input[type="text"],
    .form-modal select {
        margin-bottom: 20px;
    }
</style>

<script>
    $(function() {
        $('[data-bs-toggle="tooltip"]').tooltip()
    });

    $('#form-datos').submit(function(e) {
        e.preventDefault();
    });

    $('#aceptar').click(function(e) {
        console.log('YEE');
        $.ajax({
            url: './assets/lib/admin_control.php',
            type: 'post',
            dataType: 'text',
            data: $('#form-datos').serialize()
        })
        .done(function(result) {
            var result = $.parseJSON(result);
            if (result.res == 0) {
                muestraMensajeLn(result.msg)
            } else if (result.res == 1) {
                muestraMensajeLn(result.msg);
                setTimeout(function() {
                    location.reload();
                }, 2000);
            }
        })
        .fail(function() {
            alert("error");
        });
    });

</script>
