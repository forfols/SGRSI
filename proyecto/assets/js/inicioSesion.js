document.addEventListener("DOMContentLoaded", function () {
  const formulario = document.getElementById("inicioSesion");
  const btnObtenerUsuarios = document.getElementById('btnObtenerUsuarios');

  btnObtenerUsuarios.addEventListener("click", function (e) {
    const usuarios = [
    {
        id: -3,
        usuario: "Alexander Bogorodskiy",
        ci: "11111111",
        contra: "123",
        rol: "solicitante"
    },
    {
        id: -2,
        usuario: "Salvador Medina",
        ci: "22222222",
        contra: "123",
        rol: "tecnico"
    },
    {
        id: -1,
        usuario: "Lautaro Ocampo",
        ci: "33333333",
        contra: "123",
        rol: "administrador"
    },
    {
        id: 0,
        usuario: "Franco Pereira",
        ci: "44444444",
        contra: "123",
        rol: "solicitante"
    }
];
localStorage.setItem("usuarios", JSON.stringify(usuarios));
  })

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
        window.location.href = "indexAdministrador.php";
      } else if (usuario.rol === "tecnico") {
        window.location.href = "tecnico.php";
      } else {
        window.location.href = "indexSolicitante.php";
      }
    }
  });
});