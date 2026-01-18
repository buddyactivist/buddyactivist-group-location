document.addEventListener('DOMContentLoaded', function () {
  const el = document.getElementById('bagl-group-global-map');
  if (!el || typeof L === 'undefined' || !window.BAGL_MAP) return;

  // Default center: Italy
  const map = L.map('bagl-group-global-map').setView([41.9, 12.5], 6);

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19
  }).addTo(map);

  const markers = L.markerClusterGroup();

  fetch(BAGL_MAP.rest_url)
    .then(res => res.json())
    .then(groups => {
      if (!Array.isArray(groups)) return;

      groups.forEach(group => {
        if (!group.lat || !group.lng) return;

        const icon = L.icon({
          iconUrl: group.icon,
          iconSize: [32, 32],
          iconAnchor: [16, 32],
          popupAnchor: [0, -32]
        });

        const marker = L.marker([group.lat, group.lng], { icon });

        const address = (group.address || '').replace(/\n/g, '<br>');

        marker.bindPopup(`
          <div class="bagl-popup">
            ${group.avatar ? `<img src="${group.avatar}" class="bagl-popup-avatar" />` : ''}
            <div class="bagl-popup-title">
              <a href="${group.link}">${group.name}</a>
            </div>
            <div class="bagl-popup-address">
              ${address}
            </div>
          </div>
        `);

        markers.addLayer(marker);
      });

      map.addLayer(markers);

      // Fit map to markers if any exist
      if (markers.getLayers().length) {
        map.fitBounds(markers.getBounds());
      }
    })
    .catch(() => {
      // Optional: silent fail to avoid breaking UI
    });
});
