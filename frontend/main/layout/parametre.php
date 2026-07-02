<?php
$email = $_SESSION['user_email'] ;
$nom = $_SESSION['user_name'];
$pseudo = $_SESSION['user_username'] ;
@$telephone =  $_SESSION['user_telephone'] ;
$profile = substr($nom , 0 , 2 ) ;  



?>

<div class="p-10">
        <div class="card bg-base-200">
        <div class="card-body">
            <div class="flex flex-col md:flex-row items-center gap-8">
                <div class="avatar">
                    <div class="w-40 h-40 rounded-full ring ring-info ring-offset-2 ring-offset-base-200">
                        <div class="w-full h-full bg-info rounded-full  flex items-center justify-center text-6xl text-white font-bold">
                            <?php echo $profile ?>
                        </div>
                    </div>
                </div>

                <div class="flex-1 text-center md:text-left">
                    <h1 class="text-4xl font-bold text-info"><?php echo $nom ?></h1>
                    <p class="text-lg text-base-content/70"><?php echo '@' . $pseudo ?></p>
                    <div class="flex flex-wrap gap-4 mt-3 justify-center md:justify-start">
                        <div class="badge badge-success badge-lg gap-2">
                            <i class="fas fa-circle-check"></i>
                            Actif
                        </div>
                        <div class="badge badge-info badge-lg gap-2">
                            <i class="fas fa-envelope"></i>
                            <?php echo $email ?>
                        </div>
                        <div class="badge badge-info badge-lg gap-2">
                            <i class="fas fa-phone"></i>
                            <?php echo $telephone ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</div>