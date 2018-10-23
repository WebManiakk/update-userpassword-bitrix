<?
global $USER;

if(strlen($_REQUEST["oldpass"]) || strlen($_REQUEST["newpass"]) || strlen($_REQUEST["confirm"])){
    //проверка старого пароля
    $arUser["PASSWORD"] = $USER->GetParam("PASSWORD_HASH");
    $pass["PASSWORD"] = $_REQUEST["oldpass"];

    if(strlen($arUser["PASSWORD"]) > 32)
    {
        $salt = substr($arUser["PASSWORD"], 0, strlen($arUser["PASSWORD"]) - 32);
        $db_password = substr($arUser["PASSWORD"], -32);
    }
    else
    {
        $salt = "";
        $db_password = $arUser["PASSWORD"];
    }

    $user_password =  md5($salt.$pass["PASSWORD"]);

    //проверка старого пароля и создание нового
    if($db_password === $user_password){
        $arResult["ID"] = intval($USER->GetID());

        $obUser = new CUser;

        $arFields["PASSWORD"] = $_REQUEST["newpass"];
        $arFields["CONFIRM_PASSWORD"] = $_REQUEST["confirm"];

        if($obUser->Update($arResult["ID"], $arFields)){
            $result['done'] = 'Пароль успешно изменен';
        }else{
            $result['error'] = $obUser->LAST_ERROR;
        }

    }else{
        $result['error'] = 'Неверный старый пароль';
    }
}else{
    $result['error'] = 'Для смены пароля заполните все пустые поля';
}


exit(json_encode($result));
