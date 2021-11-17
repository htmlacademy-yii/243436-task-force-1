<?php
    use yii\helpers\Url;
?>

<div class="header__lightbulb
<?php if ($messages
    || $tasks_work || (isset($responds[0]) && $responds[0]) || $tasks_failed
    || $tasks_completed) : ?>
active
<?php endif; ?>
"></div>

<div class="lightbulb__pop-up">
    <h3>Новые события</h3>

    <?php if ($messages
    || $tasks_work
    || (isset($responds[0]) && $responds[0]) || $tasks_failed || $tasks_completed) : ?>
        <?php if ($messages) : ?>
            <?php foreach ($messages as $message) : ?>
                <p class="lightbulb__new-task lightbulb__new-task--message">
                    Новое сообщение в чате
                    <a href="<?= Url::to(['tasks/view', 'id' => $message['task_id']]) ?>" class="link-regular">
                        «<?= $message->task->name ?>»
                    </a>
                </p>
            <?php endforeach; ?>
        <?php endif; ?>

        <?php if ($tasks_work) : ?>
            <?php foreach ($tasks_work as $task) : ?>
                <p class="lightbulb__new-task lightbulb__new-task--executor">
                    Выбран исполнитель для
                    <a href="<?= Url::to(['tasks/view', 'id' => $task['id']]) ?>" class="link-regular">
                        «<?= $task['name'] ?>»
                    </a>
                </p>
            <?php endforeach; ?>
        <?php endif; ?>

        <?php if (isset($responds[0]) && $responds[0]) : ?>
            <?php foreach ($responds as $respond) : ?>
                <?php foreach ($respond as $value) : ?>
                    <p class="lightbulb__new-task lightbulb__new-task--executor">
                        Новый отклик по задаче
                        <a href="<?= Url::to(['tasks/view', 'id' => $value['task_id']]) ?>" class="link-regular">
                            «<?= $value->task->name; ?>»
                        </a>
                    </p>
                <?php endforeach; ?>
            <?php endforeach; ?>
        <?php endif; ?>

        <?php if ($tasks_failed) : ?>
            <?php foreach ($tasks_failed as $task) : ?>
                <p class="lightbulb__new-task lightbulb__new-task--close">
                    Отказ от задания
                    <a href="<?= Url::to(['tasks/view', 'id' => $task['id']]) ?>" class="link-regular">
                        «<?= $task['name'] ?>»
                    </a>
                </p>
            <?php endforeach; ?>
        <?php endif; ?>

        <?php if ($tasks_completed) : ?>
            <?php foreach ($tasks_completed as $task) : ?>
                <p class="lightbulb__new-task lightbulb__new-task--close">
                    Завершено задание
                    <a href="<?= Url::to(['tasks/view', 'id' => $task['id']]) ?>" class="link-regular">
                        «<?= $task['name'] ?>»
                    </a>
                </p>
            <?php endforeach; ?>
        <?php endif; ?>

    <?php else : ?>
        <p>Пока ничего нет...</p>
    <?php endif; ?>
</div>
