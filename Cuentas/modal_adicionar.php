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
                            <div class="mb-3">
                                <label class="form-label" for="codigo">Código</label>
                                <input type="text" name="codigo" class="form-control" minlength="1" maxlength="20" required autocomplete="off"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3">
                                <label class="form-label" for="descripcion">Descripción</label>
                                <input type="text" name="descripcion" class="form-control" minlength="3" maxlength="100" required autocomplete="off"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mb-3">
                                <label class="form-label" for="grupo">Descripción</label>
                                <select class="form-select" name="grupo" required>
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x me-2"></i>CANCELAR</button>
                    <button type="submit" class="btn btn-success"><i class="bi bi-plus me-2"></i>ADICIONAR</button>
                </div>
            </div>
        </div>
    </div>
</form>