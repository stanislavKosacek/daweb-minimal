<?php

declare(strict_types=1);

namespace PackageVersions;

use Composer\InstalledVersions;
use OutOfBoundsException;

class_exists(InstalledVersions::class);

/**
 * This class is generated by composer/package-versions-deprecated, specifically by
 * @see \PackageVersions\Installer
 *
 * This file is overwritten at every run of `composer install` or `composer update`.
 *
 * @deprecated in favor of the Composer\InstalledVersions class provided by Composer 2. Require composer-runtime-api:^2 to ensure it is present.
 */
final class Versions
{
    /**
     * @deprecated please use {@see self::rootPackageName()} instead.
     *             This constant will be removed in version 2.0.0.
     */
    const ROOT_PACKAGE_NAME = 'nette/web-project';

    /**
     * Array of all available composer packages.
     * Dont read this array from your calling code, but use the \PackageVersions\Versions::getVersion() method instead.
     *
     * @var array<string, string>
     * @internal
     */
    const VERSIONS          = array (
  'clue/stream-filter' => 'v1.5.0@aeb7d8ea49c7963d3b581378955dbf5bc49aa320',
  'composer/package-versions-deprecated' => '1.11.99.1@7413f0b55a051e89485c5cb9f765fe24bb02a7b6',
  'contributte/application' => 'v0.4.1@401975416fbc87b13acc66d17882c5941b3e303b',
  'contributte/console' => 'v0.9.0@973bc5a66ef73e2d9ab815a0c00856c9c92b7788',
  'contributte/di' => 'v0.5.0@09956b9a43d01b6204ed6b339d79c6c0bb44a28a',
  'contributte/event-dispatcher' => 'v0.8.0@97d0f73a12e3cd9024f696c990396ac7b0604ed6',
  'contributte/latte-parsedown-extra' => '1.5.2@1633c7302a48e973cc161e5299e0b91883211aaf',
  'contributte/scheduler' => 'v0.7.0@a1cf37ad0c6e5bd7016ba91039996080ee70171d',
  'contributte/translation' => 'v0.7.0@26fa99330deb663770ef45e1f6e8cbf1f3fb3c23',
  'dragonmantank/cron-expression' => 'v3.1.0@7a8c6e56ab3ffcc538d05e8155bb42269abf1a0c',
  'erusev/parsedown' => '1.7.4@cb17b6477dfff935958ba01325f2e8a2bfa6dab3',
  'erusev/parsedown-extra' => '0.8.1@91ac3ff98f0cea243bdccc688df43810f044dcef',
  'guzzlehttp/guzzle' => '6.5.5@9d4290de1cfd701f38099ef7e183b64b4b7b0c5e',
  'guzzlehttp/promises' => 'v1.3.1@a59da6cf61d80060647ff4d3eb2c03a2bc694646',
  'guzzlehttp/psr7' => '1.7.0@53330f47520498c0ae1f61f7e2c90f55690c06a3',
  'hashids/hashids' => '4.1.0@8cab111f78e0bd9c76953b082919fc9e251761be',
  'jean85/pretty-package-versions' => '1.6.0@1e0104b46f045868f11942aea058cd7186d6c303',
  'kdyby/autowired' => 'v2.1.0@c3183c5feeb3566c4a54db7f033ce24a00c27e7d',
  'latte/latte' => 'v2.8.1@fc94bd63fe995b40cb219109026e76f281c709c2',
  'nette/application' => 'v3.0.5@2974fbfdaa06ca78b8f9f2c71bf16f91ce13f7a2',
  'nette/bootstrap' => 'v3.0.2@67830a65b42abfb906f8e371512d336ebfb5da93',
  'nette/caching' => 'v3.0.1@b9ecbf920f240bd1ab14900d9a77876924ad7fb4',
  'nette/component-model' => 'v3.0.1@66409cf5507c77edb46ffa88cf6a92ff58395601',
  'nette/database' => 'v3.0.6@daccbd526f74311549e5c81d3181fc74f87c6733',
  'nette/di' => 'v3.0.5@766e8185196a97ded4f9128db6d79a3a124b7eb6',
  'nette/finder' => 'v2.5.2@4ad2c298eb8c687dd0e74ae84206a4186eeaed50',
  'nette/forms' => 'v3.0.6@ecb5f7b8c82585c5fc4698ccb6815542fe6b2db4',
  'nette/http' => 'v3.0.4@e4d8d360c66c8af9512ca13ab629d312af2b3ce3',
  'nette/mail' => 'v3.1.4@bf56ecfd338335f7873c8f356134b3a0960653f8',
  'nette/neon' => 'v3.2.1@a5b3a60833d2ef55283a82d0c30b45d136b29e75',
  'nette/php-generator' => 'v3.4.1@7051954c534cebafd650efe8b145ac75b223cb66',
  'nette/robot-loader' => 'v3.3.0@737ff8ee1709eff053d9cc27e6c661d82395bd8b',
  'nette/routing' => 'v3.0.0@603c697f3df7ed214795d4e8e8c58fbf981232b1',
  'nette/schema' => 'v1.0.2@febf71fb4052c824046f5a33f4f769a6e7fa0cb4',
  'nette/security' => 'v3.0.4@2eacedebfbeb9d9587ef1e59c18d170879474c61',
  'nette/tokenizer' => 'v3.1.0@14d42330fb299e877ad5a01f955098e35190c042',
  'nette/utils' => 'v3.1.3@c09937fbb24987b2a41c6022ebe84f4f1b8eec0f',
  'nextras/dbal' => 'v4.0.0@6a46f938bcacaac1c37d5f37420cd6bd13431aec',
  'nextras/migrations' => 'v3.1.3@6d5983de55cc51124012153477f6aa07440c935b',
  'nextras/orm' => 'v4.0.0@7272f308b69bef3e3a9f16c7bed8fd180ed1efab',
  'oops/webpack-nette-adapter' => '1.3.1@e8b0cae715438c9b02d682e58931779d189ded66',
  'paragonie/random_compat' => 'v9.99.99@84b4dfb120c6f9b4ff7b3685f9b8f1aa365a0c95',
  'php-http/client-common' => '2.3.0@e37e46c610c87519753135fb893111798c69076a',
  'php-http/discovery' => '1.13.0@788f72d64c43dc361e7fcc7464c3d947c64984a7',
  'php-http/guzzle6-adapter' => 'v2.0.1@6074a4b1f4d5c21061b70bab3b8ad484282fe31f',
  'php-http/httplug' => '2.2.0@191a0a1b41ed026b717421931f8d3bd2514ffbf9',
  'php-http/message' => '1.11.0@fb0dbce7355cad4f4f6a225f537c34d013571f29',
  'php-http/message-factory' => 'v1.0.2@a478cb11f66a6ac48d8954216cfed9aa06a501a1',
  'php-http/promise' => '1.1.0@4c4c1f9b7289a2ec57cde7f1e9762a5789506f88',
  'psr/cache' => '1.0.1@d11b50ad223250cf17b86e38383413f5a6764bf8',
  'psr/container' => '1.0.0@b7ce3b176482dbbc1245ebf52b181af44c2cf55f',
  'psr/event-dispatcher' => '1.0.0@dbefd12671e8a14ec7f180cab83036ed26714bb0',
  'psr/http-client' => '1.0.1@2dfb5f6c5eff0e91e20e913f8c5452ed95b86621',
  'psr/http-factory' => '1.0.1@12ac7fcd07e5b077433f5f2bee95b3a771bf61be',
  'psr/http-message' => '1.0.1@f6561bf28d520154e4b0ec72be95418abe6d9363',
  'psr/log' => '1.1.3@0f73288fd15629204f9d42b7055f72dacbe811fc',
  'ralouphie/getallheaders' => '3.0.3@120b605dfeb996808c31b6477290a714d356e822',
  'sentry/sentry' => '2.4.0@e44561875e0d724bac3d9cdb705bf58847acd425',
  'symfony/config' => 'v5.1.5@22f961ddffdc81389670b2ca74a1cc0213761ec0',
  'symfony/console' => 'v5.2.3@89d4b176d12a2946a1ae4e34906a025b7b6b135a',
  'symfony/deprecation-contracts' => 'v2.2.0@5fa56b4074d1ae755beb55617ddafe6f5d78f665',
  'symfony/event-dispatcher' => 'v5.2.3@4f9760f8074978ad82e2ce854dff79a71fe45367',
  'symfony/event-dispatcher-contracts' => 'v2.2.0@0ba7d54483095a198fa51781bc608d17e84dffa2',
  'symfony/filesystem' => 'v5.1.5@f7b9ed6142a34252d219801d9767dedbd711da1a',
  'symfony/options-resolver' => 'v5.2.3@5d0f633f9bbfcf7ec642a2b5037268e61b0a62ce',
  'symfony/polyfill-ctype' => 'v1.18.1@1c302646f6efc070cd46856e600e5e0684d6b454',
  'symfony/polyfill-intl-grapheme' => 'v1.18.1@b740103edbdcc39602239ee8860f0f45a8eb9aa5',
  'symfony/polyfill-intl-idn' => 'v1.18.1@5dcab1bc7146cf8c1beaa4502a3d9be344334251',
  'symfony/polyfill-intl-normalizer' => 'v1.18.1@37078a8dd4a2a1e9ab0231af7c6cb671b2ed5a7e',
  'symfony/polyfill-mbstring' => 'v1.18.1@a6977d63bf9a0ad4c65cd352709e230876f9904a',
  'symfony/polyfill-php70' => 'v1.18.1@0dd93f2c578bdc9c72697eaa5f1dd25644e618d3',
  'symfony/polyfill-php72' => 'v1.18.1@639447d008615574653fb3bc60d1986d7172eaae',
  'symfony/polyfill-php73' => 'v1.22.0@a678b42e92f86eca04b7fa4c0f6f19d097fb69e2',
  'symfony/polyfill-php80' => 'v1.18.1@d87d5766cbf48d72388a9f6b85f280c8ad51f981',
  'symfony/polyfill-uuid' => 'v1.22.0@17e0611d2e180a91d02b4fa8b03aab0368b661bc',
  'symfony/property-access' => 'v5.1.6@4c43f7ff784e1e3ee1c96e15f76b342af6617b39',
  'symfony/property-info' => 'v5.1.6@22518930091e0bdb249694efc509e3697f7e325e',
  'symfony/service-contracts' => 'v2.2.0@d15da7ba4957ffb8f1747218be9e1a121fd298a1',
  'symfony/string' => 'v5.1.6@4a9afe9d07bac506f75bcee8ed3ce76da5a9343e',
  'symfony/translation' => 'v5.1.5@917b02cdc5f33e0309b8e9d33ee1480b20687413',
  'symfony/translation-contracts' => 'v2.2.0@77ce1c3627c9f39643acd9af086631f842c50c4d',
  'tracy/tracy' => 'v2.7.5@95b1c6e35d61df8ef91a4633ba3cf835d2d47e5e',
  'ublaboo/datagrid' => 'v6.4.1@38948d9a12114e4237eb991681a5ef52dca1a887',
  'webchemistry/images' => '4.1.8@a52241e2a8630c445474df72c9d51902144fdc5c',
  'webmozart/assert' => '1.9.1@bafc69caeb4d49c39fd0779086c03a3738cbb389',
  'whichbrowser/parser' => 'v2.1.1@da24adc4f4f26002673d236e69b91a10f2fd594c',
  'nette/tester' => 'v2.3.3@1fbe4860b4cfabe99f03ee3632db6352f0564d22',
  'symfony/thanks' => 'v1.2.9@733cc7b8c09a06c9251bd35d772b453b47d98442',
  'nette/web-project' => 'dev-master@0865ca0e8800a9509d0d76ad071107b163a8f6a8',
);

    private function __construct()
    {
    }

    /**
     * @psalm-pure
     *
     * @psalm-suppress ImpureMethodCall we know that {@see InstalledVersions} interaction does not
     *                                  cause any side effects here.
     */
    public static function rootPackageName() : string
    {
        if (!class_exists(InstalledVersions::class, false) || !InstalledVersions::getRawData()) {
            return self::ROOT_PACKAGE_NAME;
        }

        return InstalledVersions::getRootPackage()['name'];
    }

    /**
     * @throws OutOfBoundsException If a version cannot be located.
     *
     * @psalm-param key-of<self::VERSIONS> $packageName
     * @psalm-pure
     *
     * @psalm-suppress ImpureMethodCall we know that {@see InstalledVersions} interaction does not
     *                                  cause any side effects here.
     */
    public static function getVersion(string $packageName): string
    {
        if (class_exists(InstalledVersions::class, false) && InstalledVersions::getRawData()) {
            return InstalledVersions::getPrettyVersion($packageName)
                . '@' . InstalledVersions::getReference($packageName);
        }

        if (isset(self::VERSIONS[$packageName])) {
            return self::VERSIONS[$packageName];
        }

        throw new OutOfBoundsException(
            'Required package "' . $packageName . '" is not installed: check your ./vendor/composer/installed.json and/or ./composer.lock files'
        );
    }
}