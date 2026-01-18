document.addEventListener('DOMContentLoaded', function () {
  const el = document.getElementById('bagl-group-map');
  if (!el || typeof L === 'undefined' || !window.BAGL_SINGLE) return;

  const lat = parseFloat(BAGL_SINGLE.lat);
  const lng = parseFloat(BAGL_SINGLE.lng);

  const map = L.map('bagl-group-map').setView([lat, lng], 13);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19
  }).addTo(map);

  const icon = L.icon({
    iconUrl: BAGL_SINGLE.icon,
    iconSize: [32, 32],
    iconAnchor: [16, 32],
    popupAnchor: [0, -32]
  });

  const marker = L.marker([lat, lng], { icon }).addTo(map);

  const address = (BAGL_SINGLE.address || '').replace(/\n/g, '<br>');

  marker.bindPopup(
    `<strong>${BAGL_SINGLE.name}</strong><br>${address}`
  );
});
