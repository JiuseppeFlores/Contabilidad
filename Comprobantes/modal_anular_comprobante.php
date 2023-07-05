<div class="modal fade" id="modal_anular_comprobante" tabindex="-1" aria-labelledby="Modal anular" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5">Anular Comprobante</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" id="id_comprobante">
        ¿Está seguro de anular el comprobante?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button type="button" data-bs-dismiss="modal" onclick="anularComprobante()" class="btn btn-danger">Sí, continuar</button>
      </div>
    </div>
  </div>
</div>