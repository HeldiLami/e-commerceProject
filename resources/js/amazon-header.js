export function setupLogoutHandler() {
  const logoutForm = document.querySelector(".js-logout-form");
  if (!logoutForm) {
      return;
  }

  logoutForm.addEventListener("submit", () => {
      localStorage.removeItem("cart");
  });
}


setupLogoutHandler();