<!--<div class="modal fade" id="modal_registrar_factura" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-light">
                <h5 class="modal-title">ADICIÓN DE DATOS</h5>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x me-2"></i>CANCELAR</button>
                <button type="submit" class="btn btn-success"><i class="bi bi-check me-2"></i>REGISTRAR</button>
            </div>
        </div>
    </div>
</div>-->

<div class="modal fade" id="modal_registrar_factura" aria-hidden="true" aria-labelledby="modal_reg_factura" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="background-color: #ebebeb">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="modal_reg_factura">Registra factura</h1>
      </div>
      <div class="modal-body m-0 row justify-content-center">
        <div class="col-auto form-check form-switch">
          <label class="form-check-label" for="fact_nueva">Factura nueva</label>
          <input class="form-check-input" type="checkbox" role="switch" id="fact_nueva">
        </div>
        <div class="mb-3">
          <label for="fact_data" class="form-label"><i class="bi bi-qr-code"></i>&nbsp; Datos de la factura  </label>
          <input type="text" class="form-control" id="fact_data">
          <div id="help" class="form-text">Escanee el código QR </div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-target="#modal_registrar_comprobante" data-bs-toggle="modal"><i class="bi bi-x me-2"></i> Cancelar</button>
        <button class="btn btn-success" id="enviarFactura" data-bs-target="#modal_registrar_comprobante" data-bs-toggle="modal">Registrar Factura</button>
      </div>
    </div>
  </div>
</div>
