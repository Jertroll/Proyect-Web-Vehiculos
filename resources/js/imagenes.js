// Preview de imagen al ingresar la URL
// Este archivo debe estar en resources/js/imagenes.js
document.addEventListener('DOMContentLoaded', function () {
    const urlInput       = document.getElementById('url_imagen');
    const previewContainer = document.getElementById('preview-container');
    const previewImg     = document.getElementById('preview-img');

    if (urlInput) {
        urlInput.addEventListener('input', function () {
            const url = this.value.trim();

            if (url !== '') {
                previewImg.src = url;
                previewContainer.style.display = 'block';

                previewImg.onerror = function () {
                    previewContainer.style.display = 'none';
                };
            } else {
                previewContainer.style.display = 'none';
            }
        });
    }
});