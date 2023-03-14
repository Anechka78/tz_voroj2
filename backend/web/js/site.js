$(document).on("click", "a.pjax-modal", function () {
    openPjaxModal($(this).data('container'), $(this).attr('href'));

    return false;
});

openPjaxModal = function (container_selector, url) {
    if (!container_selector) {
        throw new Error('Необходимо указать атрибут data-container. Например #my_pjax_container. Контейнер должен быть уникальным.');
    }
    var el = $(container_selector);

    if (!el.length) {
        $('body').append('<div id="' + container_selector.replace('#', '') + '"></div>');
    }
    var reload_selector = container_selector;

    var modal = $(container_selector + ' .modal');

    if (modal.length) {
        reload_selector = '#' + modal.find('[data-pjax-container]').attr('id');
    }

    $.pjax.reload(reload_selector, {'url': url, 'push': false, 'replace': false, 'timeout': 10000}).done(function () {
        $(container_selector + ' .modal').modal('show');
    });

    return false;
};

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

//$("body").find("#view-data .pjax-delete").on("click", function (e) {
$("body").on("click","#view-data .pjax-delete", function (e) {

    console.log('pjax-delete');
    e.preventDefault();
    var href = $(this).attr("href");
    console.log(href);

    $.ajax({
        url: href,
        type: 'GET',
        cache: false,
        dataType: 'json',
        success: function(resp) {
            message("success", resp.message);
            $.pjax.reload({container: '#view-data', url:'/data/view/', timeout: 500,replace: 0});
        },
        error: function(xhr, status, error) {
            console.log('Request failed.  Returned status of ' + xhr.status);
            message('error', xhr.responseText);
        }

    });

});



