<form id="form_actualizar_cuenta">
    <div class="modal fade" id="modal_actualizar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">ACTUALIZAR CUENTA : <span id="id_cuenta"></span></h5>
                    <input type="hidden" name="id" value="" id="cuenta_id"/>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <div class="row">
                            <div class="form-outline">
                                <input type="text" name="codigo" class="form-control form-control-lg" aria-describedby="basic-addon1" minlength="1" maxlength="20" required autocomplete="off" id="cuenta_codigo"/>
                                <label class="form-label" for="codigo">Código</label>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="form-outline">
                                <input type="text" name="descripcion" class="form-control form-control-lg" minlength="3" maxlength="100" required autocomplete="off" id="cuenta_descripcion"/>
                                <label class="form-label" for="descripcion">Descripción</label>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <select class="form-control form-control-lg form-select text-muted" name="grupo" required id="cuenta_grupo">
                                <option value="" disabled selected> - Seleccione un grupo - </option>
                                <option value="ACTIVO">ACTIVO</option>
                                <option value="PASIVO">PASIVO</option>
                                <option value="PATRIMONIO">PATRIMONIO</option>
                                <option value="INGRESOS">INGRESOS</option>
                                <option value="COSTOS">COSTOS</option>
                                <option value="GASTOS">GASTOS</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-mdb-dismiss="modal"><i class="fas fa-xmark me-2"></i>CANCELAR</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-arrows-rotate me-2"></i>ACTUALIZAR</button>
                </div>
            </div>
        </div>
    </div>
</form>