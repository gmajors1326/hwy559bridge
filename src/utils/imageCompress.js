const MAX_DIM = 1600;
const MAX_FILE_BYTES = 1_500_000;

const RASTER = new Set(['image/jpeg', 'image/png', 'image/webp', 'image/bmp', 'image/tiff', 'image/heic', 'image/heif']);

function isRaster(file) {
  return file && RASTER.has(file.type) && !/\.(gif|svg|svgz)$/i.test(file.name);
}

function loadImage(blob) {
  return new Promise((resolve, reject) => {
    const url = URL.createObjectURL(blob);
    const img = new Image();
    img.onload = () => { URL.revokeObjectURL(url); resolve(img); };
    img.onerror = () => { URL.revokeObjectURL(url); reject(new Error('Failed to decode image')); };
    img.src = url;
  });
}

function blobToFile(blob, name, type) {
  return new File([blob], name, { type: type || blob.type, lastModified: Date.now() });
}

export async function compressImage(file, { maxDim = MAX_DIM, maxBytes = MAX_FILE_BYTES } = {}) {
  if (!isRaster(file)) return file;
  if (file.size <= maxBytes) return file;

  const img = await loadImage(file);
  const scale = Math.min(1, maxDim / Math.max(img.naturalWidth, img.naturalHeight));
  const w = Math.max(1, Math.round(img.naturalWidth * scale));
  const h = Math.max(1, Math.round(img.naturalHeight * scale));

  const canvas = document.createElement('canvas');
  canvas.width = w;
  canvas.height = h;
  const ctx = canvas.getContext('2d');

  const isPng = file.type === 'image/png';
  const fill = isPng ? '#ffffff' : '#fff';
  ctx.fillStyle = fill;
  ctx.fillRect(0, 0, w, h);
  ctx.imageSmoothingEnabled = true;
  ctx.imageSmoothingQuality = 'high';
  ctx.drawImage(img, 0, 0, w, h);

  const baseName = file.name.replace(/\.(jpe?g|png|webp|bmp|tiff?|heic|heif)$/i, '');
  let quality = 0.82;
  let mime = 'image/jpeg';
  let out = await canvasToBlob(canvas, mime, quality);

  if (out.size > maxBytes && quality > 0.5) {
    out = await canvasToBlob(canvas, mime, 0.6);
  }

  return blobToFile(out, `${baseName}.jpg`, mime);
}

function canvasToBlob(canvas, type, quality) {
  return new Promise((resolve, reject) => {
    canvas.toBlob(b => (b ? resolve(b) : reject(new Error('Canvas export failed'))), type, quality);
  });
}