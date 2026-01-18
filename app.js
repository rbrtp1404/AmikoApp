document.addEventListener('DOMContentLoaded', function(){

  // Año en footer
  const year = document.getElementById('year');
  if (year) year.textContent = new Date().getFullYear();

  // --- FORMULARIO ---
  const signup = document.getElementById('signupForm');
  if (signup){
    signup.addEventListener('submit', function(e){
      e.preventDefault();

      const name = document.getElementById('signupName')?.value.trim();
      const phone = document.getElementById('signupPhone')?.value.trim();
      const email = document.getElementById('signupEmail')?.value.trim();

      // Validación nombre
      if (!name || name.length < 2){
        alert('Por favor ingresa tu nombre.');
        return;
      }

      // Validación teléfono — acepta +, espacios y guiones
      if (!phone || !/^\+?[0-9\s-]{7,}$/.test(phone)){
        alert('Por favor ingresa un número de teléfono válido.');
        return;
      }

      // Validación email
      if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)){
        alert('Por favor ingresa un correo válido.');
        return;
      }

      // Enviar al PHP con fetch
      const formData = new FormData(signup);
      fetch('save.php', {
        method: 'POST',
        body: formData
      })
      .then(res => res.json())
      .then(data => {
        alert(data.message);
        if (data.status === 'success') signup.reset();
      })
      .catch(err => {
        console.error(err);
        alert('Ocurrió un error, intenta nuevamente.');
      });

    });
  }

  // Smooth scrolling for anchor links
  document.querySelectorAll('a[href^="#"]').forEach(a=>{
    a.addEventListener('click', function(e){
      const href = this.getAttribute('href');
      if (!href || href === '#') return;
      const target = document.querySelector(href);
      if (target){
        e.preventDefault();
        target.scrollIntoView({behavior:'smooth', block:'start'});
      }
    });
  });

});
