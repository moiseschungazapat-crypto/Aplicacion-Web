<div class="modal fade" id="modalNuevaPlataforma">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="../AJAX/plataforma.ajax.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="accion" value="crear">
                
                <div class="modal-header bg-success text-white">
                    <h4 class="modal-title">Nueva Plataforma</h4>
                    <button type="button" class="close text-white" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label>Nombre</label>
                            <input type="text" name="nombre" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label>Categoría</label>
                            <select name="categoria" class="form-control" required>
                                <option value="1">Streaming</option>
                                <option value="2">Música</option>
                            </select>
                        </div>
                    </div>

                    <br>

                    <label>Descripción</label>
                    <textarea name="descripcion" class="form-control" rows="3" required></textarea>

                    <br>

                    <div class="row">
                        <div class="col-md-4">
                            <label>Precio</label>
                            <input type="number" step="0.01" name="precio" class="form-control" required>
                        </div>

                        <div class="col-md-4">
                            <label>Stock</label>
                            <input type="number" name="stock" class="form-control" value="1" required>
                        </div>

                        <div class="col-md-4">
                            <label>Imagen</label>
                            <input type="file" class="form-control" name="imagen" id="imagenNueva" accept="image/*" required>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Guardar Plataforma</button>
                </div>
            </form>
        </div>
    </div>
</div>