<?php
// Heading
$_['heading_title'] = 'AliKassa';

// Text 
$_['text_enabled']  = 'Включено';
$_['text_disabled'] = 'Отключено';
$_['text_payment']  = 'Оплата';
$_['text_edit']     = 'Изменить AliKassa';
$_['text_success']  = 'Настройки модуля обновлены!';

// Entry
$_['entry_merchant']      = 'Идентификатор магазина';
$_['entry_security']      = 'Секретный ключ';
$_['entry_security_type'] = 'Способ фомирования подписи';
$_['entry_security_sign'] = 'Проверять подпись в форме запроса платежа';
$_['entry_variants']      = 'Вариант оплаты по умолчанию';
$_['entry_geo_zone']      = 'Географическая зона';
$_['entry_status']        = 'Статус';
$_['entry_order_wait']    = 'Статус ожидания оплаты';
$_['entry_order_success'] = 'Статус успешной оплаты';
$_['entry_order_fail']    = 'Статус неудачной оплаты';
$_['entry_sort_order']    = 'Порядок сортировки';
$_['entry_log']           = 'Путь к журналу транзакций';
$_['entry_admin_email']   = 'E-mail для оповещения об ошибках';
$_['entry_test']          = 'Тестовый режим';

// Help
$_['help_merchant']      = 'Идентификатор сайта, зарегистрированного в платежной системе AliKassa';
$_['help_security']      = 'Должен совпадать с секретным ключем, указанным в личном кабинете AliKassa';
$_['help_security_type'] = 'Должен совпадать с типом, указанным в личном кабинете AliKassa';
$_['help_log']           = 'Путь к файлу, где будет сохраняться вся история оплаты в AliKassa';
$_['help_admin_email']   = 'E-mail администратора для оповещения об ошибках оплаты';

// Error
$_['error_permission'] = 'У Вас нет прав для управления этим модулем!';
$_['error_merchant']   = 'Необходимо указать идентификатор сайта!';
$_['error_security']   = 'Необходимо указать секретный код!';