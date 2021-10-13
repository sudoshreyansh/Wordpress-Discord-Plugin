document.querySelector('#dlmc-cron-actions').addEventListener('click', function(event) {
    event.preventDefault();
    document.getElementById('dlmc-cron-toggle').submit();
});

document.querySelector('#dlmc-embed-size').addEventListener('input', dlmcUpdateEmbedPreviewSize);
document.querySelector('#dlmc-embed-color').addEventListener('input', dlmcUpdateEmbedPreviewColor);
document.querySelector('#dlmc-embed-weight').addEventListener('input', dlmcUpdateEmbedPreviewWeight);
let dlmcEmbedPreview = document.querySelector('#dlmc-embed-preview span');
function dlmcUpdateEmbedPreviewSize(event) {
    dlmcEmbedPreview.style.fontSize = event.target.value + 'px';
    dlmcUpdateEmbedShortcode();
}

function dlmcUpdateEmbedPreviewColor(event) {
    dlmcEmbedPreview.style.color = event.target.value;
    dlmcUpdateEmbedShortcode();
}

function dlmcUpdateEmbedPreviewWeight(event) {
    dlmcEmbedPreview.style.fontWeight = event.target.value;
    dlmcUpdateEmbedShortcode();
}

function dlmcUpdateEmbedShortcode() {
    let embedColor = document.querySelector('#dlmc-embed-color').value;
    let embedSize = dlmcEmbedPreview.style.fontSize;
    let embedWeight = dlmcEmbedPreview.style.fontWeight;
    let embedShortcode = '[dlmc_counter color="' + embedColor + '" size="' + embedSize +  '" weight="' + embedWeight + '"]';
    document.querySelector('#dlmc-embed-shortcode').value = embedShortcode;
}