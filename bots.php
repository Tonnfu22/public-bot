<?php
    $connect = mysqli_connect('db4.myarena.ru','u25384_emerald2022','4R7u1J0x5W','u25384_emerald2022'); // connect bd

    require_once __DIR__.'/vendor/digitalstars/simplevk/autoload.php';
    use DigitalStars\SimpleVK\{Bot, SimpleVK as vk};

    $token = 'f49c268d42807f29cc103a6530f642ff24972e5f8307785c1add136bd7a2f3a05ca6a5fcee44386573f50';
    $version = '5.126';
    $confirm = 'e753886a';

    $vk = vk::create($token, $version)->setConfirm($confirm);  // vk api
    $bot = Bot::create($token, $version); // vk api
    
    $vk->setUserLogError('582127671');
    $data = $vk->initVars($peer_id, $user_id, $type, $message);

    if(!$connect) {
        $vk->reply('Ошибка подключение к БД'); // check connect bd
    }



    if($type == 'message_new') {
        if($message == '!команды') { // cmd 
            $vk->reply('
                Доступные команды 
                - !админы
                - !лидеры
                - !онлайн
                - !прокачать {nickname}
                - !профиль {nickname}
            ');
        }
        if($message == '!админы') { // cmd
            $admin = mysqli_query($connect, "SELECT * FROM `s_admin`"); // connecting to the admin database

            while ($row = mysqli_fetch_assoc($admin)) {
                $vk->reply('
                    НикНейм: '.$row['Name'].'
                    Последний заход: '.date('Y-m-d', $row['LastCon']).'
                    Отвечено репортов: '.$row['s_Reports'].'

                    Выдано блокировок: '.$row['s_Bans'].'
                    Выдано варнов: '.$row['s_Warns'].'
                    Выдано мутов: '.$row['s_Mutes'].'
                    Выдано дмг: '.$row['s_Prisons'].'
                ');
            }
        }

        if($message == '!лидеры') {
            $leaders = mysqli_query($connect, "SELECT * FROM `s_fraction`");

            $vk->reply('‼ Показаны только те лидеры, которых поставили! ‼');

            while ($row = mysqli_fetch_assoc($leaders)) {
                if($row['fLeader'] != 'None'){
                    $vk->reply('
                        НикНейм: '.$row['fLeader'].'
                        Заместитель: '.$row['fAssistant'].'
                        Сообщение при заходе: '.$row['fMessage'].'
                    ');
                }
            }
        }

        if($message == '!онлайн') {
            $online = mysqli_query($connect, "SELECT count(*) FROM `s_users` WHERE `pLogin` = 1");
            $online = mysqli_fetch_row($online);

            $vk->reply('Онлайн на данный момент: '. $online[0] .' человек');
        }

    }

    $bot->preg_cmd('update', '!\!прокачать (.*)!')->func(function ($msg, $params) use ($connect){
        $account = mysqli_query($connect, "SELECT * FROM `s_users` WHERE `Name` = '$params[1]'");
        $account = mysqli_fetch_array($account);

        if($account) {
            $money = $account['pCash'] + 30000;
            $mats = $account['pDrug'] + 30000;
            $drugs = $account['pDrug'] + 30000;

            mysqli_query($connect, "
                UPDATE `s_users` SET `pMats`= $mats,`pCash`= $money,`pDrug`= $drugs WHERE `Name` = '$params[1]'
            ");

            $msg->text("Аккаунт $params[1] успешно прокачен");
        } else {
            $msg->text("‼ Аккаунт не найден");
        }
    });

    $bot->preg_cmd('info', '!\!профиль (.*)!')->func(function ($msg, $params) use ($connect) {

        $account = mysqli_query($connect, "SELECT * FROM `s_users` WHERE `Name` = '$params[1]'");
        $account = mysqli_fetch_array($account);

        $house = mysqli_query($connect, "SELECT * FROM `house` WHERE `hOwner` = '$params[1]'");
        $house = mysqli_fetch_array($house);

        if($house) {
            $dom = "
                Номер дома: $house[hID]
                Класс: $house[hKlass]";
        } else {
            $dom = "Дом: отсуствует";
        }

        if($house) {
            
        }

        if($account) {
            $msg->text('
                🙍‍♂ НикНейм: '.$params[1].'
                Уровень: '.$account['pLevel'].'
                
                💸 Средства              
                Деньги на руках: '.number_format($account['pCash']).' $
                На депозите: '.number_format($account['pDeposit']).'
                В банке: '.number_format($account['pBank']).'
                Баланс аккаунта: '.number_format($account['u_donate']).'
                
                🏛 Недвижимость            
                '. $dom .'
                Бизнес:
                Ферма:
                Машина:
                
                🗿 Остальное
                Наркотики: '.number_format($account['pDrugs']).' шт
                Материаллы: '.number_format($account['pMats']).' шт
                Фишек казино: шт
                VIP-Статус:
                Варны: 
                Реферальный аккаунт:
            ');
        } else {
            $msg->text("‼ Аккаунт не найден");
        }
    });

    $bot->run();