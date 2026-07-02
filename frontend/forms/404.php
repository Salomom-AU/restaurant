<?php

include __DIR__ . '/../../frontend/forms/header.php';
?>

<div class="w-full min-h-150 flex items-center justify-center">
    <div class="w-full flex flex-col items-center justify-center max-w-150 p-10 gap-5 rounded-box">
        <div class="">
            <img src="/restaurant/frontend/asset/404Page.svg" class="w-full h-full" alt="">

        </div>
        <div class="text-wrap text-center w-full">
            <p>
                Page introuvable - La page que vous recherchez n'existe pas ou a été déplacée. Veuillez vous connecter pour accéder à votre espace personnel.
            </p>
        </div>
        <div class="w-full justify-center items-center flex gap-5">
            <input type="submit" class="btn btn-primary" value="connexion" onclick="window.location.href='/restaurant/frontend/forms/connexion.php'">
            <input type="submit" class="btn " value="register" onclick="window.location.href='/restaurant/frontend/forms/register.php'">
        </div>

    </div>

</div>
<?php
include __DIR__ . '/../../frontend/forms/footer.php';
?>