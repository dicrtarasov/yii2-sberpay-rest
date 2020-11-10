<?php
/*
 * @copyright 2019-2020 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 10.11.20 17:21:47
 */

declare(strict_types = 1);
namespace dicr\sberbank;

use dicr\json\EntityValidator;
use dicr\sberbank\entity\AppInfo;
use dicr\sberbank\entity\Item;
use dicr\sberbank\entity\OfdParams;
use dicr\sberbank\entity\OrderBundle;
use yii\helpers\Json;

use function array_reduce;
use function round;
use function str_replace;

/**
 * Запрос на создание платежа.
 *
 * @link https://securepayments.sberbank.ru/wiki/doku.php/integration:api:rest:requests:register
 * @link https://securepayments.sberbank.ru/wiki/doku.php/integration:api:rest:requests:register_cart
 */
class RegisterPaymentRequest extends SberbankRequest
{
    /**
     * @var string для загрузки страниц, вёрстка которых предназначена для отображения на экранах ПК.
     * (в архиве страниц платёжного интерфейса будет осуществляться поиск страниц с названиями
     * payment_<locale>.html и errors_<locale>.html)
     */
    public const PAGE_VIEW_DESKTOP = 'DESKTOP';

    /**
     * @var string для загрузки страниц, вёрстка которых предназначена для отображения на экранах мобильных устройств.
     * (в архиве страниц платёжного интерфейса будет осуществляться поиск страниц с названиями
     * mobile_payment_<locale>.html и mobile_errors_<locale>.html).
     */
    public const PAGE_VIEW_MOBILE = 'MOBILE';

    /**
     * @var string Платёж проводится без проверки подлинности владельца карты (без CVC и 3D-Secure).
     * Чтобы проводить подобные платежи и продавца должны быть соответствующие разрешения.
     */
    public const FEATURES_AUTO_PAYMENT = 'AUTO_PAYMENT';

    /**
     * @var string Принудительное проведение платежа с использованием 3-D Secure.
     * Если карта не поддерживает 3-D Secure, транзакция не пройдёт.
     */
    public const FEATURES_FORCE_TDS = 'FORCE_TDS';

    /**
     * @var string Принудительное проведение платежа через SSL (без использования 3-D Secure).
     */
    public const FEATURES_FORCE_SSL = 'FORCE_SSL';

    /**
     * @var string После проведения аутентификации с помощью 3-D Secure статус PaRes должен быть только Y,
     * что гарантирует успешную аутентификацию пользователя. В противном случае транзакция не пройдёт.
     */
    public const FEATURES_FORCE_FULL_TDS = 'FORCE_FULL_TDS';

    /** @var int система налогообложения - общая */
    public const TAX_COMMON = 0;

    /** @var int упрощённая, доход */
    public const TAX_SIMPLE = 1;

    /** @var int упрощённая, доход минус расход */
    public const TAX_SIMPLE_DEB = 2;

    /** @var int единый налог на вменённый доход */
    public const TAX_IMPUTED = 3;

    /** @var int единый сельскохозяйственный налог */
    public const TAX_AGRICULTURAL = 4;

    /** @var int патентная система налогообложения */
    public const TAX_PATENT = 5;

    /**
     * @var ?string Номер (идентификатор) заказа в системе магазина, уникален для каждого магазина в пределах системы.
     * Если номер заказа генерируется на стороне платёжного шлюза, этот параметр передавать необязательно.
     */
    public $orderNumber;

    /**
     * @var int Сумма платежа в минимальных единицах валюты (копейки, центы и т. п.).
     * Должна совпадать с общей суммой по всем товарным позициям в корзине.
     */
    public $amount;

    /**
     * @var ?int Код валюты платежа ISO 4217.
     * Если не указано, то используется значение по умолчанию.
     */
    public $currency;

    /**
     * @var string Адрес, на который требуется перенаправить пользователя в случае успешной оплаты.
     * В противном случае пользователь будет перенаправлен по адресу следующего вида:
     * http://<адрес_платёжного_шлюза>/<адрес_продавца>
     */
    public $returnUrl;

    /**
     * @var ?string Адрес, на который требуется перенаправить пользователя в случае неуспешной оплаты.
     * В противном случае пользователь будет перенаправлен по адресу следующего вида:
     * http://<адрес_платёжного_шлюза>/<адрес_продавца>.
     */
    public $failUrl;

    /**
     * @var ?string Описание заказа в свободной форме.
     * Чтобы получить возможность отправлять это поле в процессинг, обратитесь в техническую поддержку.
     * Не более 24 символов, запрещены к использованию %, +, конец строки \r и перенос строки \n
     */
    public $description;

    /**
     * @var ?string Язык в кодировке ISO 639-1.
     * Если не указан, будет использован язык, указанный в настройках магазина как язык по умолчанию.
     */
    public $language;

    /**
     * @var ?string по значению данного параметра определяется, какие страницы платёжного интерфейса
     * должны загружаться для клиента. (PAGE_VIEW_*)
     * Если параметр отсутствует, либо не соответствует формату, то по умолчанию считается pageView=DESKTOP.
     */
    public $pageView;

    /** @var ?string Номер (идентификатор) клиента в системе магазина.
     * Используется для реализации функционала связок. Может присутствовать, если магазину разрешено создание связок.
     * Указание этого параметра при платежах по связке необходимо - в противном случае платёж будет неуспешен.
     */
    public $clientId;

    /** @var ?string Чтобы зарегистрировать заказ от имени дочернего продавца, укажите его логин в этом параметре. */
    public $merchantLogin;

    /**
     * @var ?array Дополнительные параметры запроса. Формат вида: {«Имя1»: «Значение1», «Имя2»: «Значение2»}.
     */
    public $jsonParams;

    /**
     * @var ?int Продолжительность жизни заказа в секундах.
     * В случае если параметр не задан, будет использовано значение, указанное в настройках мерчанта
     * или время по умолчанию (1200 секунд = 20 минут).
     * Если в запросе присутствует параметр expirationDate, то значение параметра sessionTimeoutSecs не учитывается.
     */
    public $sessionTimeoutSecs;

    /**
     * @var ?string Дата и время окончания жизни заказа. Формат: yyyy-MM-ddTHH:mm:ss.
     * Если этот параметр не передаётся в запросе, то для определения времени окончания жизни заказа используется
     * sessionTimeoutSecs.
     */
    public $expirationDate;

    /**
     * @var ?string Идентификатор созданной ранее связки.
     * Может использоваться, только если у продавца есть разрешение на работу со связками.
     * Если этот параметр передаётся в данном запросе, то это означает:
     * 1. Данный заказ может быть оплачен только с помощью связки;
     * 2. Плательщик будет перенаправлен на платёжную страницу, где требуется только ввод CVC.
     */
    public $bindingId;

    /**
     * @var ?OfdParams дополнительный параметры ОФД.
     * Некоторые параметры блока additionalOfdParams дублируют параметры блока cartItems.items.itemAttributes.
     * Блок additionalOfdParams применяется ко всем позициям заказа, тогда как cartItems.items.itemAttributes
     * применяется к индивидуальным позициям.
     * Если в блоках additionalOfdParams и cartItems.items.itemAttributes и additionalOfdParams будет переданы
     * разные значения, то приоритетным значением будет то, которое было передано в cartItems.items.itemAttributes,
     * то есть — для индивидуальной позиции.
     * Передача этого блока возможна только при использовании следующих ОФД: АТОЛ; Бизнес.Ру; Эвотор.
     *
     */
    public $additionalOfdParams;

    /**
     * @var ?OrderBundle Блок, содержащий корзину товаров заказа.
     */
    public $orderBundle;

    /** @var ?int */
    public $taxSystem;

    /** @var ?string дополнительные параметры (FEATURES_*) */
    public $features;

    /** @var ?string Адрес электронной почты покупателя. */
    public $email;

    /**
     * @var ?string Номер телефона клиента.
     * Если в телефон включён код страны, номер должен начинаться со знака плюс («+»).
     * Если телефон передаётся без знака плюс («+»), то код страны указывать не следует.
     * Таким образом, допустимы следующие варианты:
     * +79998887766;
     * 79998887766.
     * Допустимое количество цифр: от 7 до 15.
     */
    public $phone;

    /**** Параметры при сценарии оплаты app2app ********************************************************************/

    /**
     * @var ?bool Атрибут, указывающий на способ оплаты через приложение СБОЛ (app2app).
     * Для использования этого параметра у мерчанта должны быть включены соответствующие разрешения.
     */
    public $app2app;

    /** @var ?AppInfo Обязательно, только если app2app=true. */
    public $app;

    /**** Параметры при сценарии оплаты back2app *******************************************************************/

    /** @var ?bool Атрибут, указывающий на способ оплаты по сценарию back2app */
    public $back2app;

    /**
     * @inheritDoc
     */
    public function attributeEntities() : array
    {
        return [
            'additionalOfdParams' => OfdParams::class,
            'orderBundle' => OrderBundle::class,
            'app' => AppInfo::class
        ];
    }

    /**
     * @inheritDoc
     */
    public function rules() : array
    {
        return [
            ['orderNumber', 'trim'],
            ['orderNumber', 'required'],
            ['orderNumber', 'string', 'max' => 32],

            ['currency', 'default'],
            ['currency', 'integer', 'min' => 1],
            ['currency', 'filter', 'filter' => 'intval', 'skipOnEmpty' => true],

            ['returnUrl', 'required'],
            ['returnUrl', 'url'],
            ['returnUrl', 'string', 'max' => 512],

            ['failUrl', 'default'],
            ['failUrl', 'url'],
            ['failUrl', 'string', 'max' => 512],

            ['description', 'trim'],
            ['description', 'default'],
            ['description', 'filter', 'filter' => static function (string $description) : string {
                return str_replace(['%', '+', "\r", "\n"], ['_', '_', ' ', ' '], $description);
            }, 'skipOnEmpty' => true],
            ['description', 'string', 'max' => 512],

            ['language', 'default'],
            ['language', 'string', 'length' => 2],

            ['pageView', 'default'],
            ['pageView', 'in', 'range' => [self::PAGE_VIEW_DESKTOP, self::PAGE_VIEW_MOBILE]],

            ['clientId', 'trim'],
            ['clientId', 'default'],

            ['merchantLogin', 'trim'],
            ['merchantLogin', 'default'],

            ['jsonParams', 'default'],

            ['sessionTimeoutSecs', 'default'],
            ['sessionTimeoutSecs', 'integer', 'min' => 1],

            ['expirationDate', 'default'],
            ['expirationDate', 'date', 'format' => 'php:Y-m-d\TH:i:s'],

            ['bindingId', 'trim'],
            ['bindingId', 'default'],

            ['additionalOfdParams', 'default'],
            ['additionalOfdParams', EntityValidator::class],

            ['orderBundle', 'default'],
            ['orderBundle', EntityValidator::class],

            // проверяем после валидации orderBundle
            ['amount', 'default', 'value' => function () : ?int {
                return $this->getAmount();
            }],
            ['amount', 'required'],
            ['amount', 'number', 'min' => 0.01],

            ['taxSystem', 'default'],
            ['taxSystem', 'in', 'range' => [
                self::TAX_COMMON, self::TAX_SIMPLE, self::TAX_SIMPLE_DEB, self::TAX_IMPUTED, self::TAX_AGRICULTURAL,
                self::TAX_PATENT
            ]],

            ['features', 'default'],
            ['features', 'in', 'range' => [
                self::FEATURES_AUTO_PAYMENT, self::FEATURES_FORCE_TDS, self::FEATURES_FORCE_FULL_TDS,
                self::FEATURES_FORCE_SSL
            ]],

            ['email', 'default'],
            ['email', 'email'],

            ['phone', PhoneValidator::class],
            ['phone', 'default'],

            ['app2app', 'default'],
            ['app2app', 'boolean'],
            ['app2app', 'filter', 'filter' => 'boolval', 'skipOnEmpty' => true],

            ['app', 'default'],
            ['app', EntityValidator::class, 'skipOnEmpty' => ! $this->app],

            ['back2app', 'default'],
            ['back2app', 'boolean'],
            ['back2app', 'filter', 'filter' => 'boolval', 'skipOnEmpty' => true],
        ];
    }

    /**
     * Рассчитывает сумму.
     *
     * @return ?int
     */
    public function getAmount() : ?int
    {
        if (! isset($this->orderBundle->cartItems->items)) {
            return null;
        }

        return array_reduce($this->orderBundle->cartItems->items, static function (int $amount, Item $item) : int {
            return $amount + (int)round($item->price * $item->quantity->value);
        }, 0);
    }

    /**
     * @inheritDoc
     */
    public function getJson() : array
    {
        // извращения Сбербанк потому что там программисты дебилы
        return array_filter(array_merge(parent::getJson(), [
            'additionalOfdParams' => isset($this->additionalOfdParams) ?
                Json::encode($this->additionalOfdParams->json) : null,
            'orderBundle' => isset($this->orderNumber) ?
                Json::encode($this->orderBundle->json) : null,
            'app' => isset($this->app) ? Json::encode($this->app->json) : null
        ]), static function ($val) : bool {
            return $val !== null && $val !== '' && $val !== [];
        });
    }

    /**
     * @inheritDoc
     */
    public static function url() : string
    {
        return 'register.do';
    }

    /**
     * @inheritDoc
     * @return RegisterPaymentResponse
     */
    public function send() : RegisterPaymentResponse
    {
        return new RegisterPaymentResponse([
            'json' => parent::send()
        ]);
    }
}
