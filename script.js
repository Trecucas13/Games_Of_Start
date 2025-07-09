document.addEventListener("DOMContentLoaded", () => {
  const inicio = document.getElementById("inicio");
  const preguntas = document.getElementById("preguntas");
  const resultados = document.getElementById("resultados");
  const formInicio = document.getElementById("formInicio");
  const textoPregunta = document.getElementById("textoPregunta");
  const respuestaInput = document.getElementById("respuesta");
  const enviarRespuesta = document.getElementById("enviarRespuesta");
  const resultadosInfo = document.getElementById("resultadosInfo");

  const EMOJIS_POSITIVOS = ["😊", "😁", "😄", "😃", "🤩", "🥳"];
  let indiceEmoji = 0;

  // Contenedor de emoji dentro del contenedor de preguntas
  const emoji = document.createElement("div");
  emoji.classList.add("emoji");
  emoji.textContent = EMOJIS_POSITIVOS[indiceEmoji];
  emoji.style.fontSize = "50px";
  preguntas.insertBefore(emoji, textoPregunta);

  let preguntasArray = [
    { pregunta: "¿Cómo crees que la alegría en el trabajo impacta la experiencia del cliente?" },
    { pregunta: "¿Menciona 3 acciones que consideras clave para mantener un ambiente laboral feliz y motivador?" },
    { pregunta: "¿Qué significa la felicidad organizacional y cómo se relaciona con el éxito profesional?" },
    { pregunta: "Describe un momento en el que hayas sentido verdadera alegría realizando tu trabajo?" },
    { pregunta: "¿Qué ideas podrías proponer para fomentar la alegría y el bienestar entre tus compañeros de equipo?" }
  ];

  let indicePregunta = 0;
  let nombreJugador = "";
  let campanaJugador = "";
  let respuestasUsuario = [];

  formInicio.addEventListener("submit", (e) => {
    e.preventDefault();
    nombreJugador = document.getElementById("nombre").value;
    campanaJugador = document.getElementById("campana").value;
    inicio.classList.remove("activa");
    preguntas.classList.add("activa");
    preguntasArray = preguntasArray.sort(() => Math.random() - 0.5); // Aleatorizar preguntas
    mostrarPregunta();
  });

  function mostrarPregunta() {
    if (indicePregunta < preguntasArray.length) {
      textoPregunta.textContent = preguntasArray[indicePregunta].pregunta;
    } else {
      mostrarResultados();
    }
  }

  function aplicarEfectoPantalla() {
    preguntas.classList.add("shake");
    setTimeout(() => preguntas.classList.remove("shake"), 500);
  }

  function procesarRespuesta() {
    let respuestaUsuario = respuestaInput.value.trim();
    if (respuestaUsuario !== "") {
      respuestasUsuario.push({
        pregunta: preguntasArray[indicePregunta].pregunta,
        respuesta: respuestaUsuario,
      });

      // Cambia el emoji de manera aleatoria entre los positivos
      indiceEmoji = (indiceEmoji + 1) % EMOJIS_POSITIVOS.length;
      emoji.textContent = EMOJIS_POSITIVOS[indiceEmoji];

      aplicarEfectoPantalla();
      indicePregunta++;
      respuestaInput.value = "";
      mostrarPregunta();
    }
  }

  enviarRespuesta.addEventListener("click", () => {
    procesarRespuesta();
  });

  respuestaInput.addEventListener("keypress", (event) => {
    if (event.key === "Enter") {
      event.preventDefault();
      procesarRespuesta();
    }
  });

  function mostrarResultados() {
    preguntas.classList.remove("activa");
    resultados.classList.add("activa");
    resultadosInfo.innerHTML = `
          <h3>${nombreJugador}</h3>
          <p><strong>Campaña:</strong> ${campanaJugador}</p>
          <p><strong>Emoji Final:</strong> ${emoji.textContent}</p>
      `;
    console.log("Respuestas del usuario:", respuestasUsuario);

    // Guardar en la base de datos
    guardarDatos();
  }

  function guardarDatos() {
    let datos = new FormData();
    datos.append("nombre", nombreJugador);
    datos.append("campana", campanaJugador);
    datos.append("emoji_final", emoji.textContent);
    datos.append("respuestas", JSON.stringify(respuestasUsuario)); // Enviar respuestas como JSON

    fetch("guardar.php", {
        method: "POST",
        body: datos
    })
    .then(response => response.text())
    .then(data => console.log("Respuesta del servidor:", data))
    .catch(error => console.error("Error:", error));
  }

});
function aplicarEfectoPantalla() {
  preguntas.classList.add("shake"); // Agrega la clase de vibración
  setTimeout(() => preguntas.classList.remove("shake"), 500); // La quita después de 0.5s
}
