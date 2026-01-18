document.addEventListener('DOMContentLoaded', function () {
  const el = document.getElementById('bagl-group-global-map');
  if (!el || typeof L === 'undefined' || !window.BAGL_MAP) return;

  // Default center from admin settings
  const map = L.map('bagl-group-global-map').setView(
    [BAGL_MAP.center.lat, BAGL_MAP.center.lng],
    BAGL_MAP.center.zoom
  );

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19
  }).addTo(map);

  const markers = L.markerClusterGroup();
  let groups = [];

  fetch(BAGL_MAP.rest_url)
    .then(res => res.json())
    .then(data => {
      if (!Array.isArray(data)) return;

      groups = data;

      data.forEach(group => {
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
            <div class="bagl-popup-address">${address}</div>
          </div>
        `);

        markers.addLayer(marker);
      });

      map.addLayer(markers);

      if (markers.getLayers().length) {
        map.fitBounds(markers.getBounds());
      }

      // Export for search script
      window.BAGL_GLOBAL_MAP = map;
      window.BAGL_GLOBAL_MARKERS = markers;
      window.BAGL_GLOBAL_GROUPS = groups;
    })
    .catch(() => {});
});
