document.addEventListener('DOMContentLoaded', function () {
  const input = document.getElementById('bagl-global-search');
  if (!input) return;

  let debounceTimer = null;

  input.addEventListener('input', function () {
    const query = input.value.trim().toLowerCase();

    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {

      const map = window.BAGL_GLOBAL_MAP;
      const markers = window.BAGL_GLOBAL_MARKERS;
      const groups = window.BAGL_GLOBAL_GROUPS;

      if (!map || !markers || !groups) return;

      if (!query) {
        if (markers.getLayers().length) {
          map.fitBounds(markers.getBounds());
        }
        return;
      }

      // 1) Search groups client-side
      const matched = groups.filter(g =>
        g.name.toLowerCase().includes(query)
      );

      if (matched.length > 0) {
        const g = matched[0];
        map.setView([g.lat, g.lng], 14);
        return;
      }

      // 2) Search places via Nominatim
      fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}`)
        .then(res => res.json())
        .then(results => {
          if (!Array.isArray(results) || results.length === 0) return;

          const r = results[0];
          map.setView([parseFloat(r.lat), parseFloat(r.lon)], 12);
        })
        .catch(() => {});
    }, 300);
  });
});
