const reviewList = document.getElementById('reviewList');
const endpoint = "https://sheetdb.io/api/v1/ollwc6deh9x1p";

fetch(endpoint)
  .then(res => res.json())
  .then(data => {
    console.log(data[0]); // <--- mira aquí los nombres exactos
    data.forEach(resena => addReviewCard(resena));
  });

function addReviewCard(resena) {
  const nombre = resena["👤 Nombre y Apellido "] || "Sin nombre";
  
  // Buscar la columna que contiene 'experiencia' en su nombre
  const comentarioKey = Object.keys(resena).find(key => key.includes("experiencia"));
  const comentario = comentarioKey ? resena[comentarioKey] : "Sin comentario";

  const puntuacion = Number(resena["⭐ Calificación general de tu estadía"]) || 0;
  
  // Fecha de visita o marca temporal
  const fechaKey = Object.keys(resena).find(key => key.includes("Fecha de tu visita"));
  const fecha = fechaKey ? resena[fechaKey] : resena["Marca temporal"] || new Date().toLocaleDateString();

  const card = document.createElement('div');
  card.classList.add('servicio-card');
  card.innerHTML = `
    <img src="https://cdn-icons-png.flaticon.com/512/149/149071.png" alt="Usuario">
    <h3>${nombre}</h3>
    <p>${'★'.repeat(puntuacion)}${'☆'.repeat(5 - puntuacion)}</p>
    <p>${comentario}</p>
    <p class="fecha">Publicado el ${fecha}</p>
  `;
  reviewList.prepend(card);
}


