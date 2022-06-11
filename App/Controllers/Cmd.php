<?php

namespace App\Controllers;

class Cmd
{
    public static function cmd_list() {
        return [
            "💬 Меню:" .
            "\n".
            "\n".
            "!статистика - посмотреть статистику сервера" .
            "\n".
            "!команды - посмотреть команды бота" .
            "\n".
            "!fid - посмотреть ид фракций".
            "\n" .
            "!мемберс {id fraction} - посмотреть мемберс фракций" .
            "\n" .
            "!лидеры - статистика лидеров" .
            "\n" .
            "!админы - статистика админов" .
            "\n" .
            "!аккаунт {nickname} - статистика игрока" .
            "\n" .
            "!createpromocode {namepromocode} {owner} - создать промокод" .
            "\n" .
            "!deletepromocode {namepromocode} - удалить промокод" .
            "\n" .
            "!checkpromo {namepromocode} - посмотреть статику промокода" .
            "\n" .
            "!editpromo {namepromocode} {newowner} - изменить владельца промокода" .
            "\n" .
            "!resetsecurity {name} - снять гугл-аунт с аккаунта" .
            "\n" .
            "!changepassword {name} - сменить пароль с аккаунта"
        ];
    }
}