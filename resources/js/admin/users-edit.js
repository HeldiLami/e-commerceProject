document.addEventListener("DOMContentLoaded", () => {
  const input = document.getElementById("photo");
  const img = document.getElementById("photoPreviewImg");
  const placeholder = document.getElementById("photoPlaceholder");
  if (!input || !img || !placeholder) return;

  const defaultSrc = img.dataset.defaultSrc;

  function showPlaceholder(msg) {
    placeholder.textContent = msg;
    placeholder.style.display = "grid";
    img.style.display = "none";
  }

  function showImg(src) {
    img.src = src;
    img.style.display = "block";
    placeholder.style.display = "none";
  }

  function normalizeValue(v) {
    v = (v || "").trim();
    if (!v) return "";

    // nëse është path lokal pa /, e bëjmë relative nga root
    if (!/^https?:\/\//i.test(v) && !v.startsWith("/")) {
      v = "/" + v;
    }
    return v;
  }

  function update() {
    const v = normalizeValue(input.value);

    if (!v) {
      return showImg(defaultSrc);
    }

    // basic check: duhet të jetë http(s) ose /path
    if (!/^https?:\/\//i.test(v) && !v.startsWith("/")) {
      return showPlaceholder("URL jo e vlefshme. Përdor https://... ose /path");
    }

    showImg(v);
  }

  input.addEventListener("input", update);

  img.addEventListener("error", () => {
    showPlaceholder("S’u ngarkua imazhi. Kontrollo URL/path.");
  });

  update();
});
