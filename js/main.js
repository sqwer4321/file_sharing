// function handleFile() {
//   const uploadForm = document.getElementById('uploadForm');
//   const loadingDiv = document.getElementById('loadingDiv');

//   // Show loading screen
//   loadingDiv.style.display = 'block';

//   // Using FormData to append the file
//   let formData = new FormData(uploadForm);

//   // Using fetch API to send the file to the server
//   fetch('upload.php', {
//       method: 'POST',
//       body: formData,
//   })
//   .then(response => response.text())
//   .then(result => {
//       console.log('Success:', result);
//       loadingDiv.style.display = 'none'; // Hide loading screen
//       // Here you can redirect or show a success message
//   })
//   .catch(error => {
//       console.error('Error:', error);
//       loadingDiv.style.display = 'none'; // Hide loading screen in case of error
//   });

//   // Prevent form from submitting traditionally
//   event.preventDefault();
// }



// --------фон-------
document.addEventListener("DOMContentLoaded", function() {
    const container = document.getElementById('grid-container');
    const columns = Math.floor(window.innerWidth / 40); // Количество колонок на основе ширины окна
    const rows = Math.floor(window.innerHeight / 40); // Количество строк на основе высоты окна
    const gridSize = columns * rows; // Общее количество квадратов
  
    // Функция для создания квадратов сетки
    for (let i = 0; i < gridSize; i++) {
      const square = document.createElement('div');
      square.classList.add('grid-square');
      container.appendChild(square);
  
      // Событие наведения мыши
      square.addEventListener('mouseover', function() {
        // Изменение цвета фона на фиолетовый
        this.style.backgroundColor = '#800080';
  
        // Задержка для постепенного возвращения исходного цвета сетки
        setTimeout(() => {
          this.style.backgroundColor = '#232323';
        }, 500); // Время в миллисекундах
      });
    }
  });
// ------------------