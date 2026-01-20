document.addEventListener("DOMContentLoaded", () => {
  const photoInput = document.getElementById("photo");
  const photoPreview = document.getElementById("photoPreviewImg");
  const errorSpan = document.getElementById("photo-error");

  if (!photoInput || !photoPreview) return;

  const originalSrc = photoPreview.src;

  photoInput.addEventListener("change", function(event) {
  const file = event.target.files[0];

  if (errorSpan) {
    errorSpan.textContent = "";
  }

  if (!file) {
    photoPreview.src = originalSrc;
    return;
  }

  if (!file.type.startsWith("image/")) {
    if (errorSpan) {
      errorSpan.textContent = "Ju lutem zgjidhni një skedar imazhi (JPG, PNG, etj).";
    }
    this.value = ""; 
    photoPreview.src = originalSrc;
    return;
  }

  const reader = new FileReader();
  
  reader.onload = function(e) {
    photoPreview.src = e.target.result;
  };
  
  reader.readAsDataURL(file);
  });
});