<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/style.css">
    <link rel="stylesheet" href="./css/style2.css">
    <script src="./js/cell.js"></script>

    <link rel="icon" href="">
    <script src="./js/main.js"></script>
    <title>File-Sharing</title>
    <style>
        .progress-bar {
            width: 100%;
            background-color: #e0e0e0;
            border-radius: 5px;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .progress-bar > div {
            height: 100%;
            width: 0;
            background-color: #4caf50;
            border-radius: 3px
            transition: width 0.5s ease-in-out;
        }
    </style>
</head>
<body>

<div id="grid-container"></div>

<div class="uploudfile">   
    <label for="fileInput">
        <img class="ani" id="moveImage" src="./plus.png" alt="">
    </label>
    <input type="file" id="fileInput" style="display: none;" onchange="handleFile()">
    <div class="loading" id="loadingDiv" style="display: none;">
        <iframe src="./loading/loading.html" frameborder="0"></iframe>
    </div>
    <div class="uploudfile2" style="display: none;">
        <div class="uppbar2">
            <p class="numbers-file2">0 files</p><p class="size-files2">0 Gb</p>
        </div>
        <div class="centerbar2">
            <div class="cell">
                <div class="img"><img src="./icons/document.png" alt=""></div>
                <div class="name">Empty</div>
                <div class="gb">3.4 Gb</div>
                <div class="download"><img src="./icons/download.png" alt=""></div>
            </div>
        </div>
        <div class="lowerbar2">
            <button id="transferButton" style='display: ;'>Transfer</button>
        </div>
        <div class="copy2" style="display: none;">
            <label for="inp" class="inp">
                <input type="text" id="inp" placeholder="&nbsp;">
                <span class="label">Ссылка</span>
                <span class="focus-bg"></span>
                <button id="copyButton" onclick="CopyLink()"><img src="./free-icon-copy-126498.png" alt=""></button>
            </label>
        </div>
    </div>
</div>

<div class="progress-bar"><div></div></div>

<!-- сообщения (скопировано) -->
<div class="TextMessangeCopy" id="copyMessage" style='display: none;'>Скопировано</div>

<!-- сообщения (размер файла состовляет больше 150mb) -->
<div class="TextMessangeSaze" id="errorDiv" style="display: none;"></div>

<!-- сообщение (Файл не выбран или ошибка чтения файла) -->
<div class="errorNotification" id="errorNotification" style="display: none;"></div>

<script>


function CopyLink() {
    // Получаем элемент input
    var copyText = document.getElementById("inp");

    // Выделяем текст внутри input
    copyText.select();
    copyText.setSelectionRange(0, 99999); // Для мобильных устройств

    // Копируем выделенный текст в буфер обмена
    document.execCommand("copy");

    // Выводим сообщение о копировании (опционально)
     // Получаем элемент уведомления
     var message = document.getElementById("copyMessage");
    // Отображаем уведомление
    message.style.display = "block";
    // Скрываем уведомление через 5 секунды
    setTimeout(function() {
        message.style.display = "none";
    }, 5000);
}

// открытие ссылки при нажатие на кнопку transfer
document.getElementById('transferButton').addEventListener('click', function() {
    if (!fileData) {
        // Получаем элемент для отображения ошибки
        var errorNotification = document.getElementById('errorNotification');
        errorNotification.innerText = 'Файл не выбран или ошибка чтения файла';
        // Показываем блок с ошибкой
        errorNotification.style.display = 'block';
        setTimeout(function() {
            errorNotification.style.display = 'none';
        }, 5000);

        console.error('Файл не выбран или ошибка чтения файла');
        return;
    }

    const formData = new FormData();
    formData.append('file', fileData); // Добавляем файл в FormData

    const xhr = new XMLHttpRequest();
    xhr.onload = function() {
        if (this.status == 200) {
            //console.log(this.responseText); // Обработка успешной загрузки
            const input_box = document.getElementById('inp');
            input_box.value = this.responseText;
        } else {
            console.log('Ошибка:' + this.status); // Обработка ошибок сервера
        }
    };

    xhr.open('POST', 'upload.php', true); // Настройка запроса на загрузку файла
    xhr.send(formData); // Отправка файла на сервер

    const moveImage = document.getElementById('moveImage');
    moveImage.style.display = 'none';

    var copyDiv = document.querySelector('.copy2');
    copyDiv.style.display = 'block';

    var lowerBar = document.querySelector('.lowerbar2');
    lowerBar.style.display = 'none';


});


let fileData = null; // Переменная для хранения данных файла

function handleFile() {
    const fileInput = document.getElementById('fileInput');
    const loadingDiv = document.getElementById('loadingDiv');
    const uploudfile2 = document.querySelector('.uploudfile2');
    const maxFileSize = 150 * 1024 * 1024; // Максимальный размер файла 150MB

       // Проверка размера файла
        if (fileInput.files[0].size > maxFileSize) {
            errorDiv.innerText = `Ошибка: максимальный размер файла для загрузки составляет ${maxFileSize / 1024 / 1024}MB.`;
            errorDiv.style.display = 'block'; // Показать блок с ошибкой
            setTimeout(function() {
                errorDiv.style.display = 'none';
            }, 5000);
            fileInput.value = ''; // Сброс выбранного файла
            return;
        } else {
            errorDiv.style.display = 'none'; // Скрыть блок с ошибкой, если размер файла в порядке
        }

    loadingDiv.style.display = 'block'; // Показываем индикатор загрузки

    fileData = fileInput.files[0]; // Сохраняем объект файла напрямую без чтения содержимого

    const fileSize = fileInput.files[0].size; // Получаем размер файла в байтах
    const fileName = fileInput.files[0].name; // Получаем название файла

    uploudfile2.style.display = 'block'; // Показываем элемент после выбора файла
    loadingDiv.style.display = 'none'; // Скрываем индикатор загрузки

    // Дополнительная анимация или изменения в UI
    const moveImage = document.getElementById('moveImage');
    if (fileInput.files.length > 0) {
        moveImage.style.left = '25%';
    }
    fileInput.value = ''; // Очищаем значение input после выбора файла
}

</script>
<!-------------->
    
</body>
</html>