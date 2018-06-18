(function ($) {
    'use strict';
    $(function () {
        /**
         * Анимация
         */
        (function () {
            $('h2').viewportChecker({
                classToAdd: 'fadeInDown animated',
                removeClassAfterAnimation: true
            });

            $('.benefit').viewportChecker({
                classToAdd: 'flipInX animated',
                removeClassAfterAnimation: true
            });
        })();

        /**
         * Ajax страницы
         */
        (function () {
            $('.square a, .footer ul a').fancybox({
                defaultType: 'ajax'
            });
        })();

        /**
         * Checkboxes
         */
        (function () {
            $('input[type="checkbox"]').iCheck({
                checkboxClass: 'icheckbox_minimal'
            });
        })();

        /**
         * Всплывающие подсказки
         */
        (function () {
            $('[data-toggle="tooltip"]').tooltip();
            // Показать ошибки
            $('.error[data-toggle="tooltip"]').tooltip('show');
            // Убрать ошибки при изменении значения поля
            $('.error input, .error select').on('change', function () {
                $(this).parents('.error').removeClass('error');
                $(this).parents('[data-toggle="tooltip"]').tooltip('hide');
            });
            // При изменении размеров окна
            $(window).on('resize', function () {
                // Спрятать подсказки и отобразить ошибки заново
                $('[data-toggle="tooltip"]').tooltip('hide');
                $('.error[data-toggle="tooltip"]').tooltip('show');
            });
        })();

        /**
         * Формы заказа
         */
        (function () {
            // Формы (первый и второй шаг)
            var $form = $('#order-form-1, #order-form-2');

            /**
             *  Округление
             *
             *  @param value - значение
             *  @param decimals - кол-во знаков после запятой
             */
            var round = function (value, decimals) {
                decimals = decimals || 0;
                var multiplier = Math.pow(10, decimals);
                return Math.round(value * multiplier) / multiplier;
            };

            /**
             * Показать/скрыть элемент в зависимости от значения
             *
             * @param $element - элемент
             * @param value - значение
             * @param hiddenClass - класс для скрытия элемента
             */
            var showHide = function ($element, value, hiddenClass) {
                hiddenClass = hiddenClass || 'display-none';
                if (value) {
                    $element.removeClass(hiddenClass);
                } else {
                    $element.addClass(hiddenClass);
                }
            };

            /**
             * Изменение объема заказанной услуги в калькуляторе
             *
             * @param serviceId - id услуги
             * @param value - количество
             * @param valueWithUnit - количество без единицы измерения
             */
            var syncCalc = function (serviceId, value, valueWithUnit) {
                // Дополнительные услуги в калькуляторе
                var $additionalServices = $('#additional-services');
                var $calcRows = $additionalServices.children('p');

                // Поиск соответствующей услуги и её цены
                var $calcRow = $calcRows.filter('[data-id="' + serviceId + '"]');
                var price = parseFloat($calcRow.data('price'));

                // Установка актуального количества
                $calcRow.data('count', value);
                $calcRow.find('.count').text('(' + valueWithUnit + ')');
                // Расчет стоимости услуги с учетом количества
                $calcRow.find('.price').text(round(price * value, 2));

                // Показать/спрятать элемент в зависимости от количества
                showHide($calcRow, value > 0);
                // Показать/спрятать блок дополнительных услуг
                showHide($additionalServices, $calcRows.not('.display-none').length);

                // Пересчитать итоговую стоимость заказа
                calcTotal();
            };

            /**
             * Расчет итоговой стоимости заказа
             */
            var calcTotal = function () {
                // Дополнительные услуги в калькуляторе
                var $additionalServices = $('#additional-services');
                var $calcRows = $additionalServices.children('p');
                // Элемент для итоговой стоимости
                var $priceStandard = $('#price-standard');
                var $priceTotal = $('#price-total');
                // Базовая стоимость заказа
                var baseCost = parseFloat($('#calc').data('base-cost'));
                // Стоимость базовых услуг, стоимость дополнительный, полная стоимость, стоимость со скидкой
                var totalBase, totalAdditional, total, totalWithDiscount;

                // Кол-во и цена базовых услуг (комнаты и с/у)
                var $countRoom = $('#count-room');
                var $countBathoom = $('#count-bathroom');
                var priceRoom = $countRoom.data('price');
                var countRoom = $countRoom.data('count');
                var priceBathroom = $countBathoom.data('price');
                var countBathroom = $countBathoom.data('count');
                // Расчет стоимости базовых услуг
                totalBase = round(priceRoom * countRoom, 2) + round(priceBathroom * countBathroom, 2);

                // Расчет стоимости дополнительных услуг
                totalAdditional = 0;
                $calcRows.each(function () {
                    var price = parseFloat($(this).data('price'));
                    var count = $(this).data('count');
                    totalAdditional += round(price * count, 2);
                });

                // Расчет скидки
                var discountFrequency = parseFloat($('#discount-frequency').data('discount')) || 0;
                var discountPromocode = parseFloat($('#discount-promocode').data('discount')) || 0;
                var discountTotal = round(discountFrequency + discountPromocode, 2);
                var multiplier = (100 - discountTotal) / 100;

                // Расчет итоговой стоимости
                total = round((baseCost + totalBase + totalAdditional), 2);
                totalWithDiscount = round(total * multiplier, 2);
                $priceStandard.text(total);
                $priceTotal.text(totalWithDiscount);
            };

            /**
             * Синхронизация значений из select в input
             *
             * @param $select - jQuery коллекция элементов select
             */
            var syncValues = function ($select) {
                $select.each(function () {
                    // Соответствующий input
                    var $input = $(this).siblings('input');
                    // Блок услуги (отсутствует на первом шаге)
                    var $service = $(this).parents('.service');
                    // Значение в select
                    var value = $(this).val();
                    var valueWithUnit = $(this).find('option:selected').text();
                    // Запись значения с единицами в input
                    $input.val(valueWithUnit);

                    // Если есть блок услуги, выделить/снять выделение и синхронизировать с калькулятором
                    if ($service.length) {
                        value > 0 ? $service.addClass('selected') : $service.removeClass('selected');
                        syncCalc($(this).data('id'), value, valueWithUnit);
                    }
                });
            };
            // Синхронизация при загрузке страницы
            syncValues($form.find('select'));

            /**
             * Синхронизация скидок
             */
            var syncDiscount = function () {
                // Блок скидок в калькуляторе
                var $discountBlock = $('#discount-block');
                var $discountFrequency = $('#discount-frequency');
                var $discountPromocode = $('#discount-promocode');
                // Скидка за регулярность
                var discountFrequency = $form.find('.frequency.selected').data('discount') || 0;
                // Скидка по промокоду
                var discountPromocode = $('#promocode-block').data('discount') || 0;

                // Обновить значения в калькуляторе
                $discountFrequency.data('discount', discountFrequency)
                    .find('.percent').text(discountFrequency);
                $discountPromocode.data('discount', discountPromocode)
                    .find('.percent').text(discountPromocode);

                // Показать/скрыть скидки в калькуляторе
                showHide($discountFrequency, discountFrequency);
                showHide($discountPromocode, discountPromocode);
                showHide($discountBlock, discountFrequency || discountPromocode);

                // Пересчитать итоговую стоимость заказа
                calcTotal();
            };
            // Синхронизация при загрузке страницы
            syncDiscount();

            /**
             * Кнопки плюс/минус
             */
            $form.find('.minus, .plus').on('click', function () {
                // Соответствующий select
                var $select = $(this).siblings('select');
                var $option = $select.find('option:selected');
                // Текущее значение в select
                var value = $option.val();

                // Определение нового значения
                if ($(this).hasClass('plus')) {
                    value = $option.next().val() || value;
                } else {
                    value = $option.prev().val() || value;
                }

                // Установка нового значения в select
                $select.val(value).trigger('change');
                // Синхронизация значения из select в input
                syncValues($select);
            });

            /**
             * Запрет распространения события клика
             * при нажатии на плюс/минус
             * в дополнительных услугах
             */
            $form.find('.service .input-wrapper').on('click', function (e) {
                e.stopPropagation();
            });

            /**
             * Обработка клика на дополнительных услугах
             */
            $form.find('.service').on('click', function () {
                // Соответствующий select
                var $select = $(this).find('select');
                var $option = $select.find('option:selected');
                // Текущее значение в select
                var value = $option.val();

                // Определение нового значения
                if (value > 0) {
                    value = 0;
                } else {
                    value = $option.next().val() || value;
                }

                // Установка нового значения в select
                $select.val(value).trigger('change');
                // Синхронизация значения из select в input
                syncValues($select);
            });

            /**
             * Периодичность услуг
             */
            $form.find('.frequency').on('click', function () {
                // Снятие выделения со всех вариантов
                $form.find('.frequency').removeClass('selected')
                    .find('input[type="radio"]').removeAttr('checked');
                // Выделение соответствующей периодичности
                $(this).addClass('selected')
                    .find('input[type="radio"]').prop('checked', true).trigger('change');

                // Синхронизация 
                syncDiscount();
            });

            /**
             * Выбор улицы
             */
            (function () {
                var $inputCity = $('#order_city'),
                    $inputStreet = $('#order_street'),
                    $parent = $inputStreet.parents('.input-wrapper').css('position', 'relative'),
                    source = $inputStreet.data('source');

                $inputStreet.autocomplete({
                    serviceUrl: source,
                    minChars: 2,
                    appendTo: $parent,
                    onSearchStart: function () {
                        // Производить поиск только если город установлен по умолчанию
                        return $inputCity.val().toUpperCase() === $inputCity.data('default').toUpperCase();
                    },
                    beforeRender: function (container) {
                        container.show(200);
                    }
                });
            })();

            /**
             * Календарь для выбора даты и времени уборки
             */
            $.datetimepicker.setLocale('ru');
            var $dateInput = $form.find('.date-input');
            $dateInput.datetimepicker({
                todayButton: true,
                closeOnDateSelect: false,
                mask: false,
                format: 'd.m.Y H:i',
                formatDate: 'd.m.Y',
                formatTime: 'H:i',
                maxDate: $dateInput.data('max-date'),
                minDate: $dateInput.data('min-date'),
                // minTime: $dateInput.data('min-time'), // Не работает
                // maxTime: $dateInput.data('max-time'), // Не работает
                // step: 60,                             // Не работает
                allowTimes: $dateInput.data('allow-times'),
                defaultTime: '12:00',
                dayOfWeekStart: 1, // Понедельник
                // disabledWeekDays: [0, 6], // Выходные дни
                scrollMonth: false
            });
            $form.find('.calendar').on('click', function () {
                $form.find('.date-input').datetimepicker('show');
            });

            /**
             * Проверка промокода
             */
            (function () {
                var $block = $('#promocode-block'),
                    $btnCheck = $('#order_check'),
                    $btnClear = $('#order_clear'),
                    $inputPromocode = $('#order_promocode'),
                    $parent = $inputPromocode.parents('.input-wrapper');

                // Спрятать подсказку при вводе
                $inputPromocode.on('keydown', function (e) {
                    $parent.tooltip('hide');
                    $parent.removeClass('error');
                    if (e.keyCode === 13) {
                        e.preventDefault();
                        $btnCheck.triggerHandler('click');
                    }
                });

                // При нажатии на кнопку проверки
                $btnCheck.on('click', function () {
                    // Если правильный код уже введен либо запрос уже отправлен - выход
                    if ($btnCheck.hasClass('success') || $btnCheck.hasClass('loading')) {
                        return;
                    }

                    $parent.tooltip('destroy');

                    // Текущее значение
                    var value = $inputPromocode.val();
                    // Показать подсказку если ничего не введено
                    if (!value) {
                        $parent.tooltip({title: 'Введите промокод'}).tooltip('show');
                        return;
                    }

                    // Отображение загрузки
                    $btnCheck.addClass('loading');
                    $parent.removeClass('success').removeClass('error');
                    // Запрос
                    $.post($block.data('check-url'), {promocode: value}, function (data) {
                        if (data.status === 'success' && typeof(data.discount) !== 'undefined') {
                            // Блокировка элементов
                            $parent.addClass('success');
                            $btnCheck.addClass('success').attr('disabled', true);
                            $inputPromocode.attr('readonly', true);
                            // Обновить скидку
                            $block.data('discount', data.discount);
                            syncDiscount();
                        } else if(data.status === 'error' && typeof(data.message) !== 'undefined') {
                            $parent.addClass('error');
                            $parent.tooltip({title: data.message}).tooltip('show');
                        }
                    }, 'json').always(function () {
                        // Загрузка завершена
                        $btnCheck.removeClass('loading');
                    });
                });

                // При нажатии на кнопку сброса промокода
                $btnClear.on('click', function () {
                    // Отображение загрузки
                    $btnCheck.addClass('loading');
                    $.post($block.data('clear-url'), function (data) {
                        if (data.status === 'success' && typeof(data.discount) !== 'undefined') {
                            // Возврат в первоначальное состояние
                            $parent.removeClass('success');
                            $btnCheck.removeClass('success').attr('disabled', false);
                            $inputPromocode.attr('readonly', false).val('');
                            // Обновить скидку
                            $block.data('discount', data.discount);
                            syncDiscount();
                        }
                    }).always(function () {
                        // Загрузка завершена
                        $btnCheck.removeClass('loading');
                    });
                });
            })();

            /**
             * Сообщение о добавлении заказа
             */
            (function () {
                var $message = $('#order-message'),
                    $redirect = $message.find('.close-popup').data('redirect');
                if ($message.length > 0) {
                    $.fancybox.open({
                        src: $message,
                        type : 'inline',
                        beforeClose: function () {
                            window.location = $redirect;
                        }
                    });
                }
            })();
        })();

        /**
         * Поля ввода телефонного номера
         */
        (function () {
            $('input[type="tel"]').on('focus', function () {
                if (!$(this).val()) {
                    $(this).val('+375-');
                }
            }).mask('+375-00-000-00-00');
        })();

        /**
         * Всплывающие формы
         */
        (function () {
            /**
             * Инициализация формы
             *
             * @param baseId - идентификатор
             * @param $trigger - jQuery элемент для вызова формы
             */
            var initForm = function (baseId, $trigger) {
                var $form = $('#' + baseId + '-form'),
                    $inputs = $form.find('input, textarea').not('[type="hidden"]'),
                    $errors = $form.find('.error-message'),
                    $successMessage = $('#' + baseId + '-success-message'),
                    $errorMessage = $('#' + baseId + '-error-message'),
                    $loader = $form.parents('.popup-form').find('.lds-css'),
                    $btnClose = $('.close-popup');

                // Закрытие формы
                $btnClose.on('click', function () {
                    $.fancybox.close(true);
                });

                var resetForm = function () {
                    $form.show();
                    $errors.html('').hide();
                    $successMessage.hide();
                    $errorMessage.hide();
                    $loader.hide();
                };

                var successMessage = function () {
                    $form.hide();
                    $inputs.val('');
                    $errors.html('').hide();
                    $successMessage.show();
                    $loader.hide();
                };

                var errorMessage = function () {
                    $form.hide();
                    $inputs.val('');
                    $errors.html('').hide();
                    $errorMessage.show();
                    $loader.hide();
                };

                var displayErrors = function (errors) {
                    $loader.hide();
                    if (typeof(errors) === 'object' && Object.getOwnPropertyNames(errors).length > 0) {
                        for (var i in errors) {
                            var error = errors[i].join('<br>');
                            $form.find('.' + i + '-error').html(error).show()
                                .parents('.input-wrapper').addClass('error');
                        }
                    } else {
                        errorMessage();
                    }
                };

                $trigger.fancybox({
                    beforeShow: resetForm
                });

                $form.ajaxForm({
                    method: 'post',
                    dataType: 'json',
                    beforeSubmit: function () {
                        $loader.show();
                    },
                    success: function (data) {
                        $loader.hide();
                        $errors.hide().parents('.input-wrapper').removeClass('error');
                        if (data.status === 'success') {
                            successMessage();
                        } else if (data.status === 'error') {
                            displayErrors(data.errors);
                        } else {
                            errorMessage();
                        }
                    },
                    error: errorMessage
                });

                $inputs.on('focus', function () {
                    $(this).parents('.input-wrapper').removeClass('error');
                });
            };

            // Инициализация форм
            initForm('call-request', $('.call-me')); // Форма заказа звонка
            initForm('review', $('.add-review'));    // Форма отзыва
        })();
    });
})(jQuery);