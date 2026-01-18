document.addEventListener("DOMContentLoaded", () => {
  const input = document.getElementById("photo");
  const img = document.getElementById("photoPreviewImg");
  if (!input || !img) return;

  const defaultSrc = img.dataset.defaultSrc;

  function setPreview() {
    const val = (input.value || "").trim();

    if (!val) {
      img.src = defaultSrc;
      return;
    }

    if (val.startsWith("/")) {
      img.src = `${window.location.origin}${val}`;
      return;
    }

    img.src = val;
  }

  img.addEventListener("error", () => {
    img.src = defaultSrc;
  });

  input.addEventListener("input", setPreview);
  setPreview();
});
