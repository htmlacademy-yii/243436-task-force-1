<div class="header__town">
    <select class="multiple-select input town-select" size="1" name="town[]">
        <?php foreach($cities as $city) : ?>
            <option value="<?= $city['id'] ?>" <?= $city['id'] === $user->city_id ? 'selected' : '' ?> >
                <?= $city['name'] ?>
            </option>
        <?php endforeach; ?>
    </select>
</div>
