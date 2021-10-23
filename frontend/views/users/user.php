<?php
    use yii\helpers\Url;
    use yii\helpers\Html;
    use Taskforce\BusinessLogic\Task;
?>

<section class="content-view">
    <div class="user__card-wrapper">
        <div class="user__card">
            <?= Html::img("@web/{$users->path}", ['width' => 120, 'height' => 120, 'alt' => 'Аватар пользователя']) ?>
            <div class="content-view__headline">
                <h1><?= Html::encode($users->name); ?></h1>
                <p>
                    <?= Html::encode($users->city->name); ?>,
                    <?= Yii::t(
                            'app',
                            '{n, plural,
                                =0{# лет}
                                =1{# год}
                                one{# год}
                                few{# года}
                                many{# лет}
                                other{# года}}',
                            ['n' => $years_old]
                        );
                    ?>
                </p>
                <div class="profile-mini__name five-stars__rate">
                <?php $average_rating = $users->getAverageRating(); ?>
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
                <b class="done-task">
                    Выполнил
                    <?= Yii::t(
                        'app',
                        '{n, plural,
                            =0{# заказов}
                            =1{# заказ}
                            one{# заказ}
                            few{# заказа}
                            many{# заказов}
                            other{# заказа}}',
                        ['n' => $users->tasksCount]
                    ); ?>
                </b>
                <b class="done-review">
                    Получил
                    <?= Yii::t(
                        'app',
                        '{n, plural,
                            =0{# отзывов}
                            =1{# отзыв}
                            one{# отзыв}
                            few{# отзыва}
                            many{# отзывов}
                            other{# отзыва}}',
                        ['n' => $users->reviewsCount]
                    ); ?>
                </b>
            </div>
            <div class="content-view__headline
            user__card-bookmark user__card-bookmark<?= $favorite ? '--current':'' ?>">
                <span>Был на сайте <?= Yii::$app->formatter->asRelativeTime(Html::encode($users->date_visit)); ?></span>
                <a href="<?= $favorite_link; ?>"><b></b></a>
            </div>
        </div>
        <div class="content-view__description">
            <p><?= Html::encode($users->about); ?></p>
        </div>
        <div class="user__card-general-information">
            <div class="user__card-info">
                <h3 class="content-view__h3">Специализации</h3>
                <div class="link-specialization">
                    <?php foreach($users->categories as $category) : ?>
                        <a href="<?= Url::to(['users/index', 'UsersForm'=>['category' => $category->id]]) ?>"
                        class="link-regular">
                            <?= Html::encode($category->name); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
                <h3 class="content-view__h3">Контакты</h3>

                <?php if ((int) $users->show_contacts === 1
                && $role_current_user->role === Task::CREATOR
                || (int) $users->show_contacts !== 1
                || $users->id === $role_current_user->id) : ?>
                    <div class="user__card-link">
                        <a class="user__card-link--tel link-regular" href="#"><?= Html::encode($users->phone); ?></a>
                        <a class="user__card-link--email link-regular" href="#"><?= Html::encode($users->email); ?></a>
                        <a class="user__card-link--skype link-regular" href="#"><?= Html::encode($users->skype); ?></a>
                    </div>
                <?php else : ?>
                    <p>Пользователь скрыл контакты</p>
                <?php endif; ?>
            </div>
            <div class="user__card-photo">
                <h3 class="content-view__h3">Фото работ</h3>
                <?php if(!empty($photo_work)) : ?>
                    <?php foreach($photo_work as $photo) : ?>
                        <a href="<?= Url::to("@web/uploads/{$photo['path']}"); ?>" target="_blank">
                            <?= Html::img("@web/uploads/{$photo['path']}",
                            ['width' => 85, 'height' => 86, 'alt' => 'Фото работы']) ?>
                        </a>
                    <?php endforeach; ?>
                <?php else : ?>
                    <p>Пока фото не было...</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php if(!empty($reviews)) : ?>
        <div class="content-view__feedback">
            <h2>Отзывы<span>(<?= count($reviews); ?>)</span></h2>
            <div class="content-view__feedback-wrapper reviews-wrapper">
                <?php foreach($reviews as $review) : ?>
                    <div class="feedback-card__reviews">
                        <p class="link-task link">
                            Задание
                            <a href="<?= Url::to(['tasks/view', 'id'=>$review->task_id]) ?>" class="link-regular">
                                «<?= Html::encode($review->task->name); ?>»
                            </a>
                        </p>
                        <div class="card__review">
                            <?= Html::img("@web/{$review->creator->path}", ['width' => 55, 'height' => 54]) ?>
                            <div class="feedback-card__reviews-content">
                                <p class="link-name link"><?= Html::encode($review->creator->name); ?></p>
                                <p class="review-text"><?= Html::encode($review->description); ?></p>
                            </div>
                            <div class="card__review-rate">
                                <p class="<?= $review->rating <= 3 ? 'three-rate':'five-rate' ?> big-rate">
                                    <?= Html::encode($review->rating); ?><span></span>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</section>
