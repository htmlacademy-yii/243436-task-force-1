<?php
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\widgets\LinkPager;
?>

<section class="user__search">
    <div class="new-user__wrapper">
        <h1>Исполнители</h1>
        <?php foreach ($dataProvider->getModels() as $user) : ?>
            <?php if ((int) $user->show_profile !== 1) : ?>
                <div class="content-view__feedback-card user__search-wrapper">
                    <div class="feedback-card__top">
                        <div class="user__search-icon">
                        <a href="<?= Url::to(['users/user', 'id' => $user['id']]) ?>">
                            <?= Html::img("@web/{$user->path}", ['width' => 65, 'height' => 65]) ?>
                        </a>
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
                                ['n' => $user->tasksCount]
                            ); ?>
                        </span>
                        <span>
                            <?= Yii::t(
                                'app',
                                '{n, plural,
                                    =0{# отзывов}
                                    =1{# отзыв}
                                    one{# отзыв}
                                    few{# отзыва}
                                    many{# отзывов}
                                    other{# отзыва}}',
                                ['n' => $user->reviewsCount]
                            ); ?>
                        </span>
                        </div>
                        <div class="feedback-card__top--name user__search-card">
                        <p class="link-name">
                            <a href="<?= Url::to(['users/user', 'id' => $user['id']]) ?>" class="link-regular">
                                <?= Html::encode($user->name); ?>
                            </a>
                        </p>
                        <?php $average_rating = $user->getAverageRating(); ?>
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
                        <p class="user__search-content"><?= Html::encode($user->about); ?></p>
                        </div>
                        <span class="new-task__time">
                            <?php if (($current_time - $user->date_visit) < 1800) : ?>
                                Сейчас онлайн
                            <?php else : ?>
                                Был на сайте
                                <?= Yii::$app->formatter->asRelativeTime(Html::encode($user->date_visit)); ?>
                            <?php endif; ?>
                        </span>
                    </div>

                    <div class="link-specialization user__search-link--bottom">
                        <?php foreach ($user->categories as $category) : ?>
                            <a href="<?= Url::to(['users/index', 'UsersForm'=>['category' => $category->id]]) ?>"
                            class="link-regular">
                                <?= Html::encode($category->name); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <div>
        <div class="new-task__pagination">
            <?= LinkPager::widget([
                'pagination' => $dataProvider->pagination,
                'options' => ['class' => 'new-task__pagination-list'],
                'linkContainerOptions' => ['class'=>'pagination__item'],
                'activePageCssClass' => 'pagination__item--current',
                'prevPageLabel' => '',
                'nextPageLabel' => ''
            ]) ?>
        </div>
    </div>

</section>



<section class="search-task">
    <div class="search-task__wrapper">

        <?php $form = ActiveForm::begin([
            'method' => 'get',
            'action' => Url::to(['users/index']),
            'options' => ['class' => 'search-task__form', 'name' => 'users'],
        ]); ?>

            <fieldset class="search-task__categories">
                <legend>Категории</legend>
                <?= $form->field($usersForm, 'category', [
                        'options' => ['tag' => false],
                        'template' => "{input}"
                    ])
                    ->checkboxList($categories->categoryList(), [
                        'item' => function ($index, $label, $name, $checked, $value) {
                            $checked = $checked ? 'checked' : '';

                            return "
                            <label class='checkbox__legend'>
                                <input class='visually-hidden checkbox__input' type='checkbox' name='{$name}'
                                value='{$value}' {$checked}>
                                <span>{$label}</span>
                            </label>";
                        }
                    ]) ?>
            </fieldset>

            <fieldset class="search-task__categories">
                <legend>Дополнительно</legend>
                <?= $form->field($usersForm, 'more', [
                        'options' => ['tag' => false],
                        'template' => '{input}'
                    ])
                    ->checkboxList($usersForm->moreList(), [
                        'item' => function ($index, $label, $name, $checked, $value) {
                            $checked = $checked ? 'checked' : '';

                            return "
                            <label class='checkbox__legend'>
                                <input class='visually-hidden checkbox__input' type='checkbox' name='{$name}'
                                value='{$value}' {$checked}>
                                <span>{$label}</span>
                            </label>";
                        }
                    ]) ?>
            </fieldset>

            <?= $form->field($usersForm, 'search', [
                    'options' => ['tag' => false],
                    'template' => "{label}\n{input}"
                    ])
                ->input('search', [
                    'class' => 'input-middle input',
                    'id' => 9,
                    'placeholder' => '',
                    'for' => 110
                ])
                ->label('Поиск по имени', [
                    'class' => 'search-task__name',
                    'for' => 9
                    ]) ?>

            <?= Html::submitButton('Искать', ['class' => 'button']) ?>

        <?php ActiveForm::end(); ?>

    </div>
</section>
