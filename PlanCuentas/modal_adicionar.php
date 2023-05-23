<form id="form_adicionar_cuenta">
    <div class="modal fade" id="modal_adicionar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">ADICIONAR CUENTA</h5>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <div class="alert alert-danger alert-dismissible fade hide" role="alert" id="alerta">
                                <strong id="titulo_mensaje"></strong> <label id="cuerpo_mensaje"></label>
                                <button type="button" class="btn-close" data-mdb-dismiss="alert" aria-label="Close"></button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-outline">
                                <input type="text" name="codigo" class="form-control form-control-lg" aria-describedby="basic-addon1" minlength="1" maxlength="20" required autocomplete="off" id="cuenta_codigo"/>
                                <label class="form-label" for="codigo">Código</label>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="form-outline">
                                <input type="text" name="nombrse" class="form-control form-control-lg" minlength="3" maxlength="100" required autocomplete="off" id="cuenta_nombre"/>
                                <label class="form-label" for="nombre">Cuenta</label>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <select class="form-control form-control-lg form-select text-muted" name="grupo" required id="cuenta_grupo">
                                <option value="" disabled selected> - Seleccione un grupo - </option>
                                <option value="1">ACTIVO</option>
                                <option value="2">PASIVO</option>
                                <option value="3">PATRIMONIO</option>
                                <option value="4">INGRESOS</option>
                                <option value="5">COSTOS</option>
                                <option value="6">GASTOS</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-mdb-dismiss="modal"><i class="fas fa-xmark me-2"></i>CANCELAR</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-plus me-2"></i>ADICIONAR</button>
                </div>
            </div>
        </div>
    </div>
</form>