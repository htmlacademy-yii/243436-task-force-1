<?php
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    use yii\helpers\Url;
?>

<section class="user__search">
    <div class="new-user__wrapper">
        <h1>Исполнители</h1>
        <?php foreach($users as $user) : ?>
            <div class="content-view__feedback-card user__search-wrapper">
                <div class="feedback-card__top">
                    <div class="user__search-icon">
                    <a href="user.html">
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
                            ['n' => $user->tasksCount]
                        ); ?>
                    </span>
                    </div>
                    <div class="feedback-card__top--name user__search-card">
                    <p class="link-name"><a href="user.html" class="link-regular"><?= $user->name; ?></a></p>

                    <?php switch ($user->getAverageRating($user->id)):
                        case 1: ?>
                            <span></span>
                            <span class="star-disabled"></span>
                            <span class="star-disabled"></span>
                            <span class="star-disabled"></span>
                            <span class="star-disabled"></span>
                        <?php break; ?>
                        <?php case 2: ?>
                            <span></span>
                            <span></span>
                            <span class="star-disabled"></span>
                            <span class="star-disabled"></span>
                            <span class="star-disabled"></span>
                        <?php break; ?>
                        <?php case 3: ?>
                            <span></span>
                            <span></span>
                            <span></span>
                            <span class="star-disabled"></span>
                            <span class="star-disabled"></span>
                        <?php break; ?>
                        <?php case 4: ?>
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                            <span class="star-disabled"></span>
                        <?php break; ?>
                        <?php case 5: ?>
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                            <span></span>
                        <?php break; ?>
                        <?php default: ?>
                            <span class="star-disabled"></span>
                            <span class="star-disabled"></span>
                            <span class="star-disabled"></span>
                            <span class="star-disabled"></span>
                            <span class="star-disabled"></span>
                    <?php endswitch; ?>

                    <b><?= $user->getAverageRating(); ?></b>
                    <p class="user__search-content"><?= $user->about; ?></p>
                    </div>
                    <span class="new-task__time">
                        Был на сайте <?= Yii::$app->formatter->asRelativeTime($user->date_visit); ?>
                    </span>
                </div>

                <div class="link-specialization user__search-link--bottom">
                    <?php foreach($user->categories as $category) : ?>
                        <a href="browse.html" class="link-regular"><?= $category->name ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div>
        <div class="new-task__pagination">
            <ul class="new-task__pagination-list">
            <li class="pagination__item"><a href="#"></a></li>
            <li class="pagination__item pagination__item--current">
                <a>1</a></li>
            <li class="pagination__item"><a href="#">2</a></li>
            <li class="pagination__item"><a href="#">3</a></li>
            <li class="pagination__item"><a href="#"></a></li>
            </ul>
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
                    ->checkboxList($usersForm->categoryList(), [
                        'item' => function ($index, $label, $name, $checked, $value) {
                            $checked = $checked ? 'checked' : '';

                            return "
                            <label class='checkbox__legend'>
                                <input class='visually-hidden checkbox__input' type='checkbox' name='{$name}'
                                value='{$value}' {$checked}>
                                <span>{$label}</span>
                            </label>";
                        }
                    ])
                ?>
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
                    ])
                ?>
            </fieldset>

            <?= $form->field($usersForm, 'search', [
                    'options' => ['tag' => false],
                    'template' => "{label}\n{input}"
                    ])
                ->input('search', [
                    'value' => \Yii::$app->request->get('UsersForm')['search'] ?? '',
                    'class' => 'input-middle input',
                    'id' => 9,
                    'name' => 'UsersForm[search]',
                    'placeholder' => '',
                    'for' => 110
                ])
                ->label('Поиск по имени', [
                    'class' => 'search-task__name',
                    'for' => 9
                    ])
            ?>

            <?= Html::submitButton('Искать', ['class' => 'button']) ?>

        <?php ActiveForm::end(); ?>

    </div>
</section>
