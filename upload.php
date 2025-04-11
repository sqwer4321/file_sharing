<?php

function generateRandomString($length = 10) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
    $charactersLength = strlen($characters);
    $randomPassword = '';
    for ($i = 0; $i < $length; $i++) {
        $randomPassword .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomPassword;
}

error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $uploadDir = 'uploads/';
    $uploadedFileName = basename($_FILES['file']['name']);
    $uploadFile = $uploadDir . $uploadedFileName;

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    if (move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)) {

        // Создаем новый ZIP-архив
        $zip = new ZipArchive();
        $zipFileName = $uploadDir . pathinfo($uploadedFileName, PATHINFO_FILENAME) . ".zip";

        // Открываем архив. Если файла не существует, он будет создан.
        if ($zip->open($zipFileName, ZipArchive::CREATE) === TRUE) {
            // Добавляем файл в архив с максимальным сжатием
            $zip->addFile($uploadFile, $uploadedFileName);
            $zip->setCompressionName($uploadedFileName, ZipArchive::CM_REDUCE_4);

            // Закрываем архив
            $zip->close();
            
            unlink($uploadFile);

            $connect = mysqli_connect("localhost","root","","test");

            if (!$connect) {
                die("Erroe Connect to DataBase");
            }

            $link = generateRandomString(24);
            $date = date('Y-m-d H:i:s');
            include('db.php');
            $stmt = $pdo->prepare("INSERT INTO files (ip, link, `path`, `date`) VALUES (:ip, :link, :path, :date)");
            $stmt->bindParam(':ip', $_SERVER['REMOTE_ADDR']);
            $stmt->bindParam(':link', $link);
            $stmt->bindParam(':path', $zipFileName);
            $stmt->bindParam(':date', $date);
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
            // Заменяем обратные слеши на обычные
            $directory = str_replace('\\', '/', dirname($_SERVER['REQUEST_URI']));
            $baseURL = $protocol . $_SERVER['HTTP_HOST'] . $directory;
            $fullURL = rtrim($baseURL, '/') . '/' . $link; // Убедитесь, что нет двойных слэшей
            echo $fullURL;
        } else {
            echo ' Не удалось создать архив.';
        }
    } else {
        echo 'Возможная атака с помощью файловой загрузки!';
    }
} else {
    echo "Файл не был загружен.";
}
