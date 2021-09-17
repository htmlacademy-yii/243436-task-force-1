<?php
    use yii\helpers\Url;
    use yii\helpers\Html;
    use Taskforce\BusinessLogic\Task;
    use yii\widgets\ActiveForm;
?>

<section class="content-view">
    <div class="content-view__card">
        <div class="content-view__card-wrapper">
            <div class="content-view__header">
                <div class="content-view__headline">
                <h1><?= Html::encode($tasks['name']); ?></h1>
                <span>Размещено в категории
                    <a href="<?= Url::to(['tasks/index', 'TasksForm'=>['category' => $tasks['category_id']]]) ?>"
                    class="link-regular">
                        <?= Html::encode($tasks->category->name); ?>
                    </a>
                    <?= Yii::$app->formatter->asRelativeTime(Html::encode($tasks->date_add)); ?>
                </span>
                </div>
                <b class="new-task__price new-task__price--clean content-view-price">
                    <?= Html::encode($tasks->budget) != null ? Html::encode($tasks->budget).'<b>₽</b>' : '' ?>
                </b>
                <div class="new-task__icon new-task__icon--<?= Html::encode($tasks->category->icon); ?>
                content-view-icon"></div>
            </div>
            <div class="content-view__description">
                <h3 class="content-view__h3">Общее описание</h3>
                <p><?= Html::encode($tasks->description); ?></p>
            </div>
            <div class="content-view__attach">
                <h3 class="content-view__h3">Вложения</h3>
                <?php if(!empty($clips)) : ?>
                    <?php foreach($clips as $clip) : ?>
                        <a href="<?= Url::to("@web/uploads/{$clip['path']}"); ?>"><?= $clip['path']; ?></a>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p>Отсутствуют</p>
                <?php endif; ?>
            </div>
            <div class="content-view__location">
                <h3 class="content-view__h3">Расположение</h3>
                <div class="content-view__location-wrapper">
                <div class="content-view__map" id="map" style="width: 361px; height: 292px;"></div>
                <div class="content-view__address">
                    <span class="address__town"><?= $tasks->address; ?></span><br>
                    <!-- <span>Новый арбат, 23 к. 1</span>
                    <p>Вход под арку, код домофона 1122</p> -->
                </div>
                </div>
            </div>
        </div>
        <?php if ((($user->role === Task::CREATOR
        && $tasks->user_id_create === $currentID)
        || (($user->role === Task::EXECUTOR
        && empty($oneRespond))
        || ($user->role === Task::EXECUTOR
        && $oneRespond[0]->status === 'Подтверждено')))
        && $tasks->status !== Task::STATUS_CANCEL
        && $tasks->status !== Task::STATUS_FAILED
        && $tasks->status !== Task::STATUS_PERFORMED) : ?>
            <div class="content-view__action-buttons">
                <?php if ($task->getAvailableActions($tasks->status)[0]
                ->isCompareID($currentID, $executorID, $creatorID)
                && $task->getAvailableActions($tasks->status)[0]->getNameAction() === Task::ACTION_RESPONSE) : ?>
                    <button class=" button button__big-color response-button open-modal"
                            type="button" data-for="response-form">Откликнуться
                    </button>
                <?php endif; ?>

                <?php if ($task->getAvailableActions($tasks->status)[1]
                ->isCompareID($currentID, $executorID, $creatorID)
                && $task->getAvailableActions($tasks->status)[1]->getNameAction() === Task::ACTION_CANCEL) : ?>
                    <button class="button button__big-color refusal-button open-modal"
                            type="button" data-for="cancel-form">Отменить
                    </button>
                <?php endif; ?>

                <?php if ($task->getAvailableActions($tasks->status)[0]
                ->isCompareID($currentID, $executorID, $creatorID)
                && $task->getAvailableActions($tasks->status)[0]->getNameAction() === Task::ACTION_PERFORMED) : ?>
                    <button class="button button__big-color request-button open-modal"
                        type="button" data-for="complete-form">Завершить
                    </button>
                <?php endif; ?>

                <?php if ($task->getAvailableActions($tasks->status)[1]
                ->isCompareID($currentID, $executorID, $creatorID)
                && $task->getAvailableActions($tasks->status)[1]->getNameAction() === Task::ACTION_REFUSE) : ?>
                    <button class="button button__big-color refusal-button open-modal"
                        type="button" data-for="refuse-form">Отказаться
                    </button>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <?php if (!empty($responds)) : ?>
        <div class="content-view__feedback">
            <h2>Отклики <span>(<?= count($responds); ?>)</span></h2>

            <div class="content-view__feedback-wrapper">
                <?php foreach($responds as $respond) : ?>
                    <div class="content-view__feedback-card">
                        <div class="feedback-card__top">
                            <a href="<?= Url::to(['users/user', 'id' => $respond->executor->id]) ?>">
                                <?= Html::img("@web/{$respond->executor->path}", ['width' => 55, 'height' => 55]) ?>
                            </a>
                            <div class="feedback-card__top--name">
                                <p>
                                    <a href="<?= Url::to(['users/user', 'id' => $respond->executor->id]) ?>"
                                    class="link-regular">
                                        <?= Html::encode($respond->executor->name); ?>
                                    </a>
                                </p>
                                <?php $average_rating = $respond->executor->getAverageRating(); ?>
                                <?php if ($average_rating >= 1 && $average_rating < 2) : ?>
                                    <span></span>
                                    <span class="star-disabled"></span>
                                    <span class="star-disabled"></span>
                                    <span class="star-disabled"></span>
                                    <span class="star-disabled"></span>
                                <?php elseif ($average_rating >= 2 && $average_rating < 3) : ?>
                                    <span></span>
                                    <span></span>
                                    <span class="star-disabled"></span>
                                    <span class="star-disabled"></span>
                                    <span class="star-disabled"></span>
                                <?php elseif ($average_rating >= 3 && $average_rating < 4) : ?>
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                    <span class="star-disabled"></span>
                                    <span class="star-disabled"></span>
                                <?php elseif ($average_rating >= 4 && $average_rating < 5) : ?>
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                    <span class="star-disabled"></span>
                                <?php elseif ($average_rating >= 5) : ?>
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                <?php else : ?>
                                    <span class="star-disabled"></span>
                                    <span class="star-disabled"></span>
                                    <span class="star-disabled"></span>
                                    <span class="star-disabled"></span>
                                    <span class="star-disabled"></span>
                                <?php endif; ?>

                                <b><?= Html::encode($average_rating); ?></b>
                            </div>
                            <span class="new-task__time">
                                <?= Yii::$app->formatter->asRelativeTime(Html::encode($respond->date)); ?>
                            </span>
                        </div>
                        <div class="feedback-card__content">
                            <p><?= Html::encode($respond->comment); ?></p>
                            <span><?= Html::encode($respond->budget); ?> ₽</span>
                        </div>
                        <?php if (\Yii::$app->user->getId() == $tasks->user_id_create
                        && $respond->status !== 'Отклонено'
                        && $tasks->user_id_executor == null
                        && $tasks->status !== task::STATUS_CANCEL) : ?>
                            <div class="feedback-card__actions">
                                <a class="button__small-color response-button button" type="button"
                                href="<?= Url::to(['tasks/view', 'id' => \Yii::$app->request->get('id'),
                                $respond->user_id_executor => 'true' ]) ?>">
                                    Подтвердить</a>
                                <a class="button__small-color refusal-button button" type="button"
                                href="<?= Url::to(['tasks/view', 'id' => \Yii::$app->request->get('id'),
                                $respond->user_id_executor => 'false' ]) ?>">
                                    Отказать
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</section>
<section class="connect-desk">
    <div class="connect-desk__profile-mini">
        <div class="profile-mini__wrapper">
        <h3>Заказчик</h3>
        <div class="profile-mini__top">
            <?= Html::img("@web/{$tasks->creator->path}", [
                    'width' => 55, 'height' => 55,
                    'alt' => 'Аватар заказчика'
                ])
            ?>
            <div class="profile-mini__name five-stars__rate">
            <p><?= Html::encode($tasks->creator->name); ?></p>
            </div>
        </div>
        <p class="info-customer">
        <span>
            <?= Yii::t(
                'app',
                '{n, plural,
                    =0{# заданий}
                    =1{# задание}
                    one{# задание}
                    few{# задания}
                    many{# заданий}
                    other{# задания}}',
                ['n' => $tasksCount]
            ); ?>
        </span>
        <span class="last-">
            <?php
                if ($result_time < 365) {
                    echo Yii::t(
                        'app',
                        '{n, plural,
                            =0{# дней}
                            =1{# день}
                            one{# день}
                            few{# дня}
                            many{# дней}
                            other{# дня}}',
                        ['n' => $result_time]
                    );
                } elseif ($result_time > 364) {
                    echo Yii::t(
                        'app',
                        '{n, plural,
                            =0{# лет}
                            =1{# год}
                            one{# год}
                            few{# года}
                            many{# лет}
                            other{# года}}',
                        ['n' => floor($result_time/365)]
                    );
                }
            ?>
            на сайте
        </span></p>
        <!-- <a href="#" class="link-regular">Смотреть профиль</a> -->
        </div>
    </div>

    <?php if ($user->role === Task::CREATOR
    && $tasks->user_id_create === $currentID
    || $user->role === Task::EXECUTOR) : ?>
        <div id="chat-container">
            <!--добавьте сюда атрибут task с указанием в нем id текущего задания-->
            <div class="connect-desk__chat">
                <h3>Переписка</h3>
                <div class="chat__overflow">
                    <?php foreach($messages as $message) : ?>
                        <p>
                            <span style="font-size: 10px; color: #333438;">
                                (<?= Html::encode(Yii::$app->formatter->asDatetime($message->date_add)); ?>)
                            </span><br>

                            <b <?= $message->user_id_create ? 'style="color: #ff3d71;"' : 'style="color: #3366ff;"' ?>>
                            <?= Html::encode($message->creator->name ?? $message->excecutor->name); ?></b>:
                            <span style="color: #333438;"><?= Html::encode($message->message); ?></span>
                        </p>
                    <?php endforeach; ?>
                </div>
                <?php if ($user->role === Task::CREATOR
                && $tasks->user_id_create === $currentID
                && $tasks->status !== Task::STATUS_CANCEL
                && $tasks->status !== Task::STATUS_FAILED
                && $tasks->status !== Task::STATUS_PERFORMED
                ||$user->role === Task::EXECUTOR
                && $tasks->status !== Task::STATUS_FAILED
                && $tasks->status !== Task::STATUS_PERFORMED
                && $tasks->status !== Task::STATUS_CANCEL
                && (!empty($oneRespond) && $oneRespond[0]->status !== 'Отклонено')) : ?>
                    <p class="chat__your-message">Ваше сообщение</p>

                    <?php $form = ActiveForm::begin([
                        'options' => ['class' => 'chat__form'],
                        'fieldConfig' => [
                            'template' => "{input}",
                        ]
                    ]); ?>

                        <?= $form->field($messagesForm, 'message', ['options' => ['tag' => false]])
                            ->textarea([
                                'class' => 'input textarea textarea-chat',
                                'rows' => 2,
                                'placeholder' => 'Текст сообщения'
                            ])
                        ?>

                        <?= Html::submitButton('Отправить', ['class' => 'button chat__button']) ?>

                    <?php ActiveForm::end(); ?>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</section>

<script src="https://api-maps.yandex.ru/2.1/?apikey=<?= \Yii::$app->params['apikey']; ?>&lang=ru_RU"></script>
<script>
    ymaps.ready(init);
    function init(){
        var myMap = new ymaps.Map("map", {
            center: [<?= $tasks->lat; ?>, <?= $tasks->lon; ?>],
            zoom: 15
        });
    }
</script>
