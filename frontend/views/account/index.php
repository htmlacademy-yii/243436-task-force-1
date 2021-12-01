<?php
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    use frontend\models\UsersAndCategories;
?>

<section class="account__redaction-wrapper">
    <h1>Редактирование настроек профиля</h1>

    <?php $form = ActiveForm::begin([
        'enableClientValidation' => false,
        'id' => 'account',
        'fieldConfig' => [
            'template' => "{label}\n
                {input}\n
                <span style='color: #8a8a8d;'>{hint}</span>\n
                <span class='registration__text-error'>{error}</span>",
        ]
    ]); ?>

        <div class="account__redaction-section">
            <h3 class="div-line">Настройки аккаунта</h3>
            <div class="account__redaction-section-wrapper">
                <div class="account__redaction-avatar">
                    <?= Html::img($user->path, ['width' => 156, 'height' => 156]) ?>

                    <?= $form->field($user, 'avatar', [
                            'template' => "{label}\n
                                {input}\n
                                <span class='registration__text-error'>{error}</span>",
                            ])
                        ->fileInput([
                            'id' => 'upload-avatar'
                        ])
                        ->label('Сменить аватар', [
                            'for' => 'upload-avatar',
                            'class' => 'link-regular'
                        ]) ?>
                </div>

                <div class="account__redaction">
                    <?= $form->field($user, 'name', [
                            'options' => ['class' => 'field-container account__input account__input--name']
                        ])
                        ->textInput([
                            'class' => 'input textarea',
                            'id' => 200,
                            'placeholder' => 'Титов Денис',
                            'value' => $user->name ?? ''
                        ])
                        ->label('Ваше имя', ['for' => 200]); ?>

                    <?= $form->field($user, 'email', [
                            'options' => ['class' => 'field-container account__input account__input--email']
                        ])
                        ->textInput([
                            'class' => 'input textarea',
                            'id' => 201,
                            'placeholder' => 'DenisT@bk.ru',
                            'value' => $user->email ?? ''
                        ])
                        ->label('email', ['for' => 201]); ?>

                    <?= $form->field($user, 'address', [
                        'options' => ['class' => 'field-container account__input account__input--address']
                    ])
                        ->textInput([
                            'class' => 'input textarea',
                            'id' => 'autoComplete',
                            'placeholder' => 'Санкт-Петербург, Калининский район',
                            'type' => 'search',
                            'dir' => 'ltr',
                            'spellcheck' => false,
                            'autocorrect' => 'off',
                            'autocomplete' => 'off',
                            'autocapitalize' => 'off',
                            'value' => $user->address ?? ''
                        ])
                        ->label('Локация'); ?>

                    <?= $form->field($user, 'city_id')
                        ->hiddenInput([
                            'id' => 'city_id',
                        ])
                        ->label(false); ?>

                    <?= $form->field($user, 'birthday', [
                            'options' => ['class' => 'field-container account__input account__input--date']
                        ])
                        ->textInput([
                            'class' => 'input-middle input input-date',
                            'id' => 203,
                            'placeholder' => '15.08.1987',
                            'type' => 'date',
                            'value' =>  Yii::$app->formatter->asDate($user->birthday, 'YYYY-MM-dd') ?? ''
                        ])
                        ->label('День рождения', ['for' => 203]); ?>

                    <?= $form->field($user, 'about', [
                        'options' => ['class' => 'field-container account__input account__input--info']
                    ])
                        ->textarea([
                            'class' => 'input textarea',
                            'rows' => 7,
                            'id' => 204,
                            'placeholder' => 'Введите информацию о себе',
                            'value' =>  $user->about ?? ''

                        ])
                        ->label('Информация о себе', ['for' => 204]); ?>


                </div>
            </div>

            <h3 class="div-line">Выберите свои специализации</h3>
            <div class="account__redaction-section-wrapper">
                <div class="search-task__categories account_checkbox--bottom">
                    <?= $form->field($tasksForm, 'category', [
                            'options' => ['tag' => false],
                            'template' => "{input}"
                        ])
                        ->checkboxList($categories->categoryList(), [
                            'item' => function ($index, $label, $name, $checked, $value) {
                                $usersAndCategories = new UsersAndCategories();
                                if (in_array(
                                    $value,
                                    $usersAndCategories->usersAndCategoriesList(\Yii::$app->user->getId())
                                )) {
                                    $checked = 'checked';
                                }

                                return "
                                <label class='checkbox__legend'>
                                    <input class='visually-hidden checkbox__input' type='checkbox' name='{$name}'
                                    value='{$value}' {$checked}>
                                    <span>{$label}</span>
                                </label>";
                            }
                        ]) ?>

                </div>
            </div>

            <h3 class="div-line">Безопасность</h3>
            <div class="account__redaction-section-wrapper account__redaction">

                <?= $form->field($changePassword, 'password', [
                        'options' => ['class' => 'field-container account__input'],
                    ])
                    ->passwordInput([
                        'class' => 'input textarea',
                        'id' => '2121',
                        'autocomplete' => 'on'
                    ])
                    ->label('Текущий пароль', ['for' => 2121]); ?>

                <?= $form->field($changePassword, 'password_new', [
                        'options' => ['class' => 'field-container account__input'],
                    ])
                    ->passwordInput([
                        'class' => 'input textarea',
                        'id' => '211',
                        'autocomplete' => 'on'
                    ])
                    ->label('Новый пароль', ['for' => 211]); ?>

                <?= $form->field($changePassword, 'password_repeat', [
                        'options' => ['class' => 'field-container account__input'],
                    ])
                    ->passwordInput([
                        'class' => 'input textarea',
                        'id' => '212',
                        'autocomplete' => 'on'
                    ])
                    ->label('Повтор пароля', ['for' => 212]); ?>
            </div>

            <h3 class="div-line">Фото работ</h3>

            <div class="user__card-photo">
                <?php if (!empty($photoWorkList)) : ?>
                    <?php foreach ($photoWorkList as $photo) : ?>
                        <?= Html::img(
                            "@web/uploads/{$photo['path']}",
                            ['width' => 100, 'height' => 101, 'alt' => 'Фото работы']
                        ) ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="account__redaction-section-wrapper account__redaction dropzone">

            </div>

            <h3 class="div-line">Контакты</h3>
            <div class="account__redaction-section-wrapper account__redaction">
                <?= $form->field($user, 'phone', [
                        'options' => ['class' => 'field-container account__input']
                    ])
                    ->textInput([
                        'class' => 'input textarea',
                        'id' => 213,
                        'placeholder' => '8 (555) 187 44 87',
                        'type' => 'tel',
                        'value' =>  $user->phone ?? ''
                    ])
                    ->label('Телефон', ['for' => 213]); ?>

                <?= $form->field($user, 'skype', [
                        'options' => ['class' => 'field-container account__input']
                    ])
                    ->textInput([
                        'class' => 'input textarea',
                        'id' => 214,
                        'placeholder' => 'DenisT',
                        'value' =>  $user->skype ?? ''
                    ])
                    ->label('Skype', ['for' => 214]); ?>

                <?= $form->field($user, 'messenger', [
                        'options' => ['class' => 'field-container account__input']
                    ])
                    ->textInput([
                        'class' => 'input textarea',
                        'id' => 215,
                        'placeholder' => '@DenisT',
                        'value' =>  $user->messenger ?? ''
                    ])
                    ->label('Другой мессенджер', ['for' => 215]); ?>
            </div>

            <h3 class="div-line">Настройки сайта</h3>
            <h4>Уведомления</h4>
            <div class="account__redaction-section-wrapper account_section--bottom">
                <div class="search-task__categories account_checkbox--bottom">
                    <?= $form->field($user, 'new_message', [
                        'options' => ['tag' => false]
                    ])
                        ->checkbox([
                            'labelOptions' => ['class' => 'checkbox__legend'],
                            'class' => 'visually-hidden checkbox__input',
                            'label' => '<span>Новое сообщение</span>',
                            'checked' => (int) $user->new_message !== 1 ? false:true
                        ]); ?>

                    <?= $form->field($user, 'action_task', [
                        'options' => ['tag' => false]
                    ])
                        ->checkbox([
                            'labelOptions' => ['class' => 'checkbox__legend'],
                            'class' => 'visually-hidden checkbox__input',
                            'label' => '<span>Действия по заданию</span>',
                            'checked' => (int) $user->action_task !== 1 ? false:true
                        ]); ?>

                    <?= $form->field($user, 'new_review', [
                        'options' => ['tag' => false]
                    ])
                        ->checkbox([
                            'labelOptions' => ['class' => 'checkbox__legend'],
                            'class' => 'visually-hidden checkbox__input',
                            'label' => '<span>Новый отзыв</span>',
                            'checked' => (int) $user->new_review !== 1 ? false:true
                        ]); ?>
                </div>

                <div class="search-task__categories account_checkbox account_checkbox--secrecy">
                    <?= $form->field($user, 'show_contacts', [
                        'options' => ['tag' => false]
                    ])
                        ->checkbox([
                            'labelOptions' => ['class' => 'checkbox__legend'],
                            'class' => 'visually-hidden checkbox__input',
                            'label' => '<span>Показывать мои контакты только заказчику</span>',
                            'checked' => (int) $user->show_contacts !== 1 ? false:true
                        ]); ?>

                    <?= $form->field($user, 'show_profile', [
                        'options' => ['tag' => false]
                    ])
                        ->checkbox([
                            'labelOptions' => ['class' => 'checkbox__legend'],
                            'class' => 'visually-hidden checkbox__input',
                            'label' => '<span>Не показывать мой профиль</span>',
                            'checked' => (int) $user->show_profile !== 1 ? false:true
                        ]); ?>
                </div>
            </div>
        </div>

        <?= Html::submitButton('Сохранить изменения', ['class' => 'button']) ?>

    <?php ActiveForm::end(); ?>

</section>

<?php
    $this->registerJsFile(
        '@web/js/dropzone.js',
        ['depends' => 'yii\web\YiiAsset', 'position' => \yii\web\View::POS_HEAD]
    );

    $this->registerJsFile(
        '@web/js/account.js',
        ['depends' => 'yii\web\YiiAsset']
    ); ?>
