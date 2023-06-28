<form id="form_balance_general">
    <div class="modal fade" id="modal_balance_general" tabindex="-1" aria-labelledby="modal_titulo_bg" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-light">
                    <h5 class="modal-title" id="modal_titulo_bg">BALANCE GENERAL</h5>
                </div>
                <div class="modal-body" tabindex="-1">
                    <div class="container">
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="bg_fecha_final" class="form-label text-muted"><b>Al mes de:</b></label>
                                    <input type="month" name="bg_fecha_final" id="bg_fecha_final" class="form-control form-control-sm" min="2023-01" max="2030-12" required autocomplete="off"/>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-2 align-items-top">
                            <div class="col-md-6">
                                <label for="" class="form-label text-muted"><b>Nivel de Cuenta:</b></label>
                                <div class="card p-2">
                                    <!--<div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="bg_nivel" id="bg_grupo" value="G" disabled>
                                        <label class="form-check-label" for="bg_grupo">Grupo</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="bg_nivel" id="bg_rubro" value="R" disabled>
                                        <label class="form-check-label" for="bg_rubro">Rubro</label>
                                    </div>-->
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="bg_nivel" id="bg_titulo" value="T" checked>
                                        <label class="form-check-label" for="bg_titulo">Título</label>
                                    </div>
                                    <!--<div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="bg_nivel" id="bg_compuesta" value="C" disabled>
                                        <label class="form-check-label" for="bg_compuesta">Compuesta</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="bg_nivel" id="bg_subcuenta" value="S" disabled>
                                        <label class="form-check-label" for="bg_subcuenta">Sub Cuenta</label>
                                    </div>-->
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="" class="form-label text-muted"><b>Moneda:</b></label>
                                <div class="card p-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="bg_moneda" id="bg_bs" value="BOLIVIANOS" checked>
                                        <label class="form-check-label" for="bg_bs">Bolivianos</label>
                                    </div>
                                    <!--<div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="bg_moneda" id="bg_sus" value="DOLARES" disabled>
                                        <label class="form-check-label" for="bg_sus">Dólares</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="bg_moneda" id="bg_ufv" value="UFV" disabled>
                                        <label class="form-check-label" for="bg_ufv">UFV</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="bg_moneda" id="bg_bm" value="BIMONETARIO" disabled>
                                        <label class="form-check-label" for="bg_bm">Bimonetario</label>
                                    </div>-->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="bi bi-x me-2"></i>CANCELAR</button>
                    <button type="submit" class="btn btn-success"><i class="bi bi-table me-2"></i>GENERAR</button>
                </div>
            </div>
        </div>
    </div>
</form>