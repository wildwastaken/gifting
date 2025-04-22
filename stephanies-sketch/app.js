// ——— DOM look‑ups ———
const fileInput = document.getElementById('fileInput');
const addBtn    = document.getElementById('addBtn');
const exportBtn = document.getElementById('exportBtn');
const board     = document.getElementById('board');

const cropModal = document.getElementById('cropModal');
const cropImg   = document.getElementById('cropImg');
const cropOk    = document.getElementById('cropOk');

let cropper     = null;   // Cropper.js instance
let dragTarget  = null;   // current .note being dragged
let offsetX = 0, offsetY = 0;

// ——— Helpers ———
const randomStickyHue = () => {
  const h = 45 + Math.random() * 30;          // 45–75 = pleasant yellows
  return `hsl(${h}, 100%, 92%)`;
};

function saveLayout() {
  const layout = [...document.querySelectorAll('.note')].map(n => ({
    src: n.dataset.src,
    x  : n.style.left,
    y  : n.style.top,
    r  : n.style.getPropertyValue('--r')
  }));
  localStorage.setItem('sticky-layout', JSON.stringify(layout));
}

function loadLayout() {
  const data = localStorage.getItem('sticky-layout');
  if (!data) return;
  JSON.parse(data).forEach(n => createNote(n.src, n.x, n.y, n.r, true));
}

function createNote(imgDataURL, x = '50%', y = '50%', rot = null, skipSave = false) {
  const note = document.createElement('div');
  note.className = 'note';
  note.style.backgroundImage = `url(${imgDataURL})`;
  note.style.left = x;
  note.style.top  = y;

  const r = rot ?? (Math.random() * 10 - 5).toFixed(1);   // −5 ° → +5 °
  note.style.setProperty('--r', r);
  note.style.transform = `rotate(${r}deg)`;
  note.style.backgroundColor = randomStickyHue();
  note.dataset.src = imgDataURL;

  board.appendChild(note);
  note.addEventListener('pointerdown', dragStart);
  if (!skipSave) saveLayout();
}

// ——— Dragging logic ———
function dragStart(e) {
  dragTarget = e.currentTarget;
  offsetX = e.clientX - dragTarget.offsetLeft;
  offsetY = e.clientY - dragTarget.offsetTop;

  window.addEventListener('pointermove', dragMove);
  window.addEventListener('pointerup', dragEnd, { once: true });
}

function dragMove(e) {
  if (!dragTarget) return;
  dragTarget.style.left = `${e.clientX - offsetX}px`;
  dragTarget.style.top  = `${e.clientY - offsetY}px`;
}

function dragEnd() {
  saveLayout();
  window.removeEventListener('pointermove', dragMove);
  dragTarget = null;
}

// ——— Crop workflow ———
function openCropper(file) {
  const url = URL.createObjectURL(file);
  cropImg.src = url;
  cropModal.classList.remove('hidden');

  cropper = new Cropper(cropImg, {
    viewMode   : 1,
    dragMode   : 'crop',
    autoCropArea: 1,
    responsive : true,
    background : false
  });
}

cropOk.addEventListener('click', () => {
  const canvas = cropper.getCroppedCanvas({
    width : 180,
    height: 180,
    imageSmoothingQuality: 'high'
  });
  const dataURL = canvas.toDataURL('image/png');
  cropper.destroy();
  cropModal.classList.add('hidden');
  createNote(dataURL);
});

// ——— UI bindings ———
addBtn.addEventListener('click', () => fileInput.click());

fileInput.addEventListener('change', e => {
  const [first] = e.target.files;   // upload one at a time for simplicity
  if (first) openCropper(first);
  fileInput.value = '';             // reset so same file can be chosen again
});

exportBtn.addEventListener('click', () => {
  html2canvas(board).then(canvas => {
    const link = document.createElement('a');
    link.download = 'sticky-sketchboard.png';
    link.href = canvas.toDataURL();
    link.click();
  });
});

// ——— Kick things off ———
window.addEventListener('DOMContentLoaded', loadLayout);