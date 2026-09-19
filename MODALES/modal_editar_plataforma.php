<div class="modal fade" id="modalEditar">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="../AJAX/plataforma.ajax.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="accion" value="editar">
                
                <div class="modal-header bg-warning">
                    <h4 class="modal-title">
                        <i class="fas fa-edit"></i> Editar Plataforma
                    </h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                
                <div class="modal-body">
                    <input type="hidden" id="editarId" name="id">
                    <input type="hidden" id="imagenActual" name="imagenActual">
                    
                    <div class="form-group">
                        <label>Categoría</label>
                        <select class="form-control" id="editarCategoria" name="categoria" required>
                            <option value="1">Streaming</option>
                            <option value="2">Música</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" class="form-control" id="editarNombre" name="nombre" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Descripción</label>
                        <textarea class="form-control" id="editarDescripcion" name="descripcion" rows="3"></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Precio</label>
                        <input type="number" step="0.01" class="form-control" id="editarPrecio" name="precio" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Stock</label>
                        <input type="number" class="form-control" id="editarStock" name="stock" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Imagen</label>
                        <div class="form-group">
                            <label>Imagen actual</label>
                            <br>
                            <img id="previewEditar" src="../IMG/productos/sin-imagen.png" style="width:120px;border-radius:10px;margin-bottom:10px;">
                            <input type="file" class="form-control" id="editarImagen" name="imagen" accept="image/*">
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save"></i> Actualizar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>