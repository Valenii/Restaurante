// Seleccionamos los botones de "Login" y "Register" por su ID
const btnLogin = document.getElementById('btn-login');
const btnRegister = document.getElementById('btn-register');

// Seleccionamos los formularios de login y registro
const formLogin = document.getElementById('form-login');
const formRegister = document.getElementById('form-register');

// Evento al hacer click en el botón de "Login"
btnLogin.addEventListener('click', () => {
  formLogin.classList.add('activo');      // Mostramos el formulario de login
  formRegister.classList.remove('activo'); // Ocultamos el formulario de registro
});

// Evento al hacer click en el botón de "Register"
btnRegister.addEventListener('click', () => {
  formRegister.classList.add('activo');   // Mostramos el formulario de registro
  formLogin.classList.remove('activo');    // Ocultamos el formulario de login
});
