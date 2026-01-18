document.addEventListener('DOMContentLoaded', function () {
  const el = document.getElementById('bagl-admin-map');
  if (!el || typeof L === 'undefined') return;

  const latInput = document.getElementById('bagl_lat');
  const lngInput = document.getElementById('bagl_lng');

  // Default center: Rome (fallback)
  const lat = parseFloat(latInput.value || '41.9');
  const lng = parseFloat(lngInput.value || '12.5');

  const map = L.map('bagl-admin-map').setView([lat, lng], 6);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19
  }).addTo(map);

  let marker = L.marker([lat, lng], { draggable: true }).addTo(map);

  // Update coordinates when dragging ends
  marker.on('dragend', function (e) {
    const pos = e.target.getLatLng();
    latInput.value = pos.lat.toFixed(6);
    lngInput.value = pos.lng.toFixed(6);
  });

  // Click on map to reposition marker
  map.on('click', function (e) {
    const pos = e.latlng;
    marker.setLatLng(pos);
    latInput.value = pos.lat.toFixed(6);
    lngInput.value = pos.lng.toFixed(6);
  });
});
