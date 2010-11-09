<?php
    echo $form->open('/user/process_login'),
        $form->text('username'),
        $form->password('password'),
        $form->submit('Log in'),
        $form->close();
?>
