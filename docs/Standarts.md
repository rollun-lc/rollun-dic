# Стандарты

## Стандарт оформления репозитория
    
[pds/skeleton](https://github.com/php-pds/skeleton/blob/1.x/README.md )

Каждое приложение которое требует спецефичиской настройки должно предоставить сооствествующий классы, 
реализующеее `zaboy\install\InstallerInterface` интерфейс для ее проведения.

Блдее детально [тут](https://github.com/avz-cmf/zaboy-installer).

## Стандарт кодирование

[psr-2](http://www.php-fig.org/psr/psr-2/)

## Станларт наименования контейнеров(инстансов) 

Запущеный Контейнер должен именоваться по принципу `{server_name}-{vm_name}-{container_name}`
>vm-name требуется только в случае если на сервере существуют виртуальные машины.

Имя машины должно храниться в переменной окружения `SERVICE_MACHINE_NAME`

## Стандарт ведения репозитория

Работа с репозиторием осуществляется по средсвам `git-workflow`.

Кратко

Главные ветви:
* develop
* master
Вспомогательные ветви:
* Ветви функциональностей (Feature branches)
* Ветви релизов (Release branches)
* Ветви исправлений (Hotfix branches)

Подробнее можно почитать тут:
 * [Git workflow](https://www.atlassian.com/git/tutorials/comparing-workflows/)
 * [Удачная модель ветвления для Git](https://habrahabr.ru/post/106912/)
 
 