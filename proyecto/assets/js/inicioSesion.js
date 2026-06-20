document.addEventListener("DOMContentLoaded", function () {
  const formulario = document.getElementById("inicioSesion");

  formulario.addEventListener("submit", function (e) {
    e.preventDefault();

    const ci = document.getElementById("ci").value;
    const contra = document.getElementById("contra").value;
    const usuarios = JSON.parse(localStorage.getItem("usuarios")) || [];

    const usuario = usuarios.find(u =>
      u.ci === ci &&
      u.contra === contra
    );

    if (!usuario) {
      alert("El usuario o la contraseña es incorrecta");
    } else {
        const usuarioUtilizado = [
    {
        usuario: usuario.usuario,
        ci: usuario.ci,
        rol: usuario.rol
    }]
    localStorage.setItem("usuarioUtilizado", JSON.stringify(usuarioUtilizado));
      if (usuario.rol === "administrador") {
        window.location.href = "indexAdministrador.html";
      } else if (usuario.rol === "tecnico") {
        window.location.href = "tecnico.html";
      } else {
        window.location.href = "indexSolicitante.html";
      }
    }
  });
});