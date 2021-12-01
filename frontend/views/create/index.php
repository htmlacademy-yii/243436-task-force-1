<?php
    use yii\widgets\ActiveForm;
?>

<section class="create__task">
    <h1>Публикация нового задания</h1>
    <div class="create__task-main">
        <?php $form = ActiveForm::begin([
            'enableClientValidation' => false,
            'id' => 'task-form',
            'options' => ['class' => 'create__task-form form-create'],
            'fieldConfig' => [
                'template' => "{label}\n
                    {input}\n
                    <span style='color: #8a8a8d;'>{hint}</span>\n
                    <span class='registration__text-error'>{error}</span>",
                'options' => ['class' => 'field-container'],
            ]
        ]); ?>

            <?= $form->field($tasks_form, 'name')
                ->textInput(['class' => 'input textarea', 'id' => 10, 'placeholder' => 'Повесить полку'])
                ->label('Мне нужно', ['for' => 10])
                ->hint('Кратко опишите суть работы'); ?>

            <?= $form->field($tasks_form, 'description')
                ->textarea(['class' => 'input textarea', 'rows' => 7, 'id' => 11, 'placeholder' => 'Опишите задание'])
                ->label('Подробности задания', ['for' => 11])
                ->hint('Укажите все пожелания и детали, чтобы исполнителям было проще соориентироваться'); ?>

            <?= $form->field($tasks_form, 'category_id', [
                    'template' => "{label}\n
                        {input}\n
                        <span style='color: #8a8a8d;'>{hint}</span>\n
                        <span class='registration__text-error'>{error}</span>",
                    'labelOptions' => ['for' => 12]
                    ])
                ->dropdownList($category_list, [
                    'value' => $tasks_form->category_id,
                    'class' => 'multiple-select input multiple-select-big',
                    'id' => 12,
                    'size' => 1,
                ])
                ->hint('Выберите категорию'); ?>

            <div class="field-container">
                <label>Файлы</label>
                <span>Загрузите файлы, которые помогут исполнителю лучше выполнить или оценить работу</span>
                <div class="create__file dropzone">

                </div>
            </div>

            <?= $form->field($tasks_form, 'address')
                ->textInput([
                    'class' => 'input-navigation input-middle input',
                    'id' => 'autoComplete',
                    'placeholder' => 'Санкт-Петербург, Калининский район',
                    'type' => 'search',
                    'dir' => 'ltr',
                    'spellcheck' => false,
                    'autocorrect' => 'off',
                    'autocomplete' => 'off',
                    'autocapitalize' => 'off',
                ])
                ->label('Локация', ['for' => 13])
                ->hint('Укажите адрес исполнения, если задание требует присутствия'); ?>

            <?= $form->field($tasks_form, 'lat')
                ->hiddenInput([
                    'id' => 'lat',
                ])
                ->label(false); ?>

            <?= $form->field($tasks_form, 'lon')
                ->hiddenInput([
                    'id' => 'lon',
                ])
                ->label(false); ?>

            <?= $form->field($tasks_form, 'city_id')
                ->hiddenInput([
                    'id' => 'city_id',
                ])
                ->label(false); ?>

            <div class="create__price-time">
                <?= $form->field($tasks_form, 'budget', [
                        'options' => ['tag' => 'div', 'class' => 'field-container create__price-time--wrapper']
                    ])
                    ->textInput([
                        'class' => 'input textarea input-money',
                        'id' => 14,
                        'placeholder' => '1000',
                    ])
                    ->label('Бюджет', ['for' => 14])
                    ->hint('Не заполняйте для оценки исполнителем'); ?>

                <?= $form->field($tasks_form, 'expire', [
                        'options' => ['tag' => 'div', 'class' => 'field-container create__price-time--wrapper']
                    ])
                    ->textInput([
                        'class' => 'input-middle input input-date',
                        'id' => 15,
                        'placeholder' => '10.11, 15:00',
                        'type' => 'date'
                    ])
                    ->label('Сроки исполнения', ['for' => 15])
                    ->hint('Укажите крайний срок исполнения'); ?>
            </div>

        <?php ActiveForm::end(); ?>

        <div class="create__warnings">
            <div class="warning-item warning-item--advice">
                <h2>Правила хорошего описания</h2>
                <h3>Подробности</h3>
                <p>Друзья, не используйте случайный<br>
                контент – ни наш, ни чей-либо еще. Заполняйте свои
                макеты, вайрфреймы, мокапы и прототипы реальным
                содержимым.</p>
                <h3>Файлы</h3>
                <p>Если загружаете фотографии объекта, то убедитесь,
                что всё в фокусе, а фото показывает объект со всех
                ракурсов.</p>
            </div>
            <?php if (!empty($tasks_form->getErrors())) : ?>
                <div class="warning-item warning-item--error">
                    <h2>Ошибки заполнения формы</h2>
                    <?php foreach ($tasks_form->getErrors() as $key => $value) : ?>
                        <h3>Поле «<?= $tasks_form->attributeLabels()[$key] ?>»</h3>
                        <p><?= $value[0]; ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <button form="task-form" class="button" type="submit">Опубликовать</button>
</section>

<?php
    $this->registerJsFile(
        '@web/js/dropzone.js',
        ['depends' => 'yii\web\YiiAsset', 'position' => \yii\web\View::POS_HEAD]
    );

    $this->registerJsFile(
        '@web/js/create.js',
        ['depends' => 'yii\web\YiiAsset']
    ); ?>
