<?php
session_start();
session_unset();
session_destroy();
header("Location: signin.php");
exit;

<?php if (isset($_GET['logout'])): ?>
    <p style="color: green;">Vous avez été déconnecté avec succès.</p>
<?php endif; ?>
