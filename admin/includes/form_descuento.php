<?php
include_once('../../assets/lib/bbdd.php');
include_once('../assets/lib/funciones_admin.php');

$id_descuento = isset($_REQUEST['id_descuento']) ? $_REQUEST['id_descuento'] : 0;
$datos_descuento = null;
if ($id_descuento != 0) {
    $datos_descuento = obten_descuento($id_descuento);
    $accion = 'actualiza_descuento';
    $tit_form = 'Modificar cupón descuento ' . ($datos_descuento ? $datos_descuento->nombre_descuento : '');
} else {
    $accion = 'nuevo_descuento';
    $tit_form = 'Nuevo cupón descuento';
};
// echo $_SESSION['distribuidor'];
?>
<button type="button" class="close" data-bs-dismiss="modal">&times;</button>
<p class="modal-tit"><?php echo $tit_form; ?></p>

<div class="row form-modal">
    <form id="form-datos" class="form_datos_perso" method='POST' onsubmit="return valida_form()">
        <div class="row">
            <div class="sep10"></div>
            <div class="form-group col-md-12 datetime">
                <div class="row">
                    <div class="col-md-6">
                        <label for="form_empresa">Nombre descuento <span class="required" data-bs-toggle="tooltip" data-bs-placement="top" title="Campo obligatorio">*</span></label>
                        <input type="text" class="form-control" id="input_nombre" name="input_nombre" value="<?php echo (!empty($datos_descuento->nombre_descuento)) ? $datos_descuento->nombre_descuento : ''; ?>" >
                    </div>
                    <div class="col-md-6">
                        <label for="form_empresa">Estado <span class="required" data-bs-toggle="tooltip" data-bs-placement="top" title="Campo obligatorio">*</span></label>
                        <select type="text" class="form-control" id="input_estado_descuento" name="input_estado_descuento">
                            <option value="1" <?php if (isset($datos_descuento) && $datos_descuento->activo == 1) echo 'selected'; ?>>Activo</option>
                            <option value="0" <?php if (isset($datos_descuento) && $datos_descuento->activo == 0) echo 'selected'; ?>>Inactivo</option>
                        </select>
                    </div>
                </div>
            </div>
            <?php if ($_SESSION['distribuidor'] == 1) { ?>
            <div class="form-group col-md-12 datetime">
                <div class="row">
                    <div class="col-md-12">
                        <label for="form_empresa">Distribuidor <span class="required" data-bs-toggle="tooltip" data-bs-placement="top" title="Campo obligatorio">*</span></label>
                        <select type="text" class="form-control" id="input_distribuidor" name="input_distribuidor">
                            <?php
                            $sql = "SELECT * FROM users WHERE distribuidor=1 ORDER BY uid ASC";
                            $res = consulta($sql, $conn);
                            while ($reg = $res->fetch_object()) { ?>
                                <option value="<?php echo $reg->uid ?>"><?php echo $reg->email ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
            <?php } ?>

            <div class="form-group col-md-12 datetime">
                <div class="row">
                    <div class="col-md-3">
                        <label for="form_empresa">Valor <span class="required" data-bs-toggle="tooltip" data-bs-placement="top" title="Campo obligatorio">*</span></label>
                        <input type="text" class="form-control" id="input_valor" name="input_valor" value="<?php echo (!empty($datos_descuento->valor)) ? $datos_descuento->valor : ''; ?>" >
                    </div>
                    <div class="col-md-3">
                        <label for="form_empresa">
                            <i class="fa fa-info-circle form_info"
                               data-bs-toggle="tooltip"
                               data-bs-placement="top"
                               title="El descuento puede ser un porcentaje ( % ) o una cantidad única ( € )">
                            </i>
                            Tipo <span class="required" data-bs-toggle="tooltip" data-bs-placement="top" title="Campo obligatorio">*</span></label>
                        <select type="text" class="form-control" id="input_tipo" name="input_tipo">
                            <option value="-">Seleccionar</option>
                            <option value="%" <?php if (isset($datos_descuento) && $datos_descuento->tipo == '%') echo 'selected'; ?>>%</option>
                            <option value="€" <?php if (isset($datos_descuento) && $datos_descuento->tipo == '€') echo 'selected'; ?>>€</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="form_empresa">
                            <i class="fa fa-info-circle form_info"
                               data-bs-toggle="tooltip"
                               data-bs-placement="top"
                               title="Hace referencia a las veces que un mismo usuario puede utilizar este descuento">
                            </i>
                            Max uso/cliente <span class="required" data-bs-toggle="tooltip" data-bs-placement="top" title="Campo obligatorio">*</span></label>
                        <input type="number" class="form-control" id="input_uso_persona" name="input_uso_persona" value="<?php echo (!empty($datos_descuento->uso_persona)) ? $datos_descuento->uso_persona : '1'; ?>" >
                    </div>
                </div>
            </div>

            <div class="form-group col-md-12 datetime">
                <div class="row">
                    <div class="col-md-6">
                        <label for="form_empresa">Fecha inicio <span class="required" data-bs-toggle="tooltip" data-bs-placement="top" title="Campo obligatorio">*</span></label>
                        <input type="text" class="form-control" id="input_fecha_ini" name="input_fecha_ini" value="<?php echo (!empty($datos_descuento->fecha_inicio)) ? obten_fecha($datos_descuento->fecha_inicio) : date('d/m/Y'); ?>" >
                    </div>
                    <div class="col-md-6">
                        <label for="form_empresa">Hora inicio <span class="required" data-bs-toggle="tooltip" data-bs-placement="top" title="Campo obligatorio">*</span></label>
                        <input type="text" class="form-control" id="input_hora_ini" name="input_hora_ini" value="<?php echo (!empty($datos_descuento->fecha_inicio)) ? obten_hora($datos_descuento->fecha_inicio) : '00:00:00'; ?>" >
                    </div>
                </div>
            </div>
            <div class="form-group col-md-12 datetime">
                <div class="row">
                    <div class="col-md-6">
                        <label for="form_empresa">Fecha fin <span class="required" data-bs-toggle="tooltip" data-bs-placement="top" title="Campo obligatorio">*</span></label>
                        <input type="text" class="form-control" id="input_fecha_fin" name="input_fecha_fin" value="<?php echo (!empty($datos_descuento->fecha_fin)) ? obten_fecha($datos_descuento->fecha_fin) : ''; ?>" >
                    </div>
                    <div class="col-md-6">
                        <label for="form_empresa">Hora fin <span class="required" data-bs-toggle="tooltip" data-bs-placement="top" title="Campo obligatorio">*</span></label>
                        <input type="text" class="form-control" id="input_hora_fin" name="input_hora_fin" value="<?php echo (!empty($datos_descuento->fecha_fin)) ? obten_hora($datos_descuento->fecha_fin) : '00:00:00'; ?>" >
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <label for="form_empresa">Descuento aplicado a:</label>
                        <select type="text" class="form-control" id="input_aplicacion_descuento" name="input_aplicacion_descuento">
                            <?php
                            $sql = "SELECT * FROM aplicacion_descuento ORDER BY id ASC";
                            $res = consulta($sql, $conn);
                            while ($reg = $res->fetch_object()) { ?>
                                <option value="<?php echo $reg->id ?>" <?php if ($reg->id == $datos_descuento->aplicacion_descuento) echo "selected" ?>><?php echo $reg->aplicacion_texto ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <div class="row">
                    <div class="col-md-12">
                        <?php
                        if ($datos_descuento && $datos_descuento->pais_aplicacion == 1) {
                            $pais_aplicacion = 'ES | EN | FR | IT | DE | EN-US';
                        } else {
                            $pais_aplicacion = $datos_descuento ? $datos_descuento->pais_aplicacion : '';
                        }
                        ?>
                        <label for="form_empresa">Aplicado en idiomas:</label>
                        <input type="text" class="form-control" value="<?php echo $pais_aplicacion ?>" readonly>
                    </div>
                </div>
            </div>

            <div class="sep10"></div>

            <div class="form-group col-md-12">
                <label for="form_empresa">Descripción / Comentarios</label>
                <textarea rows="4" class="form-control" id="text_coment" name="text_coment"><?php echo (!empty($datos_descuento->comentario)) ? $datos_descuento->comentario : ''; ?></textarea>
            </div>

            <div class="sep20"></div>

            <div class="btns">
                <input type="hidden" name="id_descuento" value="<?php echo $id_descuento ?>">
                <input type="hidden" name="accion" value="<?php echo $accion ?>">
                <button id="aceptar" class="send cart_btn"><i class="fa fa-save"></i>Guardar</button>
                <button class="delete"
                        data-bs-toggle="tooltip"
                        data-bs-placement="top"
                        title="Eliminar descuento '<?php echo $datos_descuento ? $datos_descuento->nombre_descuento : '' ?>'"
                        onclick="if (confirm('Si aceptas se eliminará el descuento <?php echo $datos_descuento ? $datos_descuento->nombre_descuento : '' ?>. ¿Estas seguro?') == true ) { eliminaDescuento(<?php echo $datos_descuento ? $datos_descuento->id : 0 ?>) }">
                    <i class="fa fa-trash"></i>
                </button>
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
        if (valida_form()) {
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
        }
    });

    $.fn.datepicker.dates['es'] = {
        days: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"],
        daysShort: ["Dom", "Lun", "Mar", "Mié", "Jue", "Vie", "Sáb"],
        daysMin: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sá"],
        months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
        monthsShort: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
        today: "Hoy",
        clear: "Borrar",
        format: "dd/mm/yyyy",
        titleFormat: "MM yyyy",
        weekStart: 1
    };

    $(function() {
        $('.input_fecha, #input_fecha_ini, #input_fecha_fin').datepicker({
            orientation: 'bottom',
            language: 'es'
        });
    });

    $('.input_hora, #input_hora_ini, #input_hora_fin').timepicker({
        minuteStep: 1,
        secondStep: 1,
        useCurrent: false,
        format: 'DD/MM/YYYY HH:mm:ss',
        showMeridian: false,
        showSeconds: true,
    })

    function valida_form() {
        if ($('#input_nombre').val() == '') {
            muestraMensajeLn('Es necesario un nombre de descuento');
            return false;
        } else if ($('#input_valor').val() == '') {
            muestraMensajeLn('Es necesario introducir un valor para el descuento');
            return false;
        } else if ($('#input_tipo').val() == '-') {
            muestraMensajeLn('Es necesario seleccionar un tipo de descuento (% porcentaje o € cantidad fija)');
            return false;
        } else if ($('#input_fecha_ini').val() == '' || $('#input_fecha_fin').val() == '') {
            muestraMensajeLn('Es necesario introducir Fecha inicio y Fecha fin');
            return false;
        } else if ($('#input_hora_ini').val() == '' || $('#input_hora_fin').val() == '') {
            muestraMensajeLn('Es necesario introducir Hora inicio y Hora fin');
            return false;
        } else if ($('#input_fecha_ini').val() != '' || $('#input_fecha_fin').val() != '') {
            var d1 = $('#input_fecha_ini').val();
            var parts = d1.split('/');
            d1 = Number(parts[2] + parts[1] + parts[0]);
            var d2 = $('#input_fecha_fin').val();
            parts = d2.split('/');
            d2 = Number(parts[2] + parts[1] + parts[0]);

            if (d1 > d2) {
                muestraMensajeLn('Fecha fin debe ser posterior a Fecha inicio');
                return false;
            } else {
                return true;
            }
        } else {
            return true;
        }
    }
</script>
