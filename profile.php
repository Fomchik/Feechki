<?php
require_once 'includes/config.php';
require_once 'includes/auth_functions.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

checkRememberCookie($pdo);
$isLoggedIn = is_logged_in();

function getInitial($username)
{
    return mb_substr($username, 0, 1, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Профиль</title>
    <link rel="stylesheet" href="/assets/css/header.css">
    <link rel="stylesheet" href="/assets/css/auth_modal.css">
    <link rel="stylesheet" href="/assets/css/user-dropdown.css">
    <link rel="stylesheet" href="/assets/css/profile_style.css">
    <link rel="stylesheet" href="/assets/css/footer_style.css">
</head>

<body>
    <?php include 'includes/header.php'; ?>
    <main>
        <section class="profile-section">
            <div class="profile-tabs">
                <button class="tab active" data-tab="appointments">
                    <img src="assets/icons/viol-calendar.svg" alt="Календарь">
                    <span>Записи на прием</span>
                </button>
                <button class="tab" data-tab="history">
                    <img src="assets/icons/viol-clock.svg" alt="Часы">
                    <span>История посещения</span>
                </button>
                <button class="tab" data-tab="personal">
                    <img src="assets/icons/viol-user.svg" alt="Пользователь">
                    <span>Личные данные</span>
                </button>
            </div>

            <div id="tab-content">
                <!-- Содержимое вкладки "Записи на прием" (по умолчанию активная) -->
                <div id="appointments" class="tab-pane active">
                    <h2>Управление записями на прием</h2>

                    <div class="data-table">
                        <div class="table-header">
                            <div class="cell">Дата</div>
                            <div class="cell">Время</div>
                            <div class="cell">Врач</div>
                            <div class="cell">Действия</div>
                        </div>

                        <div class="table-body">
                            <?php
                            $user_id = $_SESSION['user_id'] ?? null;
                            if ($user_id) {
                                $stmt = $pdo->prepare('SELECT a.id, a.appointment_date, a.appointment_time, d.full_name as doctor_name FROM appointments a JOIN doctors d ON a.doctor_id = d.id WHERE a.user_id = ? AND a.status = "scheduled" ORDER BY a.appointment_date, a.appointment_time');
                                $stmt->execute([$user_id]);
                                $appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($appointments as $appointment) {
                                    echo '<div class="table-row">';
                                    echo '<div class="cell">' . date('d.m.y', strtotime($appointment['appointment_date'])) . '</div>';
                                    echo '<div class="cell">' . substr($appointment['appointment_time'], 0, 5) . '</div>';
                                    echo '<div class="cell doctor-name">' . htmlspecialchars($appointment['doctor_name']) . '</div>';
                                    echo '<div class="cell actions"><button class="cancel-button" data-id="' . $appointment['id'] . '">Отменить</button></div>';
                                    echo '</div>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <!-- Содержимое вкладки "История посещения" -->
                <div id="history" class="tab-pane">
                    <h2>История посещения</h2>

                    <div class="data-table">
                        <div class="table-header">
                            <div class="cell">Дата</div>
                            <div class="cell">Время</div>
                            <div class="cell">Врач</div>
                            <div class="cell">Услуга</div>
                        </div>

                        <div class="table-body">
                            <?php
                            if ($user_id) {
                                $stmt = $pdo->prepare('SELECT v.visit_date, v.visit_time, d.full_name as doctor_name, s.name as service_name FROM visit_history v JOIN doctors d ON v.doctor_id = d.id LEFT JOIN services s ON v.service_id = s.id WHERE v.user_id = ? ORDER BY v.visit_date DESC, v.visit_time DESC');
                                $stmt->execute([$user_id]);
                                $history = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                foreach ($history as $visit) {
                                    echo '<div class="table-row">';
                                    echo '<div class="cell">' . date('d.m.y', strtotime($visit['visit_date'])) . '</div>';
                                    echo '<div class="cell">' . substr($visit['visit_time'], 0, 5) . '</div>';
                                    echo '<div class="cell doctor-name">' . htmlspecialchars($visit['doctor_name']) . '</div>';
                                    echo '<div class="cell">' . htmlspecialchars($visit['service_name']) . '</div>';
                                    echo '</div>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <!-- Содержимое вкладки "Личные данные" -->
                <div id="personal" class="tab-pane">
                    <h2>Личные данные</h2>
                    <?php
                    $user_id = $_SESSION['user_id'] ?? null;
                    $user_data = [
                        'fullname' => '',
                        'email' => '',
                        'phone' => '',
                        'birth_date' => ''
                    ];
                    if ($user_id) {
                        $stmt = $pdo->prepare('SELECT email FROM users WHERE id = ?');
                        $stmt->execute([$user_id]);
                        $user = $stmt->fetch(PDO::FETCH_ASSOC);
                        if ($user) {
                            $user_data['email'] = $user['email'];
                        }
                        $stmt = $pdo->prepare('SELECT full_name, phone, birth_date FROM user_details WHERE user_id = ?');
                        $stmt->execute([$user_id]);
                        $details = $stmt->fetch(PDO::FETCH_ASSOC);
                        if ($details) {
                            $user_data['fullname'] = $details['full_name'];
                            $user_data['phone'] = $details['phone'];
                            $user_data['birth_date'] = $details['birth_date'] ? $details['birth_date'] : '';
                        }
                    }
                    ?>
                    <form id="personal-data-form" autocomplete="off">
                        <div class="personal-data-view">
                            <div class="personal-row">
                                <div class="personal-label">Имя</div>
                                <input class="personal-value" type="text" name="fullname" value="<?php echo htmlspecialchars($user_data['fullname']); ?>" required>
                            </div>
                            <div class="personal-row">
                                <div class="personal-label">Дата рождения</div>
                                <input class="personal-value" type="date" name="birth_date" value="<?php echo htmlspecialchars($user_data['birth_date']); ?>">
                            </div>
                            <div class="personal-row">
                                <div class="personal-label">Телефон</div>
                                <input class="personal-value" type="tel" name="phone" value="<?php echo htmlspecialchars($user_data['phone']); ?>">
                            </div>
                            <div class="personal-row">
                                <div class="personal-label">Email</div>
                                <input class="personal-value" type="email" name="email" value="<?php echo htmlspecialchars($user_data['email']); ?>" required>
                            </div>
                        </div>
                        <div style="display: flex; justify-content: center; margin-top: 24px;">
                            <button type="submit" class="save-button" id="save-personal-btn" disabled>Сохранить изменения</button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
        <script src="/assets/js/profile.js"></script>
        <?php include 'includes/footer.php'; ?>

</body>

</html>