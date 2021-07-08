<?php
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
?>

<section class="registration__user">
    <h1>Регистрация аккаунта</h1>
    <div class="registration-wrapper">
        <?php $form = ActiveForm::begin([
            'options' => ['class' => 'registration__user-form form-create'],
            'errorCssClass' => 'has-error',
            'fieldConfig' => [
                'template' => "{label}\n{input}\n{error}",
                'options' => ['class' => 'field-container field-container--registration'],
                'inputOptions' => ['class' => 'input textarea'],
                'errorOptions' => ['tag' => 'span', 'class' => 'registration__text-error']
            ]
        ]); ?>

            <?= $form->field($user_form, 'email')->textInput(['placeholder' => 'Email']); ?>
            <?= $form->field($user_form, 'name')->textInput(['placeholder' => 'Ваше имя']); ?>
            <?= $form->field($user_form, 'city_id', [
                    'template' => "{label}\n{input}"
                    ])
                ->dropdownList($cities_list, [
                    'value' => $user_form->city_id,
                    'class' => 'multiple-select input town-select registration-town',
                    'id' => 18,
                    'size' => 1,
                ])
            ?>
            <?= $form->field($user_form, 'password')->passwordInput(['placeholder' => 'Пароль']); ?>

            <?= Html::submitButton('Создать аккаунт', ['class' => 'button button__registration']) ?>

        <?php ActiveForm::end(); ?>
    </div>
</section>
