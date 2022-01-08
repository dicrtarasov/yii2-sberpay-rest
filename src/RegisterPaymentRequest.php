<?php
/*
 * @copyright 2019-2022 Dicr http://dicr.org
 * @author Igor A Tarasov <develop@dicr.org>
 * @license MIT
 * @version 08.01.22 18:18:52
 */

declare(strict_types = 1);
namespace dicr\sberpay;

use dicr\json\EntityValidator;
use dicr\sberpay\entity\AppInfo;
use dicr\sberpay\entity\OfdParams;
use dicr\sberpay\entity\OrderBundle;
use yii\helpers\Json;

use function array_filter;
use function array_merge;
use function str_replace;

/**
 * Запрос на создание платежа.
 *
 * @link https://securepayments.sberbank.ru/wiki/doku.php/integration:api:rest:requests:register
 * @link https://securepayments.sberbank.ru/wiki/doku.php/integration:api:rest:requests:register_cart
 */
class RegisterPaymentRequest extends SberPayRequest
{
    /**
     * для загрузки страниц, вёрстка которых предназначена для отображения на экранах ПК.
     * (в архиве страниц платёжного интерфейса будет осуществляться поиск страниц с названиями
     * payment_<locale>.html и errors_<locale>.html)
     */
    public const PAGE_VIEW_DESKTOP = 'DESKTOP';

    /**
     * для загрузки страниц, вёрстка которых предназначена для отображения на экранах мобильных устройств.
     * (в архиве страниц платёжного интерфейса будет осуществляться поиск страниц с названиями
     * mobile_payment_<locale>.html и mobile_errors_<locale>.html).
     */
    public const PAGE_VIEW_MOBILE = 'MOBILE';

    /**
     * Платёж проводится без проверки подлинности владельца карты (без CVC и 3D-Secure).
     * Чтобы проводить подобные платежи и продавца должны быть соответствующие разрешения.
     */
    public const FEATURES_AUTO_PAYMENT = 'AUTO_PAYMENT';

    /**
     * Принудительное проведение платежа с использованием 3-D Secure.
     * Если карта не поддерживает 3-D Secure, транзакция не пройдёт.
     */
    public const FEATURES_FORCE_TDS = 'FORCE_TDS';

    /** Принудительное проведение платежа через SSL (без использования 3-D Secure). */
    public const FEATURES_FORCE_SSL = 'FORCE_SSL';

    /**
     * После проведения аутентификации с помощью 3-D Secure статус PaRes должен быть только Y,
     * что гарантирует успешную аутентификацию пользователя. В противном случае транзакция не пройдёт.
     */
    public const FEATURES_FORCE_FULL_TDS = 'FORCE_FULL_TDS';

    /** система налогообложения - общая */
    public const TAX_COMMON = 0;

    /** упрощённая, доход */
    public const TAX_SIMPLE = 1;

    /** упрощённая, доход минус расход */
    public const TAX_SIMPLE_DEB = 2;

    /** единый налог на вменённый доход */
    public const TAX_IMPUTED = 3;

    /** единый сельскохозяйственный налог */
    public const TAX_AGRICULTURAL = 4;

    /** патентная система налогообложения */
    public const TAX_PATENT = 5;

    /**
     * Номер (идентификатор) заказа в системе магазина, уникален для каждого магазина в пределах системы.
     * Если номер заказа генерируется на стороне платёжного шлюза, этот параметр передавать необязательно.
     */
    public ?string $orderNumber = null;

    /**
     * Сумма платежа в минимальных единицах валюты (копейки, центы и т. п.).
     * Должна совпадать с общей суммой по всем товарным позициям в корзине.
     */
    public ?int $amount = null;

    /**
     * Код валюты платежа ISO 4217.
     * Если не указано, то используется значение по умолчанию.
     */
    public ?int $currency = null;

    /**
     * Адрес, на который требуется перенаправить пользователя в случае успешной оплаты.
     * В противном случае пользователь будет перенаправлен по адресу следующего вида:
     * http://<адрес_платёжного_шлюза>/<адрес_продавца>
     */
    public ?string $returnUrl = null;

    /**
     * Адрес, на который требуется перенаправить пользователя в случае неуспешной оплаты.
     * В противном случае пользователь будет перенаправлен по адресу следующего вида:
     * http://<адрес_платёжного_шлюза>/<адрес_продавца>.
     */
    public ?string $failUrl = null;

    /**
     * Описание заказа в свободной форме.
     * Чтобы получить возможность отправлять это поле в процессинг, обратитесь в техническую поддержку.
     * Не более 24 символов, запрещены к использованию %, +, конец строки \r и перенос строки \n
     */
    public ?string $description = null;

    /**
     * Язык в кодировке ISO 639-1.
     * Если не указан, будет использован язык, указанный в настройках магазина как язык по умолчанию.
     */
    public ?string $language = null;

    /**
     * по значению данного параметра определяется, какие страницы платёжного интерфейса
     * должны загружаться для клиента. (PAGE_VIEW_*)
     * Если параметр отсутствует, либо не соответствует формату, то по умолчанию считается pageView=DESKTOP.
     */
    public ?string $pageView = null;

    /** Номер (идентификатор) клиента в системе магазина.
     * Используется для реализации функционала связок. Может присутствовать, если магазину разрешено создание связок.
     * Указание этого параметра при платежах по связке необходимо - в противном случае платёж будет неуспешен.
     */
    public ?string $clientId = null;

    /** Чтобы зарегистрировать заказ от имени дочернего продавца, укажите его логин в этом параметре. */
    public ?string $merchantLogin = null;

    /**
     * Дополнительные параметры запроса. Формат вида: {«Имя1»: «Значение1», «Имя2»: «Значение2»}.
     */
    public ?array $jsonParams = null;

    /**
     * Продолжительность жизни заказа в секундах.
     * В случае если параметр не задан, будет использовано значение, указанное в настройках мерчанта
     * или время по умолчанию (1200 секунд = 20 минут).
     * Если в запросе присутствует параметр expirationDate, то значение параметра sessionTimeoutSecs не учитывается.
     */
    public ?int $sessionTimeoutSecs = null;

    /**
     * Дата и время окончания жизни заказа. Формат: yyyy-MM-ddTHH:mm:ss.
     * Если этот параметр не передаётся в запросе, то для определения времени окончания жизни заказа используется
     * sessionTimeoutSecs.
     */
    public ?string $expirationDate = null;

    /**
     * Идентификатор созданной ранее связки.
     * Может использоваться, только если у продавца есть разрешение на работу со связками.
     * Если этот параметр передаётся в данном запросе, то это означает:
     * 1. Данный заказ может быть оплачен только с помощью связки;
     * 2. Плательщик будет перенаправлен на платёжную страницу, где требуется только ввод CVC.
     */
    public ?string $bindingId = null;

    /**
     * дополнительный параметры ОФД.
     * Некоторые параметры блока additionalOfdParams дублируют параметры блока cartItems.items.itemAttributes.
     * Блок additionalOfdParams применяется ко всем позициям заказа, тогда как cartItems.items.itemAttributes
     * применяется к индивидуальным позициям.
     * Если в блоках additionalOfdParams и cartItems.items.itemAttributes и additionalOfdParams будет переданы
     * разные значения, то приоритетным значением будет то, которое было передано в cartItems.items.itemAttributes,
     * то есть — для индивидуальной позиции.
     * Передача этого блока возможна только при использовании следующих ОФД: АТОЛ; Бизнес.Ру; Эвотор.
     */
    public OfdParams|array|null $additionalOfdParams = null;

    /**
     * Блок, содержащий корзину товаров заказа.
     */
    public OrderBundle|array|null $orderBundle = null;

    public ?int $taxSystem = null;

    /** дополнительные параметры (FEATURES_*) */
    public ?string $features = null;

    /** Адрес электронной почты покупателя. */
    public ?string $email = null;

    /**
     * Номер телефона клиента.
     * Если в телефон включён код страны, номер должен начинаться со знака плюс («+»).
     * Если телефон передаётся без знака плюс («+»), то код страны указывать не следует.
     * Таким образом, допустимы следующие варианты:
     * +79998887766;
     * 79998887766.
     * Допустимое количество цифр: от 7 до 15.
     */
    public ?string $phone = null;

    /**** Параметры при сценарии оплаты app2app ********************************************************************/

    /**
     * Атрибут, указывающий на способ оплаты через приложение СБОЛ (app2app).
     * Для использования этого параметра у мерчанта должны быть включены соответствующие разрешения.
     */
    public ?bool $app2app = null;

    /** Обязательно, только если app2app=true. */
    public AppInfo|array|null $app = null;

    /**** Параметры при сценарии оплаты back2app *******************************************************************/

    /** Атрибут, указывающий на способ оплаты по сценарию back2app */
    public ?bool $back2app = null;

    /** запрос с предавторизацией */
    public bool $preAuth = false;

    /**
     * @inheritDoc
     */
    public function attributeEntities(): array
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
    public function rules(): array
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
            ['description', 'filter', 'filter' => static fn(string $description): string => str_replace(
                ['%', '+', "\r", "\n"], ['_', '_', ' ', ' '], $description
            ), 'skipOnEmpty' => true],
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
            ['amount', 'default', 'value' => fn(): ?int => $this->amount()],
            ['amount', 'required'],
            ['amount', 'integer', 'min' => 1],

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
     */
    public function amount(): ?int
    {
        if (! isset($this->orderBundle->cartItems->items)) {
            return null;
        }

        $amount = 0;

        foreach ($this->orderBundle->cartItems->items as $item) {
            $amount += $item->amount ?? $item->amount();
        }

        return $amount;
    }

    /**
     * @inheritDoc
     */
    public function getJson(): array
    {
        $json = parent::getJson();

        // удаляем лишний атрибут preAuth
        unset($json['preAuth']);

        // дебильные программисты Сбербанк поля дополнительно кодируют в JSON
        $json = array_merge($json, [
            'additionalOfdParams' => isset($this->additionalOfdParams) ?
                Json::encode($this->additionalOfdParams->json) : null,
            'orderBundle' => isset($this->orderBundle) ?
                Json::encode($this->orderBundle->json) : null,
            'app' => isset($this->app) ? Json::encode($this->app->json) : null
        ]);

        return array_filter(
            $json,
            static fn($val): bool => $val !== null && $val !== '' && $val !== []
        );
    }

    /**
     * @inheritDoc
     */
    public function url(): string
    {
        return $this->preAuth ? 'registerPreAuth.do' : 'register.do';
    }

    /**
     * @inheritDoc
     */
    public function send(): RegisterPaymentResponse
    {
        return new RegisterPaymentResponse([
            'json' => parent::send()
        ]);
    }
}
