const API = (typeof window !== 'undefined' && window.location?.origin)
  ? window.location.origin.replace(/\/$/, '') + '/wp-json/bridge/v1'
  : (window.bridgeData?.rest_url ? window.bridgeData.rest_url.replace(/\/$/, '') + '/bridge/v1' : '/wp-json/bridge/v1');

let _nonce = window.bridgeData?.nonce ?? '';

// Adopted by the login gate after a successful /login (post-auth nonce).
export function setNonce(n) { if (typeof n === 'string' && n) _nonce = n; }

// Fetch a fresh nonce from our own cookie-validated endpoint.
// NOT the REST index — /wp-json/ does not expose a nonce (verified).
let _nonceRefreshPromise = null;
let _hasSettled = false;
async function refreshNonce() {
  if (_nonceRefreshPromise) return _nonceRefreshPromise;
  _nonceRefreshPromise = (async () => {
    try {
      const res = await fetch(API + '/session', { credentials: 'include' });
      if (!res.ok) return false;            // 401 here = session genuinely gone
      const data = await res.json();
      if (data?.nonce && typeof data.nonce === 'string') { _nonce = data.nonce; return true; }
    } catch {}
    return false;
  })();
  const ok = await _nonceRefreshPromise;
  _nonceRefreshPromise = null;
  return ok;
}

function convertRgbToHexInHtml(html) {
  if (typeof html !== 'string') return html;
  
  // Replace rgb(r, g, b)
  let result = html.replace(/rgb\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*\)/g, (match, r, g, b) => {
    const red = parseInt(r, 10);
    const green = parseInt(g, 10);
    const blue = parseInt(b, 10);
    return "#" + ((1 << 24) + (red << 16) + (green << 8) + blue).toString(16).slice(1);
  });
  
  // Replace rgba(r, g, b, a)
  result = result.replace(/rgba\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)\s*,\s*([\d.]+)\s*\)/g, (match, r, g, b, a) => {
    const red = parseInt(r, 10);
    const green = parseInt(g, 10);
    const blue = parseInt(b, 10);
    const alpha = parseFloat(a);
    if (alpha === 0) return 'transparent';
    return "#" + ((1 << 24) + (red << 16) + (green << 8) + blue).toString(16).slice(1);
  });
  
  return result;
}

function sanitizePayload(obj) {
  if (typeof obj === 'string') {
    return convertRgbToHexInHtml(obj);
  }
  if (Array.isArray(obj)) {
    return obj.map(sanitizePayload);
  }
  if (obj !== null && typeof obj === 'object') {
    const res = {};
    for (const key in obj) {
      if (Object.prototype.hasOwnProperty.call(obj, key)) {
        res[key] = sanitizePayload(obj[key]);
      }
    }
    return res;
  }
  return obj;
}

const MAX_RETRIES = 2;
const RETRY_DELAYS = [1500, 3000]; // ms — escalating backoff

const MOCK_CATEGORY_TREE = {
  "Utility Vehicles": { "Utility": [] },
  "Tractors": {
    "175 HP to 299 HP": [],
    "100 HP to 174 HP": [],
    "40 HP to 99 HP": [],
    "Less than 40 HP": []
  },
  "Planting Equipment": { "Other": [] },
  "Tillage Equipment": {
    "Chisel Plows": [],
    "Disks": [],
    "Plows": [],
    "Rippers": [],
    "Rotary Tillage": [],
    "Row Crop Cultivators": [],
    "Other": []
  },
  "Hay and Forage Equipment": {
    "Bale Accumulators / Movers": [],
    "Disc Mowers": [],
    "Mower Conditioners/Windrowers": ["Self-Propelled", "Pull-Type", "Mounted"],
    "Hay Rakes": [],
    "Tedders": [],
    "Rotary Mowers": [],
    "Round Balers": [],
    "Square Balers": ["Large", "Small"],
    "Tub Grinders/Bale Processors": [],
    "Other": []
  },
  "Chemical Applicators": { "Sprayers": ["3 pt/Mounted"] },
  "Grain Handling / Storage Equipment": { "Grain Augers": [] },
  "Ag Trailers": { "Other": [] },
  "Snow Equipment": {
    "Lawn Mowers": ["Riding"],
    "Snow Blowers": []
  },
  "Implements": {
    "Blades/Box Scrapers": [],
    "Manure Spreaders": ["Dry"]
  },
  "Turf Equipment": { "Mowers": ["Fairway"] },
  "Trucks": {
    "Pickup Trucks": ["1/2 Ton"],
    "Service Trucks / Utility Trucks / Mechanic Trucks": [],
    "Truck Bodies Only": ["Other"]
  },
  "Semi-Trailers": { "Log Trailers": [] },
  "Trailers": {
    "Car Hauler Trailers": ["Enclosed", "Open"],
    "Cargo / Enclosed Trailers": [],
    "Dump Trailers": [],
    "Flatbed / Tag Trailers": [],
    "Livestock Trailers": [],
    "Tilt Trailers": [],
    "Landscaping Trailers": [],
    "Utility Trailers": ["ATV", "Snowmobile"],
    "Other Trailers": []
  }
};

// ── Localhost mock: a functional, localStorage-backed dev store ──
// Lets the app be fully exercised in the browser without a WordPress backend.
const MOCK_NS = 'bridge_mock_v1';
const MOCK_MEDIA_KEY = 'bridge_mock_media';

function mockLoad(key) {
  try { return JSON.parse(localStorage.getItem(`${MOCK_NS}:${key}`)) || []; } catch { return []; }
}

function mockSave(key, val) {
  try { localStorage.setItem(`${MOCK_NS}:${key}`, JSON.stringify(val)); } catch (e) { console.warn('[Bridge mock] storage failed:', e); }
}

function mockMediaLoad() {
  try { return JSON.parse(localStorage.getItem(MOCK_MEDIA_KEY)) || {}; } catch { return {}; }
}

function mockMediaSave(map) {
  try { localStorage.setItem(MOCK_MEDIA_KEY, JSON.stringify(map)); } catch (e) { console.warn('[Bridge mock] media storage failed:', e); }
}

let _mockIdCounter = 0;
function mockId() { return Date.now() * 1000 + (_mockIdCounter++ % 1000); }

function mockParts(path) {
  return path.split('?')[0].split('/').filter(Boolean);
}

function mockQuery(path) {
  const qs = path.split('?')[1] || '';
  return new URLSearchParams(qs);
}

// Resolve stored image_ids → URLs so the UI renders uploaded images.
function mockResolveImages(u) {
  const media = mockMediaLoad();
  const images = (Array.isArray(u.image_ids) ? u.image_ids : []).map(id => media[id]).filter(Boolean);
  const implementsResolved = (u.implements || []).map(imp => ({
    ...imp,
    image: imp.image_id && media[imp.image_id] ? media[imp.image_id] : (imp.image || ''),
  }));
  return { ...u, images, implements: implementsResolved };
}

function mockHandleInventory(method, parts, options, query) {
  const inventory = mockLoad('inventory');
  const deleted = mockLoad('deleted');
  let body = {};
  if (options.body && typeof options.body === 'string') {
    try { body = JSON.parse(options.body); } catch {}
  }

  // /inventory/deleted  (GET)
  if (parts.length === 2 && parts[1] === 'deleted') {
    return Promise.resolve({ items: deleted.map(mockResolveImages), total: deleted.length });
  }

  // /inventory/:id/restore  (POST)
  if (parts.length === 3 && parts[2] === 'restore' && method === 'POST') {
    const id = Number(parts[1]);
    const idx = deleted.findIndex(u => u.id === id);
    if (idx !== -1) {
      const unit = deleted.splice(idx, 1)[0];
      delete unit.deleted_at;
      inventory.push(unit);
      mockSave('inventory', inventory);
      mockSave('deleted', deleted);
    }
    return Promise.resolve({ success: true });
  }

  // /inventory/:id/permanent  (DELETE)
  if (parts.length === 3 && parts[2] === 'permanent' && method === 'DELETE') {
    const id = Number(parts[1]);
    mockSave('deleted', deleted.filter(u => u.id !== id));
    return Promise.resolve({ success: true });
  }

  // /inventory/:id  (GET / PATCH / DELETE)
  if (parts.length === 2) {
    const id = Number(parts[1]);
    const unit = inventory.find(u => u.id === id) || deleted.find(u => u.id === id);
    if (method === 'DELETE') {
      const idx = inventory.findIndex(u => u.id === id);
      if (idx !== -1) {
        const [removed] = inventory.splice(idx, 1);
        deleted.push({ ...removed, deleted_at: new Date().toISOString() });
        mockSave('inventory', inventory);
        mockSave('deleted', deleted);
      }
      return Promise.resolve({ success: true });
    }
    if (method === 'PATCH') {
      const idx = inventory.findIndex(u => u.id === id);
      if (idx !== -1) {
        inventory[idx] = { ...inventory[idx], ...body, id };
        mockSave('inventory', inventory);
      }
      return Promise.resolve(mockResolveImages(inventory[idx] || { ...body, id }));
    }
    return Promise.resolve(unit ? mockResolveImages(unit) : null);
  }

  // /inventory  (GET / POST)
  if (method === 'POST') {
    const unit = { ...body, id: mockId(), created_at: new Date().toISOString() };
    inventory.push(unit);
    mockSave('inventory', inventory);
    return Promise.resolve(mockResolveImages(unit));
  }

  const page = Math.max(1, Number(query?.get('page')) || 1);
  const perPage = Math.max(1, Number(query?.get('per_page')) || inventory.length || 1);
  const start = (page - 1) * perPage;
  const pageItems = inventory.slice(start, start + perPage);
  return Promise.resolve({ items: pageItems.map(mockResolveImages), total: inventory.length });
}

function handleMockApi(path, options = {}) {
  const method = (options.method || 'GET').toUpperCase();
  const parts = mockParts(path);
  const query = mockQuery(path);

  if (parts[0] === 'inventory') {
    return mockHandleInventory(method, parts, options, query);
  }

  if (parts[0] === 'me') {
    return Promise.resolve({ id: 1, name: 'Greg', roles: ['administrator', 'editor'] });
  }
  if (parts[0] === 'brands') {
    const list = mockLoad('brands');
    if (method === 'POST') {
      const body = JSON.parse(options.body || '{}');
      const updated = body.brands || list;
      mockSave('brands', updated);
      return Promise.resolve({ brands: updated });
    }
    return Promise.resolve(list.length ? list : ['Mahindra', 'Zetor', 'Titan Trailers', 'Big Tex']);
  }
  if (parts[0] === 'years') {
    const list = mockLoad('years');
    if (method === 'POST') {
      const body = JSON.parse(options.body || '{}');
      const updated = body.years || list;
      mockSave('years', updated);
      return Promise.resolve({ years: updated });
    }
    return Promise.resolve(list.length ? list : ['2026', '2025', '2024', '2023', '2022']);
  }
  if (parts[0] === 'categories') {
    return Promise.resolve(Object.keys(MOCK_CATEGORY_TREE));
  }
  if (parts[0] === 'subcategories') {
    const subs = [];
    Object.values(MOCK_CATEGORY_TREE).forEach(o => {
      if (Array.isArray(o)) {
        subs.push(...o);
      } else if (o && typeof o === 'object') {
        subs.push(...Object.keys(o));
      }
    });
    return Promise.resolve([...new Set(subs)]);
  }
  if (parts[0] === 'category-tree') {
    return Promise.resolve(MOCK_CATEGORY_TREE);
  }
  if (parts[0] === 'session' || parts[0] === 'sessions') {
    return Promise.resolve({ items: [], nonce: 'mock-nonce' });
  }
  if (parts[0] === 'ledger') {
    return Promise.resolve({ items: [] });
  }
  return Promise.resolve({});
}

export async function apiFetch(path, options = {}) {
  if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
    return handleMockApi(path, options);
  }
  const headers = {
    'Content-Type': 'application/json',
    'X-WP-Nonce': _nonce,
    ...(options.headers ?? {}),
  };

  let body = options.body;
  if (body && typeof body === 'string') {
    try {
      const parsed = JSON.parse(body);
      const sanitized = sanitizePayload(parsed);
      body = JSON.stringify(sanitized);
    } catch (e) {
      body = convertRgbToHexInHtml(body);
    }
  }

  for (let attempt = 0; attempt <= MAX_RETRIES; attempt++) {
    const fetchOpts = {
      credentials: 'include',
      ...options,
      headers: { ...headers, 'X-WP-Nonce': _nonce },
      ...(body ? { body } : {}),
    };
    const res = await fetch(`${API}${path}`, fetchOpts);

    // Retry on rate-limit (429) or server overload (503) with backoff
    if ((res.status === 429 || res.status === 503) && attempt < MAX_RETRIES) {
      const delay = RETRY_DELAYS[attempt] ?? 3000;
      console.warn(`[Bridge] ${res.status} on ${path} — retrying in ${delay}ms (attempt ${attempt + 1}/${MAX_RETRIES})`);
      await new Promise(r => setTimeout(r, delay));
      continue;
    }

    if (!res.ok) {
      // ── Stale-nonce auto-recovery: 403 + rest_cookie_invalid_nonce ──
      if (res.status === 403 && attempt === 0) {
        const errBody = await res.clone().json().catch(() => ({}));
        if (errBody.code === 'rest_cookie_invalid_nonce') {
          if (!_hasSettled) {
            _hasSettled = true;
            await new Promise(r => setTimeout(r, 400));
          }
          if (await refreshNonce()) continue;        // got a fresh nonce → retry once
          // refresh failed → session is actually gone; fall into the re-auth path below
          if (window.bridgeData?.is_mobile_app) {
            window.dispatchEvent(new CustomEvent('bridge:token-expired'));
          }
        }
      }

      // Existing 401 handler — session/token gone, NOT a nonce problem. Unchanged.
      if (res.status === 401 && window.bridgeData?.is_mobile_app) {
        window.dispatchEvent(new CustomEvent('bridge:token-expired'));
      }

      const errBody = await res.json().catch(() => ({}));
      const error = new Error(errBody.message ?? `Request failed: ${res.status}`);
      error.code = errBody.code;
      error.data = errBody.data;
      throw error;
    }
    return res.json();
  }
}

export async function uploadFile(file) {
  if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
    const url = URL.createObjectURL(file);
    const id = mockId();
    const media = mockMediaLoad();
    media[id] = url;
    mockMediaSave(media);
    return { id, url };
  }

  const headers = {
    'X-WP-Nonce': _nonce,
  };

  const form = new FormData();
  form.append('file', file);
  const res = await fetch(`${API}/media`, {
    method: 'POST',
    headers,
    credentials: 'include',
    body: form,
  });
  if (!res.ok) {
    const text = await res.text().catch(() => '');
    throw new Error(`Upload failed (${res.status}): ${text.slice(0, 200)}`);
  }
  return res.json(); // { id, url }
}
