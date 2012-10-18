<?php

UserService::getInstance()->logoutUser();

header('Location: '.$app->urlRoot());
exit();