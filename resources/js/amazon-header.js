export function setupLogoutHandler() {
  const logoutForm = document.querySelector('.js-logout-form');

  if (logoutForm) {
    logoutForm.addEventListener('submit', () => {
      localStorage.removeItem('cart');
      // Mund të shtosh edhe një console.log për debug
      console.log('Cart cleared on logout');
    });
  }
}


setupLogoutHandler();