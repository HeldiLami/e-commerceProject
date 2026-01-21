document.addEventListener("DOMContentLoaded", () => {
  const photoInput = document.getElementById("photo");
  const photoPreview = document.getElementById("photoPreviewImg");
  const errorSpan = document.getElementById("photo-error");

  if (!photoInput || !photoPreview) return;

  const originalSrc = photoPreview.src;

  photoInput.addEventListener("change", function (event) {
    const file = event.target.files && event.target.files[0];

    if (errorSpan) {
      errorSpan.textContent = "";
    }

    // Nëse user-i nuk zgjodhi file (ose e hoqi zgjedhjen)
    if (!file) {
      photoPreview.src = originalSrc;
      return;
    }

    // Kontrollo që është imazh
    if (!file.type || !file.type.startsWith("image/")) {
      if (errorSpan) {
        errorSpan.textContent =
          "Ju lutem zgjidhni një skedar imazhi (JPG, PNG, etj).";
      }

      // Pastro input-in që të mos mbetet file i gabuar
      this.value = "";
      photoPreview.src = originalSrc;
      return;
    }

    const reader = new FileReader();

    reader.onload = function (e) {
      photoPreview.src = e.target.result;
    };

    reader.readAsDataURL(file);
  });
});
