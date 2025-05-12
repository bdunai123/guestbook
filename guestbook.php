<?php
session_start();

// Шаг 3: Обработка POST-запроса
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Очистка и фильтрация данных
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $text = htmlspecialchars(trim($_POST['text']));

    if (!empty($name) && !empty($email) && !empty($text)) {
        $comment = [
            'name' => $name,
            'email' => $email,
            'text' => $text,
            'date' => date('Y-m-d H:i:s')
        ];

        // Сохраняем в JSON-строке (одна строка — один комментарий)
        $jsonData = json_encode($comment);
        file_put_contents('comments.csv', $jsonData . PHP_EOL, FILE_APPEND);
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Гостевая книга</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Подключение Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<div class="container mt-4">

    <!-- Форма -->
    <div class="card">
        <div class="card-header bg-primary text-white">Форма гостевой книги</div>
        <div class="card-body">
            <form method="post" action="guestbook.php">
                <div class="mb-3">
                    <label for="name" class="form-label">Имя:</label>
                    <input type="text" id="name" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" id="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="text" class="form-label">Сообщение:</label>
                    <textarea id="text" name="text" class="form-control" rows="4" required></textarea>
                </div>
                <button type="submit" class="btn btn-success">Отправить</button>
            </form>
        </div>
    </div>

    <!-- Комментарии -->
    <div class="card mt-4">
        <div class="card-header bg-secondary text-white">Комментарии</div>
        <div class="card-body">
            <?php
            if (file_exists('comments.csv')) {
                $lines = file('comments.csv', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                foreach ($lines as $line) {
                    $comment = json_decode($line, true);
                    if ($comment) {
                        echo '<div class="mb-3 border-bottom pb-2">';
                        echo '<strong>' . $comment['name'] . '</strong> (' . $comment['email'] . ')<br>';
                        echo '<small class="text-muted">' . $comment['date'] . '</small><br>';
                        echo '<p>' . nl2br($comment['text']) . '</p>';
                        echo '</div>';
                    }
                }
            } else {
                echo 'Пока нет комментариев.';
            }
            ?>
        </div>
    </div>

</div>

</body>
</html>
