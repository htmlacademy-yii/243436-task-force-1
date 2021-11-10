<div class="header__town">
    <select class="multiple-select input town-select" size="1" name="town[]">
        <?php foreach($cities as $city) : ?>
            <?php if (\Yii::$app->request->get('city')) : ?>
                <option value="<?= $city['id'] ?>"
                <?= $city['id'] === (int) \Yii::$app->request->get('city') ? 'selected' : '' ?>>
                    <?= $city['name'] ?>
                </option>
            <?php elseif ($session['city_id']) : ?>
                <option value="<?= $city['id'] ?>"
                <?= $city['id'] === (int) $session['city_id'] ? 'selected' : '' ?>>
                    <?= $city['name'] ?>
                </option>
            <?php else : ?>
                <option value="<?= $city['id'] ?>"
                <?= $city['id'] === $user->city_id ? 'selected' : '' ?>>
                    <?= $city['name'] ?>
                </option>
            <?php endif; ?>
        <?php endforeach; ?>
    </select>
</div>
