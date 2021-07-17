<?php
    use yii\helpers\Html;
    use yii\helpers\Url;
    use yii\helpers\StringHelper;
?>

<div class="landing-bottom">
    <div class="landing-bottom-container">
        <h2>Последние задания на сайте</h2>
        <?php foreach($tasks as $task) : ?>
            <div class="landing-task">
                <div class="landing-task-top task-<?= Html::encode($task->category->icon); ?>"></div>
                <div class="landing-task-description">
                    <h3>
                        <a href="<?= Url::to(['tasks/view', 'id' => $task['id']]) ?>" class="link-regular">
                            <?= StringHelper::truncate(Html::encode($task->name), '19', '...'); ?>
                        </a>
                    </h3>
                    <p><?= StringHelper::truncate(Html::encode($task->description), '61', '...'); ?></p>
                </div>
                <div class="landing-task-info">
                    <div class="task-info-left">
                        <p>
                            <a href="<?= Url::to(['tasks/index', 'TasksForm'=>['category' => $task['category_id']]]) ?>"
                            class="link-regular"><?= Html::encode($task->category->name); ?></a>
                        </p>
                        <p><?= Yii::$app->formatter->asRelativeTime(Html::encode($task->date_add)); ?></p>
                    </div>
                    <span><?= Html::encode($task->budget); ?> <b>₽</b></span>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="landing-bottom-container">
        <button type="button" class="button red-button" onclick="document.location='<?= Url::to(['tasks/index']) ?>'">
            смотреть все задания
        </button>
    </div>
</div>
