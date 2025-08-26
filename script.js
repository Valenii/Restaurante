const btnLogin = document.getElementById('btn-login');
const btnRegister = document.getElementById('btn-register');

const formLogin = document.getElementById('form-login');
const formRegister = document.getElementById('form-register');

btnLogin.addEventListener('click', () => {
  formLogin.classList.add('activo');
  formRegister.classList.remove('activo');
});

btnRegister.addEventListener('click', () => {
  formRegister.classList.add('activo');
  formLogin.classList.remove('activo');
});
