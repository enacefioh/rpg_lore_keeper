<?php
// Este archivo se incluye en personajes.php y personaje_editar.php
// Requiere que $gallery esté definido (enarol_pjs_get_gallery_images())

$all_tags = [];
foreach($gallery as $img) {
    if(isset($img['tags'])) $all_tags = array_merge($all_tags, $img['tags']);
}
$all_tags = array_unique($all_tags);
sort($all_tags);
?>

<!-- Estructura del Modal -->
<div id="galleryModal" class="modal-overlay" style="display:none;">
    <div class="modal-content card">
        <div class="modal-header">
            <h3>Seleccionar Retrato</h3>
            <span class="close-modal" onclick="closeGallery()">&times;</span>
        </div>
        
        <div class="modal-body">
            <!-- Pestañas o Secciones -->
            <div class="gallery-tabs">
                <button class="tab-btn active" onclick="switchTab('library')">Librería</button>
                <button class="tab-btn" onclick="switchTab('upload')">Subir propia imagen</button>
            </div>

            <!-- Sección Librería -->
            <div id="tab-library" class="tab-content active">
                <div class="filter-bar">
                    <input type="text" id="modalSearchTags" placeholder="Buscar tags..." oninput="filterModalGallery()">
                    <select id="modalFilterTag" onchange="filterModalGallery()">
                        <option value="">Todos los tags</option>
                        <?php foreach($all_tags as $tag): ?>
                            <option value="<?php echo htmlspecialchars($tag); ?>"><?php echo htmlspecialchars(ucfirst($tag)); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div id="modalGalleryGrid" class="image-grid">
                    <?php foreach($gallery as $img): ?>
                        <div class="modal-gallery-item" 
                             data-tags="<?php echo htmlspecialchars(implode(',', $img['tags'])); ?>"
                             onclick="selectFromGallery('<?php echo $img['filename']; ?>', 'res/pjs_img_library/')">
                            <img src="../res/pjs_img_library/<?php echo $img['filename']; ?>" loading="lazy">
                            <div class="tags-preview">
                                <?php foreach(array_slice($img['tags'], 0, 2) as $t) echo "<span class='mini-tag'>$t</span>"; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Sección Upload -->
            <div id="tab-upload" class="tab-content" style="display:none;">
                <div class="upload-zone" id="dropZone" onclick="document.getElementById('modalFileInput').click()">
                    <p>Arrastra una imagen aquí o haz clic para buscar</p>
                    <input type="file" id="modalFileInput" style="display:none" accept="image/*" onchange="handleModalUpload(this)">
                </div>
                <div id="uploadStatus" style="margin-top:10px; text-align:center;"></div>
            </div>
        </div>
    </div>
</div>

<style>
.modal-overlay {
    position: fixed; top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(0,0,0,0.8); z-index: 1000;
    display: flex; align-items: center; justify-content: center;
}
.modal-content {
    width: 90%; max-width: 1000px; max-height: 90vh;
    overflow-y: auto; background: #fff; position: relative;
    padding: 20px !important;
}
.modal-header { display: flex; justify-content: space-between; border-bottom: 1px solid #eee; margin-bottom: 15px; }
.close-modal { cursor: pointer; font-size: 2rem; }
.gallery-tabs { display: flex; gap: 10px; margin-bottom: 15px; }
.tab-btn { padding: 10px 20px; border: none; background: #eee; cursor: pointer; border-radius: 4px; }
.tab-btn.active { background: var(--accent-gold); color: black; }
.filter-bar { display: flex; gap: 10px; margin-bottom: 15px; width: 100%; align-items: center; }
.filter-bar input#modalSearchTags { flex-grow: 1; padding: 10px; min-width: 0; }
.filter-bar select#modalFilterTag { width: auto; padding: 10px; flex-shrink: 0; }
.image-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(130px, 1fr)); gap: 10px; }
.modal-gallery-item { cursor: pointer; transition: 0.2s; position: relative; border-radius: 4px; overflow: hidden; height: 130px; }
.modal-gallery-item:hover { transform: scale(1.05); box-shadow: 0 0 10px var(--accent-gold); }
.modal-gallery-item img { width: 100%; height: 100%; object-fit: cover; }
.tags-preview { position: absolute; bottom: 0; left: 0; right: 0; background: rgba(0,0,0,0.5); display: flex; gap: 2px; padding: 2px; }
.mini-tag { font-size: 0.5rem; color: white; background: rgba(255,255,255,0.2); padding: 1px 3px; border-radius: 2px; }
.upload-zone { border: 2px dashed #ccc; padding: 40px; text-align: center; cursor: pointer; border-radius: 8px; }
.upload-zone:hover { border-color: var(--accent-gold); background: rgba(255,215,0,0.05); }
</style>

<script>
function openGallery() { document.getElementById('galleryModal').style.display = 'flex'; }
function closeGallery() { document.getElementById('galleryModal').style.display = 'none'; }

function switchTab(tab) {
    document.querySelectorAll('.tab-content').forEach(c => c.style.display = 'none');
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('tab-' + tab).style.display = 'block';
    event.target.classList.add('active');
}

function filterModalGallery() {
    const search = document.getElementById('modalSearchTags').value.toLowerCase();
    const filter = document.getElementById('modalFilterTag').value.toLowerCase();
    const items = document.querySelectorAll('.modal-gallery-item');
    
    items.forEach(item => {
        const tags = item.dataset.tags.toLowerCase();
        const matchesSearch = tags.includes(search);
        const matchesFilter = filter === "" || tags.split(',').includes(filter);
        item.style.display = (matchesSearch && matchesFilter) ? 'block' : 'none';
    });
}

function selectFromGallery(filename, pathPrefix) {
    document.getElementById('pj_img_input').value = filename;
    document.getElementById('pj_img_type').value = (pathPrefix.includes('library')) ? 'library' : 'upload';
    document.getElementById('pj_preview').src = "../" + pathPrefix + filename;
    closeGallery();
}

async function handleModalUpload(input) {
    if (!input.files || !input.files[0]) return;
    
    const status = document.getElementById('uploadStatus');
    status.innerHTML = "Subiendo y procesando...";
    
    const formData = new FormData();
    formData.append('img', input.files[0]);
    formData.append('nombre', document.querySelector('input[name="nombre"]').value || 'Temp');

    try {
        const resp = await fetch('controller/upload_ajax.php', { method: 'POST', body: formData });
        const data = await resp.json();
        
        if (data.success) {
            status.innerHTML = "<span style='color:green'>¡Subida con éxito!</span>";
            selectFromGallery(data.filename, 'uploads/');
        } else {
            status.innerHTML = "<span style='color:red'>" + (data.error || "Error al subir") + "</span>";
        }
    } catch (e) {
        status.innerHTML = "<span style='color:red'>Error de conexión</span>";
    }
}
</script>
