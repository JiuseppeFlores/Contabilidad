<form id="form_eliminar_cuenta">
    <div class="modal fade" id="modal_eliminar" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">ELIMINAR CUENTA</h5></h5>
                    <input type="hidden" name="id" value="" id="eliminar_id"/>
                </div>
                <div class="modal-body">
                    <div class="container text-center">
                        <p>¿Esta seguro que desea eliminar la siguiente cuenta?</p>
                        <b><p class="text-danger" id="eliminar_descripcion"></p></b>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-mdb-dismiss="modal"><i class="bi bi-x me-2"></i>CANCELAR</button>
                    <button type="submit" class="btn btn-danger"><i class="bi bi-trash me-2"></i>ELIMINAR</button>
                </div>
            </div>
        </div>
    </div>
</form>