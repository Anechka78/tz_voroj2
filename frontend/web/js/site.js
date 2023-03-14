/**
 * Добавление разметки для работы функции сообщений
 */
addServiceMarkup = function () {
    var markup = '<div style="display: none;" id="message_wrapper" class="message_wrapper"><div id="message_body" class="message_body alert"></div> </div> <div id="blocker-screen" style="display: none"> <div class="blocker-screen-text"></div> <div class="blocker-screen-backdrop"></div></div>';
    $('body').append(markup);
};

addServiceMarkup();

// Общая переменная для бинда таймаута скрытия сообщения
var message_life;

// Общая переменная для текущего класса сообщения (чтобы можно было почистить)
var current_alert_class = '';

/**
 * Скрывает сообщение
 */
function hideMessage() {
    $('#ajax_indicator').hide();
    $('#message_body').removeClass(current_alert_class);
    $('#message_wrapper').hide();
}

/**
 * Показывает сообщение системы
 * @param type - тип сообщения (ошибка, успех или предупреждение)
 * @param text - текст сообщения
 */
function message(type, text) {

    // первым делом чистим таймаут скрытия и
    // немедленно скрываем предыдущее сообщение на всякий случай
    clearTimeout(message_life);

    hideMessage();


    // для стилизации сообщения используются классы Twitter Bootstrap
    var types = {
        error: 'alert-danger',
        success: 'alert-success'
    };
    // Записываем текущий класс в общую переменную
    current_alert_class = types[type];

    // Показываем сообщение
    $('#message_body').html(text).addClass(types[type]);
    var message_wrapper = $('#message_wrapper');
    message_wrapper.show();

    // Ставим таймаут на скрытие сообщения
    message_life = setTimeout(function () {
        hideMessage();
    }, 7500);

    message_wrapper.hover(function () {
        // Убираем таймаут на скрытие, если мышка наведена на сообщение
        clearTimeout(message_life);
    });

    message_wrapper.mouseleave(function () {
        // Ставим таймаут на скрытие сообщения
        message_life = setTimeout(function () {
            hideMessage();
        }, 2000);
    });
}

