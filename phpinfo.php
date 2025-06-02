if ($user) {
    $initial = strtoupper(substr($user['username'], 0, 1));
    return [
        'initial' => $initial,
        'color' => $user['avatar_color']
    ];
}