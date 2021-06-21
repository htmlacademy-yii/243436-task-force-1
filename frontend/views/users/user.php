<?php
    use yii\helpers\Url;
    use yii\helpers\Html;
?>

<section class="content-view">
    <div class="user__card-wrapper">
        <div class="user__card">
            <?= Html::img("@web/{$users->path}", ['width' => 120, 'height' => 120, 'alt' => 'Аватар пользователя']) ?>
            <div class="content-view__headline">
                <h1><?= $users->name; ?></h1>
                <p>
                    <?= $users->city->name; ?>,
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
                <?php if ($users->getAverageRating() >= 1 && $users->getAverageRating() < 2) : ?>
                        <span></span>
                        <span class="star-disabled"></span>
                        <span class="star-disabled"></span>
                        <span class="star-disabled"></span>
                        <span class="star-disabled"></span>
                    <?php elseif ($users->getAverageRating() >= 2 && $users->getAverageRating() < 3) : ?>
                        <span></span>
                        <span></span>
                        <span class="star-disabled"></span>
                        <span class="star-disabled"></span>
                        <span class="star-disabled"></span>
                    <?php elseif ($users->getAverageRating() >= 3 && $users->getAverageRating() < 4) : ?>
                        <span></span>
                        <span></span>
                        <span></span>
                        <span class="star-disabled"></span>
                        <span class="star-disabled"></span>
                    <?php elseif ($users->getAverageRating() >= 4 && $users->getAverageRating() < 5) : ?>
                        <span></span>
                        <span></span>
                        <span></span>
                        <span></span>
                        <span class="star-disabled"></span>
                    <?php elseif ($users->getAverageRating() >= 5) : ?>
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

                    <b><?= $users->getAverageRating(); ?></b>
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
            user__card-bookmark user__card-bookmark<?= in_array($users->id, $executors) ? '--current':'' ?>">
                <span>Был на сайте <?= Yii::$app->formatter->asRelativeTime($users->date_visit); ?></span>
                <a href="<?= Url::to(['users/update', 'id' => $users['id']]) ?>"><b></b></a>
            </div>
        </div>
        <div class="content-view__description">
            <p><?= $users->about; ?></p>
        </div>
        <div class="user__card-general-information">
            <div class="user__card-info">
                <h3 class="content-view__h3">Специализации</h3>
                <div class="link-specialization">
                    <?php foreach($users->categories as $category) : ?>
                        <a href="<?= Url::to(['users/index', 'UsersForm'=>['category' => $category->id]]) ?>"
                        class="link-regular">
                            <?= $category->name ?>
                        </a>
                    <?php endforeach; ?>
                </div>
                <h3 class="content-view__h3">Контакты</h3>
                <div class="user__card-link">
                    <a class="user__card-link--tel link-regular" href="#"><?= $users->phone; ?></a>
                    <a class="user__card-link--email link-regular" href="#"><?= $users->email; ?></a>
                    <a class="user__card-link--skype link-regular" href="#"><?= $users->skype; ?></a>
                </div>
            </div>
            <div class="user__card-photo">
                <h3 class="content-view__h3">Фото работ</h3>
                <?php if(!empty($photo_work)) : ?>
                    <?php foreach($photo_work as $photo) : ?>
                        <a href="<?= Url::to("@web/img/{$photo['path']}"); ?>" target="_blank">
                            <?= Html::img("@web/img/{$photo['path']}",
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
            <h2>Отзывы<span>(<?= $reviewsCount; ?>)</span></h2>
            <div class="content-view__feedback-wrapper reviews-wrapper">
                <?php foreach($reviews as $review) : ?>
                    <div class="feedback-card__reviews">
                        <p class="link-task link">
                            Задание
                            <a href="<?= Url::to(['tasks/view', 'id'=>$review->task_id]) ?>" class="link-regular">
                                «<?= $review->task->name; ?>»
                            </a>
                        </p>
                        <div class="card__review">
                            <?= Html::img("@web/{$review->creator->path}", ['width' => 55, 'height' => 54]) ?>
                            <div class="feedback-card__reviews-content">
                                <p class="link-name link"><?= $review->creator->name; ?></p>
                                <p class="review-text"><?= $review->description; ?></p>
                            </div>
                            <div class="card__review-rate">
                                <p class="<?= $review->rating <= 3 ? 'three-rate':'five-rate' ?> big-rate">
                                    <?= $review->rating; ?><span></span>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</section>
