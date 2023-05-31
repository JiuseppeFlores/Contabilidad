<form id="form_actualizar_cuenta">
    <div class="modal fade" id="modal_actualizar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">ACTUALIZAR CUENTA : <span id="id_cuenta"></span></h5>
                    <input type="hidden" name="id" value="" id="cuenta_editar_id"/>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <input type="text" name="codigo" id="cuenta_editar_codigo" class="form-control" aria-describedby="basic-addon1" minlength="1" maxlength="20" required autocomplete="off"/>
                                    <label for="cuenta_editar_codigo">Código</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <input type="text" id="cuenta_editar_descripcion" name="descripcion" class="form-control" minlength="3" maxlength="100" required autocomplete="off"/>
                                    <label for="cuenta_editar_descripcion">Descripción</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating mb-3">
                                    <select class="form-select" id="cuenta_editar_grupo" name="grupo" required>
                                        <option value="" disabled selected> - Seleccione un grupo - </option>
                                        <option value="ACTIVO">ACTIVO</option>
                                        <option value="PASIVO">PASIVO</option>
                                        <option value="PATRIMONIO">PATRIMONIO</option>
                                        <option value="INGRESOS">INGRESOS</option>
                                        <option value="COSTOS">COSTOS</option>
                                        <option value="GASTOS">GASTOS</option>
                                    </select>
                                    <label for="cuenta_editar_grupo">Grupo</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x me-2"></i>CANCELAR</button>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-arrow-clockwise me-2"></i>ACTUALIZAR</button>
                </div>
            </div>
        </div>
    </div>
</form>