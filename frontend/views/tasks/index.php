<?php
    use yii\widgets\ActiveForm;
    use yii\helpers\Html;
    use yii\helpers\Url;
?>

<section class="new-task">
    <div class="new-task__wrapper">
        <h1>Новые задания</h1>
        <?php foreach($tasks as $task) : ?>
            <div class="new-task__card">
                <div class="new-task__title">
                <a href="<?= Url::to(['tasks/view', 'id' => $task['id']]) ?>" class="link-regular">
                    <h2><?= Html::encode($task->name); ?></h2>
                </a>
                <a class="new-task__type link-regular"
                href="<?= Url::to(['tasks/index', 'TasksForm'=>['category' => $task['category_id']]]) ?>">
                    <p><?= Html::encode($task->category->name); ?></p>
                </a>
                </div>
                <div class="new-task__icon new-task__icon--<?= Html::encode($task->category->icon); ?>"></div>
                <p class="new-task_description">
                    <?= Html::encode($task->description); ?>
                </p>
                <b class="new-task__price new-task__price--translation">
                    <?= Html::encode($task->budget); ?><b> ₽</b>
                </b>
                <p class="new-task__place">
                    <?= Html::encode($task->city->name ?? 'Удаленная работа'); ?>
                </p>
                <span class="new-task__time">
                    <?= Yii::$app->formatter->asRelativeTime(Html::encode($task->date_add)); ?>
                </span>
            </div>
        <?php endforeach; ?>
    </div>

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
</section>
<section class="search-task">
    <div class="search-task__wrapper">

        <?php $form = ActiveForm::begin([
            'method' => 'get',
            'action' => Url::to(['tasks/index']),
            'options' => ['class' => 'search-task__form', 'name' => 'tasks'],
        ]); ?>

            <fieldset class="search-task__categories">
                <legend>Категории</legend>
                <?= $form->field($tasksForm, 'category', [
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
                    ])
                ?>
            </fieldset>

            <fieldset class="search-task__categories">
                <legend>Дополнительно</legend>
                <?= $form->field($tasksForm, 'more', [
                        'options' => ['tag' => false],
                        'template' => '{input}'
                    ])
                    ->checkboxList($tasksForm->moreList(), [
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

            <div class="field-container">
                <?= $form->field($tasksForm, 'period', [
                    'options' => ['tag' => false],
                    'template' => "{label}\n{input}"
                    ])
                ->dropdownList($tasksForm->periodList(), [
                    'value' => $tasksForm->period ?? 'week',
                    'class' => 'multiple-select input',
                    'id' => 8,
                    'size' => 1,
                ])
                ->label('Период', [
                    'class' => 'search-task__name',
                    'for' => 8
                    ])
                ?>
            </div>

            <div class="field-container">
                <?= $form->field($tasksForm, 'search', [
                    'options' => ['tag' => false],
                    'template' => "{label}\n{input}"
                    ])
                ->input('search', [
                    'class' => 'input-middle input',
                    'id' => 9,
                    'placeholder' => ''
                ])
                ->label('Поиск по названию', [
                    'class' => 'search-task__name',
                    'for' => 9
                    ])
                ?>
            </div>

            <?= Html::submitButton('Искать', ['class' => 'button']) ?>

        <?php ActiveForm::end(); ?>
    </div>
</section>
