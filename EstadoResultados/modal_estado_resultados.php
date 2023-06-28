<form id="form_estado_resultados">
    <div class="modal fade" id="modal_estado_resultados" tabindex="-1" aria-labelledby="modal_titulo_er" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success text-light">
                    <h5 class="modal-title" id="modal_titulo_er">ESTADO DE RESULTADOS</h5>
                </div>
                <div class="modal-body" tabindex="-1">
                    <div class="container">
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="er_fecha_final" class="form-label text-muted"><b>Al mes de:</b></label>
                                    <input type="month" name="er_fecha_final" id="er_fecha_final" class="form-control form-control-sm" min="2023-01" max="2030-12" required autocomplete="off"/>
                                </div>
                            </div>
                            <!--<div class="col-md-6">
                                <label for="" class="form-label text-muted"><b>Por defecto:</b></label>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="er_formato" name="er_formato" checked disabled>
                                    <label class="form-check-label" for="er_formato">
                                        Ingresos - Egresos
                                    </label>
                                </div>
                            </div>-->
                        </div>
                        <div class="row align-items-top">
                            <div class="col-md-6">
                                <label for="" class="form-label text-muted"><b>Nivel de Cuenta:</b></label>
                                <div class="card p-2">
                                    <!--<div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="er_nivel" id="er_grupo" value="G" disabled>
                                        <label class="form-check-label" for="er_grupo">Grupo</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="er_nivel" id="er_rubro" value="R" disabled>
                                        <label class="form-check-label" for="er_rubro">Rubro</label>
                                    </div>-->
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="er_nivel" id="er_titulo" value="T" checked>
                                        <label class="form-check-label" for="er_titulo">Título</label>
                                    </div>
                                    <!--<div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="er_nivel" id="er_compuesta" value="C" disabled>
                                        <label class="form-check-label" for="er_compuesta">Compuesta</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="er_nivel" id="er_subcuenta" value="S" disabled>
                                        <label class="form-check-label" for="er_subcuenta">Sub Cuenta</label>
                                    </div>-->
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="" class="form-label text-muted"><b>Moneda:</b></label>
                                <div class="card p-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="er_moneda" id="er_bs" value="BOLIVIANOS" checked>
                                        <label class="form-check-label" for="er_bs">Bolivianos</label>
                                    </div>
                                    <!--<div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="er_moneda" id="er_sus" value="DOLARES" disabled>
                                        <label class="form-check-label" for="er_sus">Dólares</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="er_moneda" id="er_ufv" value="UFV" disabled>
                                        <label class="form-check-label" for="er_ufv">UFV</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="er_moneda" id="er_bm" value="BIMONETARIO" disabled>
                                        <label class="form-check-label" for="er_bm">Bimonetario</label>
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