const handleRegisterSubmit = () => {
  const registerForm = document.querySelector('.js-register-form');
  const submitButton = document.querySelector('.js-register-submit');

  if (registerForm && submitButton) {
      registerForm.addEventListener('submit', () => {
          submitButton.disabled = true;
          submitButton.textContent = 'Registering...';
      });
  }
};

handleRegisterSubmit();