// Preview i fotos nga URL
document.addEventListener("DOMContentLoaded", () => {
  const input = document.getElementById("imageUrlInput");
  const img = document.getElementById("imagePreview");
  const placeholder = document.getElementById("previewPlaceholder");

  if (!input || !img || !placeholder) return;

  function updatePreview() {
    const url = (input.value || "").trim();

    if (!url) {
      img.style.display = "none";
      img.src = "";
      placeholder.style.display = "grid";
      return;
    }

    img.src = url;
    img.style.display = "block";
    placeholder.style.display = "none";
  }

  // kur ndryshon input
  input.addEventListener("input", updatePreview);

  // init në load (për old('image'))
  updatePreview();

  // nëse URL është invalid ose nuk hapet
  img.addEventListener("error", () => {
    img.style.display = "none";
    img.src = "";
    placeholder.style.display = "grid";
    placeholder.textContent = "S’u ngarkua imazhi. Kontrollo URL-në.";
  });

  img.addEventListener("load", () => {
    placeholder.textContent = "Vendos një Image URL sipër";
  });
});
